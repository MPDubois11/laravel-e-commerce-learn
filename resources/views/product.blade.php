
@extends('layouts.app')

@section('content')
  <!-- header section starts -->
  @include('layouts.header')

  <!-- product detail section -->
  <section class="product_section">
    <div class="container">
      <a href="{{ route('index') }}" class="back-link">
        <i class="fa fa-arrow-left"></i> Back to Shop
      </a>
      
      <div class="product-detail">
        <div class="product-image">
          @if($product->image)
            <img src="{{ asset('products/' . $product->image) }}" alt="{{ $product->name }}">
          @else
            <img src="{{ asset('front/images/p1.png') }}" alt="{{ $product->name }}">
          @endif
        </div>
        
        <div class="product-info">
          @if($product->category)
            <p class="product-category">{{ $product->category->category }}</p>
          @endif
          
          <h1>{{ $product->name }}</h1>
          
          <p class="product-price">${{ number_format($product->price, 2) }}</p>
          
          @if($product->quantity > 0)
            <p class="product-stock">
              <i class="fa fa-check-circle"></i> In Stock ({{ $product->quantity }} available)
            </p>
          @else
            <p class="product-stock out-of-stock">
              <i class="fa fa-times-circle"></i> Out of Stock
            </p>
          @endif
          
          <p class="product-description">{{ $product->description }}</p>
          
          @if (session('cart_message'))
              <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                  {{ session('cart_message') }}
              </div>
          @endif

          @if($availableStock > 0)
          <form class="add-to-cart-form" action="{{ route('addtocart', $product->id) }}" method="POST">
            @csrf
            <input type="number" name="quantity" value="1" min="1" max="{{ $availableStock }}" class="quantity-input">
            <button type="submit" class="btn-add-cart">
              <i class="fa fa-shopping-cart"></i> Add to Cart
            </button>
            @if($availableStock < $product->quantity)
              <p class="stock-notice"><i class="fa fa-info-circle"></i> You have {{ $product->quantity - $availableStock }} in your cart</p>
            @endif
          </form>
          @elseif($product->quantity > 0)
          <div class="all-in-cart-notice">
            <i class="fa fa-check-circle"></i> All available stock is in your cart
          </div>
          @endif
        </div>
      </div>
    </div>
  </section>
  <!-- product detail section ends -->
  @endsection
