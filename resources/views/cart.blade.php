@extends('layouts.app')

@section('content')
@include('layouts.header')

<section class="cart_section layout_padding">
    <div class="container">
        <div class="heading_container heading_center">
            <h2>Your Shopping Cart</h2>
        </div>

        @if(session('cart_message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('cart_message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('cart_removed'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                {{ session('cart_removed') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if($cartItems->count() > 0)
        <div class="cart-container">
            <div class="cart-items">
                @php $total = 0; $totalItems = 0; @endphp
                @foreach ($cartItems as $item)
                    @php 
                        $subtotal = $item->product->price * $item->quantity;
                        $total += $subtotal;
                        $totalItems += $item->quantity;
                    @endphp
                    <div class="cart-item">
                        <div class="cart-item-image">
                            @if($item->product->image)
                                <img src="{{ asset('products/' . $item->product->image) }}" alt="{{ $item->product->name }}">
                            @else
                                <img src="{{ asset('front/images/p1.png') }}" alt="{{ $item->product->name }}">
                            @endif
                        </div>
                        <div class="cart-item-details">
                            <h4>
                                <a href="{{ route('product', $item->product->id) }}">
                                    {{ $item->product->name }}
                                </a>
                            </h4>
                            @if($item->product->category)
                                <p class="cart-item-category">{{ $item->product->category->category }}</p>
                            @endif
                            <div class="cart-item-quantity">
                                <span class="quantity-label">Qty:</span>
                                <span class="quantity-value">{{ $item->quantity }}</span>
                            </div>
                            <p class="cart-item-price">
                                ${{ number_format($item->product->price, 2) }}
                                @if($item->quantity > 1)
                                    <span class="item-subtotal">× {{ $item->quantity }} = ${{ number_format($subtotal, 2) }}</span>
                                @endif
                            </p>
                        </div>
                        <div class="cart-item-actions">
                            <a href="{{ route('removefromcart', $item->product->id) }}" class="btn-remove" onclick="return confirm('Remove all {{ $item->quantity }} of this item from cart?')">
                                <i class="fa fa-trash"></i> Remove
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="cart-summary">
                <h3>Order Summary</h3>
                <div class="summary-row">
                    <span>Items ({{ $totalItems }})</span>
                    <span>${{ number_format($total, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span>Shipping</span>
                    <span class="free-shipping">FREE</span>
                </div>
                <hr>
                <div class="summary-row total">
                    <span>Total</span>
                    <span>${{ number_format($total, 2) }}</span>
                </div>
                <a href="{{ route('checkout') }}" class="btn-checkout">
                    @if($cartItems->count() == 0)<i class="fa fa-lock"></i>@endif Proceed to Checkout
                </a>
                <a href="{{ route('allproducts') }}" class="btn-continue">
                    Continue Shopping
                </a>
            </div>
        </div>
        @else
        <div class="empty-cart">
            <i class="fa fa-shopping-cart"></i>
            <h3>Your cart is empty</h3>
            <p>Looks like you haven't added any items to your cart yet.</p>
            <a href="{{ route('allproducts') }}" class="btn-shop-now">
                Start Shopping
            </a>
        </div>
        @endif
    </div>
</section>

@endsection
