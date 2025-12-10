@extends('layouts.app')

@section('content')
@include('layouts.header')

<section class="checkout_section layout_padding">
    <div class="container">
        <div class="heading_container heading_center">
            <h2>Checkout</h2>
        </div>

        @if($cartItems->count() > 0)
        <div class="checkout-container">
            <!-- Billing & Shipping Form -->
            <div class="checkout-form">
                <form action="{{ route('placeorder') }}" method="POST">
                    @csrf
                    
                    <div class="form-section">
                        <h3><i class="fa fa-user"></i> Contact Information</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone" placeholder="+1 (555) 000-0000" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3><i class="fa fa-truck"></i> Shipping Address</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" id="first_name" name="first_name" placeholder="John" required>
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" id="last_name" name="last_name" placeholder="Doe" required>
                            </div>
                        </div>
                        <div class="form-group full-width">
                            <label for="address">Street Address</label>
                            <input type="text" id="address" name="address" placeholder="123 Main Street" required>
                        </div>
                        <div class="form-group full-width">
                            <label for="address_2">Apartment, suite, etc. (optional)</label>
                            <input type="text" id="address_2" name="address_2" placeholder="Apt 4B">
                        </div>
                        <div class="form-row three-col">
                            <div class="form-group">
                                <label for="city">City</label>
                                <input type="text" id="city" name="city" placeholder="New York" required>
                            </div>
                            <div class="form-group">
                                <label for="state">State / Province</label>
                                <input type="text" id="state" name="state" placeholder="NY" required>
                            </div>
                            <div class="form-group">
                                <label for="postal_code">Zip/ Postal Code</label>
                                <input type="text" id="postal_code" name="postal_code" placeholder="10001" required>
                            </div>
                        </div>
                        <div class="form-group full-width">
                            <label for="country">Country</label>
                            <select id="country" name="country" required>
                                <option value="" disabled selected>Select a country</option>
                                <option value="US">United States</option>
                                <option value="CA">Canada</option>
                                <option value="UK">United Kingdom</option>
                                <option value="FR">France</option>
                                <option value="DE">Germany</option>
                                <option value="AU">Australia</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn-place-order">
                        <i class="fa fa-lock"></i> Go to Payment
                    </button>
                </form>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="order-summary">
                <h3>Order Summary</h3>
                
                <div class="order-items">
                    @php $total = 0; $totalItems = 0; @endphp
                    @foreach ($cartItems as $item)
                        @php 
                            $subtotal = $item->product->price * $item->quantity;
                            $total += $subtotal;
                            $totalItems += $item->quantity;
                        @endphp
                        <div class="order-item">
                            <div class="order-item-image">
                                @if($item->product->image)
                                    <img src="{{ asset('products/' . $item->product->image) }}" alt="{{ $item->product->name }}">
                                @else
                                    <img src="{{ asset('front/images/p1.png') }}" alt="{{ $item->product->name }}">
                                @endif
                                <span class="item-quantity">{{ $item->quantity }}</span>
                            </div>
                            <div class="order-item-details">
                                <h5>{{ $item->product->name }}</h5>
                                @if($item->product->category)
                                    <p>{{ $item->product->category->category }}</p>
                                @endif
                            </div>
                            <div class="order-item-price">
                                ${{ number_format($subtotal, 2) }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="order-totals">
                    <div class="total-row">
                        <span>Subtotal ({{ $totalItems }} items)</span>
                        <span>${{ number_format($total, 2) }}</span>
                    </div>
                    <div class="total-row">
                        <span>Shipping</span>
                        <span class="free">FREE</span>
                    </div>
                    <div class="total-row">
                        <span>Tax</span>
                        <span>${{ number_format($total * 0.08, 2) }}</span>
                    </div>
                    <hr>
                    <div class="total-row grand-total">
                        <span>Total</span>
                        <span>${{ number_format($total * 1.08, 2) }}</span>
                    </div>
                </div>

                <a href="{{ route('viewcart') }}" class="back-to-cart">
                    <i class="fa fa-arrow-left"></i> Back to Cart
                </a>
            </div>
        </div>
        @else
        <div class="empty-checkout">
            <i class="fa fa-shopping-cart"></i>
            <h3>Your cart is empty</h3>
            <p>Add some items to your cart before checking out.</p>
            <a href="{{ route('allproducts') }}" class="btn-shop-now">
                Browse Products
            </a>
        </div>
        @endif
    </div>
</section>

@endsection

