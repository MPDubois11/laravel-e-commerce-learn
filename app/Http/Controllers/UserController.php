<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\ProductCart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Redirect;
use Barryvdh\DomPDF\Facade\Pdf;

class UserController extends Controller
{
    public function index() {
        if (Auth::check() && Auth::user()->user_type == "user") {
            $orders = Order::where('user_id', Auth::id())
                ->with('items.product')
                ->latest()
                ->get();
            return view('dashboard', compact('orders'));
        } elseif (Auth::check() && Auth::user()->user_type == "admin") {
            return view('admin.dashboard');
        }
    }

    public function homePage() {
        $products = Product::latest()->take(8)->get();

        return view('index', compact('products'));
    }

    public function productPage($id) {
        $product = Product::findOrFail($id);

        // Calculate available stock (product quantity - items already in user's cart)
        $inCart = 0;
        if (Auth::check()) {
            $inCart = ProductCart::where('product_id', $id)
                                 ->count();
        }
        $availableStock = max(0, $product->quantity - $inCart);

        return view('product', compact('product', 'availableStock'));
    }

    public function productsPage() {
        $products = Product::paginate(12);

        return view('products', compact('products'));
    }

    public function addToCart(Request $request, $id) {
        $product = Product::findOrFail($id);
        $quantity = $request->input('quantity', 1);
        
        // Validate quantity
        $quantity = max(1, min($quantity, $product->quantity));

        // Add the specified quantity of items to cart
        for ($i = 0; $i < $quantity; $i++) {
            $product_in_cart = new ProductCart();
            $product_in_cart->user_id = Auth::id();
            $product_in_cart->product_id = $product->id;
            $product_in_cart->save();
        }

        $message = $quantity > 1 
            ? "$quantity items have been added to your cart." 
            : 'The product has been added to your cart.';

        return redirect()->back()->with('cart_message', $message);
    }

    public function viewCart() {
        $cartItems = ProductCart::where('user_id', Auth::id())
            ->selectRaw('product_id, COUNT(*) as quantity, MIN(id) as id')
            ->groupBy('product_id')
            ->get();

        // Load the product relationship for each grouped item
        foreach ($cartItems as $item) {
            $item->product = Product::find($item->product_id);
        }

        return view('cart', compact('cartItems'));
    }

    public function removeFromCart($productId) {
        // Remove all cart entries for this product
        ProductCart::where('product_id', $productId)
                   ->where('user_id', Auth::id())
                   ->delete();

        return redirect()->back()->with('cart_removed', 'Item removed from your cart.');
    }

    public function checkoutPage() {
        $cartItems = ProductCart::where('user_id', Auth::id())
            ->selectRaw('product_id, COUNT(*) as quantity, MIN(id) as id')
            ->groupBy('product_id')
            ->get();

        if ($cartItems->count() > 0) {
            // Load the product relationship for each grouped item
            foreach ($cartItems as $item) {
                $item->product = Product::find($item->product_id);
            }

            return view('checkout', compact('cartItems'));
        } else {
            return Redirect::to('/');
        }
    }

    public function placeOrder(Request $request) {
        \Stripe\Stripe::setApiKey(config('app.stripe_secret_key'));

        // Get cart items
        $cartItems = ProductCart::where('user_id', Auth::id())
            ->selectRaw('product_id, COUNT(*) as quantity, MIN(id) as id')
            ->groupBy('product_id')
            ->get();

        // Load products for each cart item
        foreach ($cartItems as $item) {
            $item->product = Product::find($item->product_id);
        }

        // Calculate totals first
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->product->price * $item->quantity;
        }
        $tax = $subtotal * 0.08;
        $total = $subtotal + $tax;

        // Create and SAVE the order first to get the ID
        $order = new Order();
        $order->user_id = Auth::id();
        $order->email = $request->email;
        $order->phone = $request->phone;
        $order->first_name = $request->first_name;
        $order->last_name = $request->last_name;
        $order->address = $request->address;
        $order->address_2 = $request->address_2;
        $order->city = $request->city;
        $order->state = $request->state;
        $order->postal_code = $request->postal_code;
        $order->country = $request->country;
        $order->subtotal = $subtotal;
        $order->tax = $tax;
        $order->total = $total;
        $order->status = 'pending';
        $order->payment_status = 'unpaid';
        $order->save();

        // Create order items with the order_id
        foreach ($cartItems as $item) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $item->product_id;
            $orderItem->quantity = $item->quantity;
            $orderItem->price = $item->product->price;
            $orderItem->save();
        }

        // Build line items for Stripe
        $lineItems = [];
        foreach ($cartItems as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item->product->name,
                    ],
                    'unit_amount' => $item->product->price * 100, // Stripe uses cents
                ],
                'quantity' => $item->quantity,
            ];
        }

        // Add tax as a line item
        $lineItems[] = [
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => 'Tax (8%)',
                ],
                'unit_amount' => round($tax * 100),
            ],
            'quantity' => 1,
        ];

        // Create Stripe Checkout Session
        $checkoutSession = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('stripe.success', ['order' => $order->id]) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('stripe.cancel', ['order' => $order->id]),
            'customer_email' => $order->email,
            'metadata' => [
                'order_id' => $order->id,
            ],
        ]);

        // Save the session ID to the order
        $order->stripe_session_id = $checkoutSession->id;
        $order->save();

        // Redirect to Stripe Checkout
        return redirect($checkoutSession->url);
    }

    public function stripeSuccess(Request $request, $orderId) {
        \Stripe\Stripe::setApiKey(config('app.stripe_secret_key'));

        $order = Order::findOrFail($orderId);

        // Verify the order belongs to this user
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('index');
        }

        // Retrieve the session to verify payment
        if ($request->has('session_id')) {
            // Retrieve session with expanded payment_intent and customer
            $session = \Stripe\Checkout\Session::retrieve([
                'id' => $request->session_id,
                'expand' => ['payment_intent.payment_method', 'customer'],
            ]);

            if ($session->payment_status === 'paid') {
                $order->payment_status = 'paid';
                $order->stripe_payment_intent_id = $session->payment_intent->id ?? $session->payment_intent;
                $order->stripe_customer_id = $session->customer->id ?? $session->customer;
                $order->paid_at = now();

                // Get payment method details from the expanded payment_intent
                if (isset($session->payment_intent->payment_method)) {
                    $paymentMethod = $session->payment_intent->payment_method;
                    $order->payment_method = $paymentMethod->type ?? 'card';
                    
                    if (isset($paymentMethod->card)) {
                        $order->card_brand = $paymentMethod->card->brand;
                        $order->card_last_four = $paymentMethod->card->last4;
                    }
                }

                $order->save();

                // Clear the user's cart
                ProductCart::where('user_id', Auth::id())->delete();
            }
        }

        return redirect()->route('thankyou', $order->id);
    }

    public function stripeCancel($orderId) {
        $order = Order::findOrFail($orderId);

        // Verify the order belongs to this user
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('index');
        }

        // Mark order as cancelled
        $order->status = 'cancelled';
        $order->payment_status = 'failed';
        $order->save();

        return redirect()->route('viewcart')->with('error', 'Payment was cancelled. Your cart items are still saved.');
    }

    public function thankYouPage($orderId) {
        $order = Order::with('items.product')->findOrFail($orderId);

        // Make sure the order belongs to the current user
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('index');
        }

        return view('thankyou', compact('order'));
    }

    public function downloadInvoice($orderId) {
        $order = Order::with('items.product')->findOrFail($orderId);

        // Ensure the authenticated user owns this order
        if (Auth::id() !== $order->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $pdf = Pdf::loadView('downloadinvoice', compact('order'));
        $filename = 'invoice-' . str_pad($order->id, 6, '0', STR_PAD_LEFT) . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Handle Stripe webhook events
     * See: https://docs.stripe.com/webhooks/quickstart?lang=php
     */
    public function stripeWebhook(Request $request) {
        \Stripe\Stripe::setApiKey(config('app.stripe_secret_key'));

        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('app.stripe_webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sigHeader,
                $webhookSecret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                $this->handleCheckoutSessionCompleted($session);
                break;

            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                $this->handlePaymentIntentSucceeded($paymentIntent);
                break;

            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object;
                $this->handlePaymentIntentFailed($paymentIntent);
                break;

            default:
                // Unexpected event type
                \Log::info('Unhandled Stripe webhook event: ' . $event->type);
        }

        return response()->json(['status' => 'success'], 200);
    }

    private function handleCheckoutSessionCompleted($session) {
        // Find order by stripe_session_id
        $order = Order::where('stripe_session_id', $session->id)->first();

        if ($order && $order->payment_status !== 'paid') {
            $order->payment_status = 'paid';
            $order->stripe_payment_intent_id = $session->payment_intent;
            $order->paid_at = now();
            $order->status = 'processing';
            $order->save();

            // Clear cart for this user
            ProductCart::where('user_id', $order->user_id)->delete();

            \Log::info('Order #' . $order->id . ' marked as paid via webhook');
        }
    }

    private function handlePaymentIntentSucceeded($paymentIntent) {
        $order = Order::where('stripe_payment_intent_id', $paymentIntent->id)->first();

        if ($order && $order->payment_status !== 'paid') {
            $order->payment_status = 'paid';
            $order->paid_at = now();

            // Get payment method details
            if (isset($paymentIntent->payment_method)) {
                try {
                    $pm = \Stripe\PaymentMethod::retrieve($paymentIntent->payment_method);
                    if ($pm->type === 'card') {
                        $order->payment_method = 'card';
                        $order->card_brand = $pm->card->brand;
                        $order->card_last_four = $pm->card->last4;
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to retrieve payment method: ' . $e->getMessage());
                }
            }

            $order->save();
        }
    }

    private function handlePaymentIntentFailed($paymentIntent) {
        $order = Order::where('stripe_payment_intent_id', $paymentIntent->id)->first();

        if ($order) {
            $order->payment_status = 'failed';
            $order->save();

            \Log::warning('Order #' . $order->id . ' payment failed');
        }
    }
}
