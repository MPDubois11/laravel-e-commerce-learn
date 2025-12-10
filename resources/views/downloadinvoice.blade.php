<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 14px;
            color: #333;
            line-height: 1.6;
            background: #fff;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px;
        }
        
        /* Header */
        .invoice-header {
            display: table;
            width: 100%;
            margin-bottom: 40px;
            border-bottom: 3px solid #f7444e;
            padding-bottom: 30px;
        }
        
        .logo-section {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        
        .logo {
            font-size: 32px;
            font-weight: bold;
            color: #f7444e;
            letter-spacing: -1px;
        }
        
        .logo-tagline {
            font-size: 12px;
            color: #888;
            margin-top: 5px;
        }
        
        .invoice-info-section {
            display: table-cell;
            width: 50%;
            text-align: right;
            vertical-align: top;
        }
        
        .invoice-title {
            font-size: 28px;
            font-weight: 300;
            color: #333;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .invoice-number {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }
        
        .invoice-date {
            font-size: 13px;
            color: #888;
            margin-top: 3px;
        }
        
        /* Addresses */
        .addresses {
            display: table;
            width: 100%;
            margin-bottom: 40px;
        }
        
        .address-block {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        
        .address-block h3 {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #999;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .address-block p {
            font-size: 13px;
            color: #555;
            margin-bottom: 3px;
        }
        
        .address-block .name {
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }
        
        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .items-table thead {
            background: #f8f9fa;
        }
        
        .items-table th {
            padding: 12px 15px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #666;
            font-weight: 600;
            border-bottom: 2px solid #e9ecef;
        }
        
        .items-table th.text-right {
            text-align: right;
        }
        
        .items-table th.text-center {
            text-align: center;
        }
        
        .items-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
            color: #444;
        }
        
        .items-table td.text-right {
            text-align: right;
        }
        
        .items-table td.text-center {
            text-align: center;
        }
        
        .items-table .product-name {
            font-weight: 500;
            color: #333;
        }
        
        .items-table tbody tr:last-child td {
            border-bottom: 2px solid #e9ecef;
        }
        
        /* Totals */
        .totals-section {
            display: table;
            width: 100%;
            margin-bottom: 40px;
        }
        
        .totals-spacer {
            display: table-cell;
            width: 60%;
        }
        
        .totals-table {
            display: table-cell;
            width: 40%;
        }
        
        .total-row {
            display: table;
            width: 100%;
            padding: 8px 0;
        }
        
        .total-label {
            display: table-cell;
            text-align: left;
            font-size: 13px;
            color: #666;
        }
        
        .total-value {
            display: table-cell;
            text-align: right;
            font-size: 13px;
            color: #333;
        }
        
        .total-row.grand-total {
            border-top: 2px solid #333;
            margin-top: 10px;
            padding-top: 15px;
        }
        
        .total-row.grand-total .total-label,
        .total-row.grand-total .total-value {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        
        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-processing {
            background: #cce5ff;
            color: #004085;
        }
        
        .status-shipped {
            background: #d4edda;
            color: #155724;
        }
        
        .status-delivered {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }
        
        /* Footer */
        .invoice-footer {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid #eee;
        }
        
        .footer-message {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .footer-contact {
            font-size: 12px;
            color: #999;
        }
        
        /* Payment Info */
        .payment-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .payment-info h3 {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .payment-info p {
            font-size: 13px;
            color: #555;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="logo-section">
                <div class="logo">Giftos</div>
                <div class="logo-tagline">Premium Gift Shop</div>
            </div>
            <div class="invoice-info-section">
                <div class="invoice-title">Invoice</div>
                <div class="invoice-number">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
                <div class="invoice-date">{{ $order->created_at->format('F d, Y') }}</div>
                <div style="margin-top: 10px;">
                    <span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                </div>
            </div>
        </div>
        
        <!-- Addresses -->
        <div class="addresses">
            <div class="address-block">
                <h3>From</h3>
                <p class="name">Giftos Inc.</p>
                <p>123 Commerce Street</p>
                <p>Business District</p>
                <p>New York, NY 10001</p>
                <p>United States</p>
                <p style="margin-top: 10px;">contact@giftos.com</p>
            </div>
            <div class="address-block">
                <h3>Bill To</h3>
                <p class="name">{{ $order->first_name }} {{ $order->last_name }}</p>
                <p>{{ $order->address }}</p>
                @if($order->address_2)
                <p>{{ $order->address_2 }}</p>
                @endif
                <p>{{ $order->city }}, {{ $order->state }} {{ $order->postal_code }}</p>
                <p>{{ $order->country }}</p>
                <p style="margin-top: 10px;">{{ $order->email }}</p>
                <p>{{ $order->phone }}</p>
            </div>
        </div>
        
        <!-- Items -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 50%;">Description</th>
                    <th class="text-center" style="width: 15%;">Qty</th>
                    <th class="text-right" style="width: 17%;">Unit Price</th>
                    <th class="text-right" style="width: 18%;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <span class="product-name">{{ $item->product->name ?? 'Product' }}</span>
                    </td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">${{ number_format($item->price, 2) }}</td>
                    <td class="text-right">${{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Totals -->
        <div class="totals-section">
            <div class="totals-spacer"></div>
            <div class="totals-table">
                <div class="total-row">
                    <span class="total-label">Subtotal</span>
                    <span class="total-value">${{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="total-row">
                    <span class="total-label">Shipping</span>
                    <span class="total-value" style="color: #28a745;">FREE</span>
                </div>
                <div class="total-row">
                    <span class="total-label">Tax (8%)</span>
                    <span class="total-value">${{ number_format($order->tax, 2) }}</span>
                </div>
                <div class="total-row grand-total">
                    <span class="total-label">Total</span>
                    <span class="total-value">${{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>
        
        <!-- Payment Info -->
        <div class="payment-info">
            <h3>Payment Information</h3>
            <p><strong>Payment Method:</strong> Credit Card</p>
            <p><strong>Payment Status:</strong> Paid</p>
            <p><strong>Transaction Date:</strong> {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
        </div>
        
        <!-- Footer -->
        <div class="invoice-footer">
            <p class="footer-message">Thank you for your business!</p>
            <p class="footer-contact">
                Questions? Contact us at contact@giftos.com or call +1 (555) 123-4567
            </p>
        </div>
    </div>
</body>
</html>
