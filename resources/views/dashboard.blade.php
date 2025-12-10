<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($orders->count() > 0)
                <div class="space-y-6">
                    @foreach($orders as $order)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <!-- Order Header -->
                            <div class="flex flex-wrap justify-between items-start mb-4 gap-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                        Order #{{ $order->id }}
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}
                                    </p>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                        @elseif($order->status == 'shipped') bg-indigo-100 text-indigo-800
                                        @elseif($order->status == 'delivered') bg-green-100 text-green-800
                                        @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                    <span class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                        ${{ number_format($order->total, 2) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Order Items -->
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                <div class="space-y-3">
                                    @foreach($order->items as $item)
                                    <div class="flex items-center gap-4">
                                        <div class="flex-shrink-0 w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden">
                                            @if($item->product && $item->product->image)
                                                <img src="{{ asset('products/' . $item->product->image) }}" 
                                                     alt="{{ $item->product->name ?? 'Product' }}" 
                                                     class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                                {{ $item->product->name ?? 'Product no longer available' }}
                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                Qty: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}
                                            </p>
                                        </div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            ${{ number_format($item->price * $item->quantity, 2) }}
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="order-details-dashboard">
                                <!-- Shipping Info (collapsible) -->
                                <details class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-4">
                                    <summary class="cursor-pointer text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500">
                                        View shipping details
                                    </summary>
                                    <div class="mt-3 text-sm text-gray-600 dark:text-gray-400">
                                        <p class="font-medium text-gray-900 dark:text-gray-100">{{ $order->first_name }} {{ $order->last_name }}</p>
                                        <p>{{ $order->address }}</p>
                                        @if($order->address_2)<p>{{ $order->address_2 }}</p>@endif
                                        <p>{{ $order->city }}, {{ $order->state }} {{ $order->postal_code }}</p>
                                        <p>{{ $order->country }}</p>
                                        <p class="mt-2">{{ $order->email }} · {{ $order->phone }}</p>
                                    </div>
                                </details>

                                <div class="download-invoice-dashboard">
                                    <a href="{{ route('downloadinvoice', $order->id) }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 mt-4 pt-4" style="display: block;">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="download-invoice-dashboard-icon">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                          </svg>
                                           Download Invoice
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">No orders yet</h3>
                        <p class="mt-2 text-gray-500 dark:text-gray-400">Start shopping to see your orders here.</p>
                        <a href="{{ route('allproducts') }}" class="mt-6 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 transition">
                            Browse Products
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
