@extends('layouts.app')

@section('content')
@include('layouts.header')

<section class="thankyou_section layout_padding">
    <div class="container">
        <div class="thankyou-container">
            <div class="thankyou-header">
                <div class="success-icon">
                    <i class="fa fa-check"></i>
                </div>
                <h1>Thank You!</h1>
                <p class="order-confirmed">Your order has been placed successfully</p>
                <p class="order-number">Order #{{ $order->id }}</p>
            </div>

            <div class="order-details-card">
                <h3><i class="fa fa-box"></i> Order Summary</h3>
                
                <div class="order-items-list">
                    @foreach($order->items as $item)
                    <div class="order-item-row">
                        <div class="item-info">
                            <span class="item-name">{{ $item->product->name ?? 'Product' }}</span>
                            <span class="item-qty">× {{ $item->quantity }}</span>
                        </div>
                        <span class="item-price">${{ number_format($item->price * $item->quantity, 2) }}</span>
                    </div>
                    @endforeach
                </div>

                <div class="order-totals-section">
                    <div class="total-line">
                        <span>Subtotal</span>
                        <span>${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="total-line">
                        <span>Tax</span>
                        <span>${{ number_format($order->tax, 2) }}</span>
                    </div>
                    <div class="total-line">
                        <span>Shipping</span>
                        <span class="free-text">FREE</span>
                    </div>
                    <hr>
                    <div class="total-line grand-total">
                        <span>Total Paid</span>
                        <span>${{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="shipping-details-card">
                <h3><i class="fa fa-truck"></i> Shipping To</h3>
                <p class="shipping-name">{{ $order->first_name }} {{ $order->last_name }}</p>
                <p>{{ $order->address }}</p>
                @if($order->address_2)
                    <p>{{ $order->address_2 }}</p>
                @endif
                <p>{{ $order->city }}, {{ $order->state }} {{ $order->postal_code }}</p>
                <p>{{ $order->country }}</p>
                <p class="shipping-contact">
                    <i class="fa fa-envelope"></i> {{ $order->email }}<br>
                    <i class="fa fa-phone"></i> {{ $order->phone }}
                </p>
            </div>

            <div class="order-status-card">
                <h3><i class="fa fa-info-circle"></i> What's Next?</h3>
                <div class="status-timeline">
                    <div class="status-step active">
                        <div class="step-icon"><i class="fa fa-check"></i></div>
                        <div class="step-info">
                            <strong>Order Confirmed</strong>
                            <span>We've received your order</span>
                        </div>
                    </div>
                    <div class="status-step">
                        <div class="step-icon"><i class="fa fa-cog"></i></div>
                        <div class="step-info">
                            <strong>Processing</strong>
                            <span>We're preparing your items</span>
                        </div>
                    </div>
                    <div class="status-step">
                        <div class="step-icon"><i class="fa fa-truck"></i></div>
                        <div class="step-info">
                            <strong>Shipped</strong>
                            <span>On the way to you</span>
                        </div>
                    </div>
                    <div class="status-step">
                        <div class="step-icon"><i class="fa fa-home"></i></div>
                        <div class="step-info">
                            <strong>Delivered</strong>
                            <span>Enjoy your purchase!</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="thankyou-actions">
                <a href="{{ route('allproducts') }}" class="btn-continue-shopping">
                    <i class="fa fa-shopping-bag"></i> Continue Shopping
                </a>
                <a href="{{ route('index') }}" class="btn-home">
                    <i class="fa fa-home"></i> Back to Home
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

