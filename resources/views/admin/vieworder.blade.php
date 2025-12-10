@extends('admin.maindesign')

@section('content')
<div class="container-fluid">
    <a href="{{ route('admin.vieworders') }}" class="back-link" style="margin-bottom: 20px; display: inline-block;">
        <i class="fa fa-arrow-left"></i> Back to Orders
    </a>

    @if (session('status_updated'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
            {{ session('status_updated') }}
        </div>
    @endif

    <div class="order-header">
        <div>
            <h2>Order #{{ $order->id }}</h2>
            <p class="order-meta">
                Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}
            </p>
        </div>
        <form action="{{ route('admin.updateorderstatus', $order->id) }}" method="POST" class="status-form">
            @csrf
            @method('PATCH')
            <select name="status" class="status-select status-{{ $order->status }}" onchange="this.form.submit()">
                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </form>
    </div>

    <div class="order-cards">
        <!-- Customer Info -->
        <div class="order-card">
            <h4><i class="fa fa-user"></i> Customer</h4>
            <p><strong>{{ $order->first_name }} {{ $order->last_name }}</strong></p>
            <p>{{ $order->email }}</p>
            <p>{{ $order->phone }}</p>
            @if($order->user)
                <p class="label">User ID: {{ $order->user->id }}</p>
            @endif
        </div>

        <!-- Shipping Address -->
        <div class="order-card">
            <h4><i class="fa fa-truck"></i> Shipping Address</h4>
            <p>{{ $order->address }}</p>
            @if($order->address_2)
                <p>{{ $order->address_2 }}</p>
            @endif
            <p>{{ $order->city }}, {{ $order->state }} {{ $order->postal_code }}</p>
            <p>{{ $order->country }}</p>
        </div>

        <!-- Order Items -->
        <div class="order-card order-items-card">
            <h4><i class="fa fa-box"></i> Order Items</h4>
            
            @foreach($order->items as $item)
            <div class="order-item-row">
                <div class="order-item-info">
                    @if($item->product && $item->product->image)
                        <img src="{{ asset('products/' . $item->product->image) }}" alt="" class="order-item-image">
                    @else
                        <img src="{{ asset('front/images/p1.png') }}" alt="" class="order-item-image">
                    @endif
                    <div>
                        <p class="order-item-name">{{ $item->product->name ?? 'Product Deleted' }}</p>
                        <p class="order-item-qty">Qty: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}</p>
                    </div>
                </div>
                <span class="order-item-price">${{ number_format($item->price * $item->quantity, 2) }}</span>
            </div>
            @endforeach

            <div class="order-summary-totals">
                <div class="summary-line">
                    <span>Subtotal</span>
                    <span>${{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="summary-line">
                    <span>Tax</span>
                    <span>${{ number_format($order->tax, 2) }}</span>
                </div>
                <div class="summary-line">
                    <span>Shipping</span>
                    <span style="color: #22c55e;">FREE</span>
                </div>
                <div class="summary-line total">
                    <span>Total</span>
                    <span>${{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

