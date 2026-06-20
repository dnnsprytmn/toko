<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $order->order_number }}</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- LOGO --}}
    <link rel="icon" type="image/png" href="{{ url('assets/logo/1.png') }}">
    <link rel="apple-touch-icon" href="{{ url('assets/logo/1.png') }}">
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: #1f2122;
            padding: 30px;
            color: #fff;
        }
        .invoice-wrapper {
            max-width: 900px;
            margin: 0 auto;
            background: #27292a;
            border-radius: 23px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            border: 1px solid #3a3c3d;
        }
        
        /* ===== HEADER ===== */
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #3a3c3d;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }
        .invoice-header .logo h2 {
            color: #fff;
            font-weight: 700;
            font-size: 28px;
            margin: 0;
        }
        .invoice-header .logo h2 em {
            color: #ec6090;
            font-style: normal;
        }
        .invoice-header .logo p {
            color: #666;
            font-size: 14px;
            margin: 0;
        }
        .invoice-header .invoice-title {
            text-align: right;
        }
        .invoice-header .invoice-title h1 {
            color: #ec6090;
            font-size: 32px;
            font-weight: 700;
            margin: 0;
        }
        .invoice-header .invoice-title p {
            color: #666;
            font-size: 14px;
            margin: 0;
        }
        .invoice-header .invoice-title p strong {
            color: #fff;
        }

        /* ===== INFO ===== */
        .invoice-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
            background: #1f2122;
            padding: 20px;
            border-radius: 12px;
            border: 1px solid #3a3c3d;
        }
        .invoice-info .info-box h5 {
            color: #ec6090;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .invoice-info .info-box p {
            color: #666;
            font-size: 14px;
            margin-bottom: 3px;
            line-height: 1.6;
        }
        .invoice-info .info-box p strong {
            color: #fff;
        }

        /* ===== ITEMS TABLE ===== */
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .invoice-table thead th {
            background: #1f2122;
            color: #ec6090;
            padding: 12px 15px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #3a3c3d;
        }
        .invoice-table thead th:last-child {
            text-align: right;
        }
        .invoice-table tbody td {
            padding: 12px 15px;
            color: #fff;
            font-size: 14px;
            border-bottom: 1px solid #2a2c2d;
        }
        .invoice-table tbody td:last-child {
            text-align: right;
            font-weight: 600;
            color: #ec6090;
        }
        .invoice-table tbody tr:last-child td {
            border-bottom: none;
        }
        .invoice-table tbody tr:hover {
            background: #1f2122;
        }

        /* ===== TOTALS ===== */
        .invoice-totals {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 30px;
        }
        .invoice-totals .totals-box {
            width: 100%;
            max-width: 350px;
            background: #1f2122;
            padding: 20px;
            border-radius: 12px;
            border: 1px solid #3a3c3d;
        }
        .invoice-totals .totals-box .total-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            color: #666;
            font-size: 14px;
        }
        .invoice-totals .totals-box .total-row .label {
            color: #666;
        }
        .invoice-totals .totals-box .total-row .value {
            color: #fff;
        }
        .invoice-totals .totals-box .total-row.grand-total {
            border-top: 2px solid #ec6090;
            margin-top: 8px;
            padding-top: 12px;
        }
        .invoice-totals .totals-box .total-row.grand-total .label {
            color: #fff;
            font-weight: 600;
            font-size: 16px;
        }
        .invoice-totals .totals-box .total-row.grand-total .value {
            color: #ec6090;
            font-size: 20px;
            font-weight: 700;
        }

        /* ===== FOOTER ===== */
        .invoice-footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #3a3c3d;
            color: #666;
            font-size: 13px;
        }
        .invoice-footer p {
            margin-bottom: 5px;
        }
        .invoice-footer p strong {
            color: #fff;
        }
        .invoice-footer .social-icons {
            margin-top: 10px;
            display: flex;
            justify-content: center;
            gap: 12px;
        }
        .invoice-footer .social-icons a {
            color: #666;
            font-size: 18px;
            transition: color 0.3s;
        }
        .invoice-footer .social-icons a:hover {
            color: #ec6090;
        }

        /* ===== PRINT BUTTON ===== */
        .print-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 25px;
            background: linear-gradient(135deg, #e75e8d, #d44a7a);
            color: #fff;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }
        .print-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(231, 94, 141, 0.3);
            color: #fff;
        }
        .print-btn i {
            font-size: 16px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            body {
                padding: 15px;
            }
            .invoice-wrapper {
                padding: 20px;
            }
            .invoice-header {
                flex-direction: column;
                text-align: center;
            }
            .invoice-header .invoice-title {
                text-align: center;
            }
            .invoice-info {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            .invoice-totals {
                justify-content: center;
            }
            .invoice-totals .totals-box {
                max-width: 100%;
            }
            .invoice-table thead th,
            .invoice-table tbody td {
                padding: 8px 10px;
                font-size: 12px;
            }
            .invoice-header .logo h2 {
                font-size: 22px;
            }
            .invoice-header .invoice-title h1 {
                font-size: 24px;
            }
        }
        @media (max-width: 576px) {
            .invoice-wrapper {
                padding: 15px;
            }
            .invoice-table thead th,
            .invoice-table tbody td {
                padding: 6px 8px;
                font-size: 11px;
            }
            .invoice-table tbody td:last-child {
                font-size: 12px;
            }
            .invoice-totals .totals-box .total-row {
                font-size: 13px;
            }
            .invoice-totals .totals-box .total-row.grand-total .value {
                font-size: 17px;
            }
        }

        /* ===== PRINT STYLES ===== */
        @media print {
            body {
                background: #fff !important;
                padding: 20px !important;
                color: #333 !important;
            }
            .invoice-wrapper {
                background: #fff !important;
                box-shadow: none !important;
                border: 1px solid #ddd !important;
                padding: 30px !important;
            }
            .print-btn {
                display: none !important;
            }
            .invoice-header .logo h2 {
                color: #333 !important;
            }
            .invoice-header .logo h2 em {
                color: #e75e8d !important;
            }
            .invoice-header .logo p {
                color: #666 !important;
            }
            .invoice-header .invoice-title h1 {
                color: #e75e8d !important;
            }
            .invoice-header .invoice-title p {
                color: #666 !important;
            }
            .invoice-header .invoice-title p strong {
                color: #333 !important;
            }
            .invoice-info {
                background: #f8f9fa !important;
                border-color: #ddd !important;
            }
            .invoice-info .info-box p {
                color: #666 !important;
            }
            .invoice-info .info-box p strong {
                color: #333 !important;
            }
            .invoice-table thead th {
                background: #f8f9fa !important;
                color: #e75e8d !important;
                border-color: #ddd !important;
            }
            .invoice-table tbody td {
                color: #333 !important;
                border-color: #eee !important;
            }
            .invoice-table tbody td:last-child {
                color: #e75e8d !important;
            }
            .invoice-totals .totals-box {
                background: #f8f9fa !important;
                border-color: #ddd !important;
            }
            .invoice-totals .totals-box .total-row .value {
                color: #333 !important;
            }
            .invoice-totals .totals-box .total-row.grand-total .label {
                color: #333 !important;
            }
            .invoice-totals .totals-box .total-row.grand-total .value {
                color: #e75e8d !important;
            }
            .invoice-footer {
                border-color: #ddd !important;
                color: #666 !important;
            }
            .invoice-footer p strong {
                color: #333 !important;
            }
            .invoice-footer .social-icons a {
                color: #666 !important;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- ===== PRINT BUTTON ===== -->
    <div style="text-align: center; margin-bottom: 20px;" class="no-print">
        <button onclick="window.print()" class="print-btn">
            <i class="fas fa-print"></i> Print Invoice
        </button>
        <a href="{{ route('checkout.success', $order->id) }}" class="print-btn" style="background: transparent; border: 1px solid #3a3c3d; color: #fff; margin-left: 10px;">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <!-- ===== INVOICE ===== -->
    <div class="invoice-wrapper">

        <!-- Header -->
        <div class="invoice-header">
            <div class="logo">
                <h2><em>AzazeL</em> Warehouse</h2>
                <p>Your Trusted Online Store</p>
            </div>
            <div class="invoice-title">
                <h1>INVOICE</h1>
                <p><strong>#{{ $order->order_number }}</strong></p>
            </div>
        </div>

        <!-- Info -->
        <div class="invoice-info">
            <div class="info-box">
                <h5><i class="fas fa-user me-2"></i>Bill To</h5>
                <p><strong>{{ $order->customer_name ?? 'Guest' }}</strong></p>
                <p>{{ $order->customer_email }}</p>
                <p>{{ $order->customer_phone ?? '-' }}</p>
                @if($order->shipping_address)
                    <p>{{ $order->shipping_address }}</p>
                    <p>{{ $order->shipping_city ?? '' }} {{ $order->shipping_postal_code ?? '' }}</p>
                @endif
            </div>
            <div class="info-box">
                <h5><i class="fas fa-info-circle me-2"></i>Order Details</h5>
                <p><strong>Order Date:</strong> {{ $order->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY HH:mm') }}</p>
                <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method ?? '-')) }}</p>
                <p><strong>Order Status:</strong> 
                    <span class="badge bg-{{ $order->status_badge }}" style="padding: 4px 12px; border-radius: 20px;">
                        {{ ucfirst($order->status) }}
                    </span>
                </p>
                <p><strong>Payment Status:</strong> 
                    <span class="badge bg-{{ $order->payment_status_badge }}" style="padding: 4px 12px; border-radius: 20px;">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </p>
            </div>
        </div>

        <!-- Items Table -->
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th style="text-align: center;">Quantity</th>
                    <th style="text-align: right;">Price</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @if($order->items)
                    @php $no = 1; @endphp
                    @foreach(json_decode($order->items, true) as $item)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $item['name'] ?? 'Product' }}</td>
                        <td style="text-align: center;">{{ $item['quantity'] ?? 1 }}</td>
                        <td style="text-align: right;">Rp. {{ number_format($item['price'] ?? 0, 0, ',', '.') }}</td>
                        <td style="text-align: right;">Rp. {{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" style="text-align: center;">No items found</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <!-- Totals -->
        <div class="invoice-totals">
            <div class="totals-box">
                <div class="total-row">
                    <span class="label">Subtotal</span>
                    <span class="value">Rp. {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                @if($order->shipping_cost > 0)
                <div class="total-row">
                    <span class="label">Shipping</span>
                    <span class="value">Rp. {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="total-row grand-total">
                    <span class="label">Total</span>
                    <span class="value">Rp. {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Notes -->
        @if($order->notes)
        <div style="background: #1f2122; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #3a3c3d;">
            <p style="color: #666; font-size: 14px; margin: 0;">
                <strong style="color: #ec6090;">Notes:</strong> {{ $order->notes }}
            </p>
        </div>
        @endif

        <!-- Footer -->
        <div class="invoice-footer">
            <p>Thank you for your order!</p>
            <p style="font-size: 12px; color: #666;">Regards, <strong>AzazeL Warehouse.</strong></p>
            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
                <a href="#"><i class="fab fa-tiktok"></i></a>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>