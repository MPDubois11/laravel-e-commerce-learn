<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\ProductCart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    public function index() {
        if (Auth::check() && Auth::user()->user_type == "user") {
            return view('dashboard');
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
        $order->save(); // NOW $order->id exists!

        // Create order items with the order_id
        foreach ($cartItems as $item) {
            $orderItem = new OrderItem(); // Create NEW instance for each item!
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $item->product_id;
            $orderItem->quantity = $item->quantity;
            $orderItem->price = $item->product->price;
            $orderItem->save();
        }

        // Clear the user's cart
        ProductCart::where('user_id', Auth::id())->delete();

        return redirect()->route('thankyou', $order->id);
    }

    public function thankYouPage($orderId) {
        $order = Order::with('items.product')->findOrFail($orderId);

        // Make sure the order belongs to the current user
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('index');
        }

        return view('thankyou', compact('order'));
    }
}
