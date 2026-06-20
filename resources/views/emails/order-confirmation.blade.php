<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .header .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .header h1 {
            font-size: 28px;
            font-weight: 700;
            margin: 0;
        }
        .header p {
            margin: 5px 0 0;
            opacity: 0.9;
            font-size: 16px;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 15px;
        }
        .greeting strong {
            color: #333;
        }
        .order-summary {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }
        .order-summary .row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
        }
        .order-summary .row:not(:last-child) {
            border-bottom: 1px solid #e9ecef;
        }
        .order-summary .label {
            color: #6c757d;
        }
        .order-summary .value {
            font-weight: 600;
        }
        .order-summary .value.status {
            text-transform: capitalize;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }
        .status-badge.pending { background: #ffc107; color: #000; }
        .status-badge.processing { background: #17a2b8; color: #fff; }
        .status-badge.completed { background: #28a745; color: #fff; }
        .status-badge.shipped { background: #0d6efd; color: #fff; }
        .status-badge.cancelled { background: #dc3545; color: #fff; }
        .status-badge.unpaid { background: #ffc107; color: #000; }
        .status-badge.paid { background: #28a745; color: #fff; }
        .status-badge.refunded { background: #dc3545; color: #fff; }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0 20px;
            font-size: 14px;
        }
        .items-table th {
            background: #f8f9fa;
            padding: 10px 12px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }
        .items-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #dee2e6;
        }
        .items-table .text-center {
            text-align: center;
        }
        .items-table .text-right {
            text-align: right;
        }
        .items-table .total-row td {
            font-weight: 700;
            border-top: 2px solid #667eea;
            padding-top: 15px;
            font-size: 16px;
        }
        .items-table .total-row .grand-total {
            color: #667eea;
            font-size: 20px;
        }
        
        .shipping-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px 20px;
            margin: 15px 0 20px;
        }
        .shipping-info h4 {
            margin-bottom: 8px;
            color: #333;
        }
        .shipping-info p {
            margin: 3px 0;
            color: #555;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 35px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #5a67d8;
        }
        .btn-secondary {
            background: #6c757d;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .actions {
            text-align: center;
            margin: 25px 0 10px;
        }
        .actions .btn {
            margin: 5px;
        }
        
        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
            border-top: 1px solid #dee2e6;
        }
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
        .footer .social {
            margin-top: 10px;
        }
        .footer .social a {
            margin: 0 8px;
            font-size: 20px;
        }
        
        @media (max-width: 480px) {
            .container {
                margin: 10px;
                border-radius: 8px;
            }
            .header {
                padding: 25px 20px;
            }
            .header h1 {
                font-size: 22px;
            }
            .content {
                padding: 20px 15px;
            }
            .items-table {
                font-size: 13px;
            }
            .items-table th,
            .items-table td {
                padding: 8px;
            }
            .actions .btn {
                display: block;
                margin: 8px 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="icon">🎉</div>
            <h1>Order Confirmed!</h1>
            <p>Thank you for your order, {{ $order->customer_name ?? 'Customer' }}</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Hi <strong>{{ $order->customer_name ?? 'Customer' }}</strong>,
            </div>
            
            <p>Thank you for shopping with <strong>AzazeL Warehouse</strong>. Your order has been successfully placed and is being processed.</p>

            <!-- Order Summary -->
            <div class="order-summary">
                <div class="row">
                    <span class="label">Order Number: </span>
                    <span class="value">#{{ $order->order_number }}</span>
                </div>
                <div class="row">
                    <span class="label">Order Date: </span>
                    <span class="value">{{ $order->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY HH:mm') }}</span>
                </div>
                <div class="row">
                    <span class="label">Order Status: </span>
                    <span class="value status">
                        <span class="status-badge {{ $order->status }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </span>
                </div>
                <div class="row">
                    <span class="label">Payment Status: </span>
                    <span class="value status">
                        <span class="status-badge {{ $order->payment_status }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </span>
                </div>
                <div class="row">
                    <span class="label">Payment Method: </span>
                    <span class="value">{{ ucfirst(str_replace('_', ' ', $order->payment_method ?? '-')) }}</span>
                </div>
            </div>

            <!-- Order Items -->
            <h3 style="margin-bottom: 10px;">🛒 Order Items</h3>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th class="text-center">Qty</th>
                        <th class="text-right">Price</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @if($order->items)
                        @php $items = json_decode($order->items, true); @endphp
                        @foreach($items as $item)
                        <tr>
                            <td>{{ $item['name'] ?? 'Product' }}</td>
                            <td class="text-center">{{ $item['quantity'] ?? 1 }}</td>
                            <td class="text-right">Rp. {{ number_format($item['price'] ?? 0, 0, ',', '.') }}</td>
                            <td class="text-right">Rp. {{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    @endif
                    <tr class="total-row">
                        <td colspan="3" class="text-right">Subtotal</td>
                        <td class="text-right">Rp. {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @if($order->shipping_cost > 0)
                    <tr>
                        <td colspan="3" class="text-right">Shipping</td>
                        <td class="text-right">Rp. {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                    <tr class="total-row">
                        <td colspan="3" class="text-right">Total</td>
                        <td class="text-right grand-total">Rp. {{ number_format($order->total, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Shipping Address -->
            @if($order->shipping_address)
            <div class="shipping-info">
                <h4>📍 Shipping Address</h4>
                <p><strong>{{ $order->customer_name }}</strong></p>
                <p>{{ $order->shipping_address }}</p>
                @if($order->shipping_city)
                <p>{{ $order->shipping_city }}, {{ $order->shipping_postal_code ?? '' }}</p>
                @endif
                @if($order->customer_phone)
                <p>📞 {{ $order->customer_phone }}</p>
                @endif
            </div>
            @endif

            <!-- Notes -->
            @if($order->notes)
            <div style="background: #fff3cd; padding: 12px 16px; border-radius: 8px; margin: 15px 0;">
                <strong>📝 Notes:</strong> {{ $order->notes }}
            </div>
            @endif

            <!-- Actions -->
            <div class="actions">
                <a href="{{ route('invoice', $order->id) }}" class="btn" target="_blank">
                    📄 View Invoice
                </a>
                <a href="{{ route('home') }}" class="btn btn-secondary">
                    🏠 Continue Shopping
                </a>
            </div>

            <p style="text-align: center; color: #6c757d; font-size: 14px; margin-top: 20px;">
                We will send you a confirmation email shortly.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>
                <strong>AzazeL Warehouse</strong><br>
                Your trusted online store
            </p>
            <p>
                <a href="{{ route('home') }}">Visit our website</a>
            </p>
            <div class="social">
                <a href="#">📱</a>
                <a href="#">🐦</a>
                <a href="#">📷</a>
                <a href="#">💼</a>
            </div>
            <p style="font-size: 12px; color: #adb5bd; margin-top: 10px;">
                This is an automated email. Please do not reply to this message.<br>
                &copy; {{ date('Y') }} AzazeL Warehouse. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>