@extends('admin.maindesign')

@section('content')
<div class="container-fluid">
    @if (session('status_updated'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
            {{ session('status_updated') }}
        </div>
    @endif

    <h2 class="page-title">Orders</h2>
    
    <table class="products-table orders-table">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Email</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
            <tr>
                <td><strong>#{{ $order->id }}</strong></td>
                <td>{{ $order->first_name }} {{ $order->last_name }}</td>
                <td>{{ $order->email }}</td>
                <td class="order-total">${{ number_format($order->total, 2) }}</td>
                <td>
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
                </td>
                <td>{{ $order->created_at->format('M d, Y') }}</td>
                <td>
                    <div class="actions">
                        <a href="{{ route('admin.vieworder', $order->id) }}" title="View Order">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.64 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.64 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="empty-table">No orders yet</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

