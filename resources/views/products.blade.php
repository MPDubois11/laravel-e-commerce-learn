<!DOCTYPE html>
<html>

<head>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Site Metas -->
  <meta name="keywords" content="" />
  <meta name="description" content="Browse all our products" />
  <meta name="author" content="" />
  <link rel="shortcut icon" href="{{ asset('front/images/favicon.png') }}" type="image/x-icon">

  <title>All Products - Giftos</title>

  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="{{ asset('front/css/bootstrap.css') }}" />
  <!-- Custom styles for this template -->
  <link href="{{ asset('front/css/style.css') }}" rel="stylesheet" />
  <!-- responsive style -->
  <link href="{{ asset('front/css/responsive.css') }}" rel="stylesheet" />

  <style>
    .products_section {
      padding: 60px 0;
    }
    .products_section .heading_container {
      margin-bottom: 40px;
    }
    .products_section .heading_container h2 {
      font-weight: bold;
      color: #333;
    }
    .pagination-wrapper {
      margin-top: 40px;
      display: flex;
      justify-content: center;
    }
    .pagination-wrapper .pagination {
      margin: 0;
    }
    .pagination-wrapper .page-link {
      color: #f7444e;
      border-color: #ddd;
    }
    .pagination-wrapper .page-item.active .page-link {
      background-color: #f7444e;
      border-color: #f7444e;
      color: #fff;
    }
    .pagination-wrapper .page-link:hover {
      background-color: #f7444e;
      border-color: #f7444e;
      color: #fff;
    }
    .back-link {
      display: inline-block;
      margin-bottom: 20px;
      color: #f7444e;
      text-decoration: none;
    }
    .back-link:hover {
      text-decoration: underline;
    }
    .no-products {
      text-align: center;
      padding: 60px 20px;
      color: #666;
    }
    .no-products i {
      font-size: 4rem;
      color: #ddd;
      margin-bottom: 20px;
    }
  </style>
</head>

<body>
  <!-- header section starts -->
  <header class="header_section">
    <nav class="navbar navbar-expand-lg custom_nav-container ">
      <a class="navbar-brand" href="{{ route('index') }}">
        <span>Giftos</span>
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class=""></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="{{ route('index') }}">Home</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="{{ route('allproducts') }}">Shop <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Why Us</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Testimonial</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Contact Us</a>
          </li>
        </ul>
        <div class="user_option">
          @if (Auth::check())
          <a href="{{ route('dashboard') }}">
            <i class="fa fa-user" aria-hidden="true"></i>
            <span>Dashboard</span>
          </a>
          @else
          <a href="{{ route('login') }}">
            <i class="fa fa-user" aria-hidden="true"></i>
            <span>Login</span>
          </a>
          <a href="{{ route('register') }}">
            <i class="fa fa-user" aria-hidden="true"></i>
            <span>Sign Up</span>
          </a>
          @endif
          <a href="">
            <i class="fa fa-shopping-bag" aria-hidden="true"></i>
          </a>
        </div>
      </div>
    </nav>
  </header>
  <!-- header section ends -->

  <!-- products section -->
  <section class="products_section shop_section layout_padding">
    <div class="container">
      <a href="{{ route('index') }}" class="back-link">
        <i class="fa fa-arrow-left"></i> Back to Home
      </a>

      <div class="heading_container heading_center">
        <h2>All Products</h2>
        <p>Showing {{ $products->count() }} of {{ $products->total() }} products</p>
      </div>

      @if($products->count() > 0)
      <div class="row">
        @foreach ($products as $product)
        <div class="col-sm-6 col-md-4 col-lg-3">
          <div class="box">
            <a href="{{ route('product', $product->id) }}">
              <div class="img-box">
                @if($product->image)
                  <img src="{{ asset('products/' . $product->image) }}" alt="{{ $product->name }}">
                @else
                  <img src="{{ asset('front/images/p1.png') }}" alt="{{ $product->name }}">
                @endif
              </div>
              <div class="detail-box">
                <h6>{{ $product->name }}</h6>
                <h6>
                  Price
                  <span>${{ number_format($product->price, 2) }}</span>
                </h6>
              </div>
              @if($product->quantity <= 0)
              <div class="new" style="background-color: #dc3545;">
                <span>Sold Out</span>
              </div>
              @elseif($product->created_at >= now()->subDays(7))
              <div class="new">
                <span>New</span>
              </div>
              @endif
            </a>
          </div>
        </div>
        @endforeach
      </div>

      <div class="pagination-wrapper">
        {{ $products->links() }}
      </div>
      @else
      <div class="no-products">
        <i class="fa fa-shopping-bag"></i>
        <h3>No products found</h3>
        <p>Check back later for new products!</p>
      </div>
      @endif
    </div>
  </section>
  <!-- products section ends -->

  <!-- info section -->
  <section class="info_section layout_padding2-top">
    <div class="social_container">
      <div class="social_box">
        <a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>
        <a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a>
        <a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a>
        <a href="#"><i class="fa fa-youtube" aria-hidden="true"></i></a>
      </div>
    </div>
    <div class="info_container">
      <div class="container">
        <div class="row">
          <div class="col-md-6 col-lg-3">
            <h6>ABOUT US</h6>
            <p>Your one-stop shop for amazing gifts and products.</p>
          </div>
          <div class="col-md-6 col-lg-3">
            <h6>NEED HELP</h6>
            <p>Contact our support team for any questions or concerns.</p>
          </div>
          <div class="col-md-6 col-lg-3">
            <h6>CONTACT US</h6>
            <div class="info_link-box">
              <a href=""><i class="fa fa-map-marker" aria-hidden="true"></i><span> Gb road 123 london Uk </span></a>
              <a href=""><i class="fa fa-phone" aria-hidden="true"></i><span>+01 12345678901</span></a>
              <a href=""><i class="fa fa-envelope" aria-hidden="true"></i><span> demo@gmail.com</span></a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- footer section -->
    <footer class="footer_section">
      <div class="container">
        <p>&copy; <span id="displayYear"></span> All Rights Reserved By <a href="https://html.design/">Web Tech Knowledge</a></p>
      </div>
    </footer>
  </section>
  <!-- end info section -->

  <script src="{{ asset('front/js/jquery-3.4.1.min.js') }}"></script>
  <script src="{{ asset('front/js/bootstrap.js') }}"></script>
  <script src="{{ asset('front/js/custom.js') }}"></script>

</body>

</html>

