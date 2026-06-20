<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Invoice - {{ $order->order_number }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            padding: 30px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
        }
        .invoice-wrapper {
            max-width: 1000px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            padding: 40px;
            position: relative;
            overflow: hidden;
        }
        .invoice-wrapper::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #4e73df, #1cc88a, #36b9cc, #f6c23e, #e74a3b);
        }
        
        /* ===== HEADER ===== */
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding-bottom: 25px;
            border-bottom: 2px solid #f1f3f5;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }
        .invoice-header .logo-section h1 {
            font-size: 32px;
            font-weight: 800;
            margin: 0;
            background: linear-gradient(135deg, #4e73df, #224abe);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .invoice-header .logo-section p {
            color: #6c757d;
            font-size: 14px;
            margin: 0;
        }
        .invoice-header .invoice-title {
            text-align: right;
        }
        .invoice-header .invoice-title h2 {
            font-size: 28px;
            font-weight: 700;
            color: #2d3436;
            margin: 0;
        }
        .invoice-header .invoice-title .order-number {
            color: #4e73df;
            font-weight: 600;
            font-size: 16px;
        }
        .invoice-header .invoice-title .status-badge {
            padding: 6px 18px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            display: inline-block;
        }
        .invoice-header .invoice-title .status-badge.pending { background: #fff3cd; color: #856404; }
        .invoice-header .invoice-title .status-badge.processing { background: #cce5ff; color: #004085; }
        .invoice-header .invoice-title .status-badge.shipped { background: #d1ecf1; color: #0c5460; }
        .invoice-header .invoice-title .status-badge.completed { background: #d4edda; color: #155724; }
        .invoice-header .invoice-title .status-badge.cancelled { background: #f8d7da; color: #721c24; }

        /* ===== INFO ===== */
        .invoice-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-bottom: 30px;
        }
        .invoice-info .info-box {
            background: #f8f9fc;
            padding: 18px 22px;
            border-radius: 12px;
            border: 1px solid #e9ecef;
        }
        .invoice-info .info-box h6 {
            color: #4e73df;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        .invoice-info .info-box p {
            margin-bottom: 3px;
            font-size: 14px;
            color: #2d3436;
        }
        .invoice-info .info-box p strong {
            color: #2d3436;
        }
        .invoice-info .info-box .payment-badge {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .invoice-info .info-box .payment-badge.unpaid { background: #fff3cd; color: #856404; }
        .invoice-info .info-box .payment-badge.paid { background: #d4edda; color: #155724; }
        .invoice-info .info-box .payment-badge.refunded { background: #f8d7da; color: #721c24; }

        /* ===== TABLE ===== */
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .invoice-table thead th {
            background: #f8f9fc;
            color: #4e73df;
            padding: 12px 15px;
            text-align: left;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e9ecef;
        }
        .invoice-table thead th:last-child {
            text-align: right;
        }
        .invoice-table thead th:nth-child(3) {
            text-align: center;
        }
        .invoice-table tbody td {
            padding: 12px 15px;
            font-size: 14px;
            color: #2d3436;
            border-bottom: 1px solid #f1f3f5;
        }
        .invoice-table tbody td:last-child {
            text-align: right;
            font-weight: 600;
            color: #4e73df;
        }
        .invoice-table tbody td:nth-child(3) {
            text-align: center;
        }
        .invoice-table tbody tr:hover {
            background: #f8f9fc;
        }
        .invoice-table tbody tr:last-child td {
            border-bottom: none;
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
            background: #f8f9fc;
            padding: 20px 25px;
            border-radius: 12px;
            border: 1px solid #e9ecef;
        }
        .invoice-totals .totals-box .total-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            font-size: 14px;
            color: #6c757d;
        }
        .invoice-totals .totals-box .total-row .value {
            color: #2d3436;
            font-weight: 500;
        }
        .invoice-totals .totals-box .total-row.grand-total {
            border-top: 2px solid #4e73df;
            margin-top: 8px;
            padding-top: 12px;
            font-size: 18px;
        }
        .invoice-totals .totals-box .total-row.grand-total .label {
            color: #2d3436;
            font-weight: 700;
        }
        .invoice-totals .totals-box .total-row.grand-total .value {
            color: #4e73df;
            font-size: 22px;
            font-weight: 700;
        }

        /* ===== NOTES ===== */
        .invoice-notes {
            background: #fff8e1;
            padding: 12px 18px;
            border-radius: 8px;
            border-left: 4px solid #ffc107;
            margin-bottom: 25px;
        }
        .invoice-notes p {
            margin: 0;
            font-size: 14px;
            color: #6c757d;
        }
        .invoice-notes p strong {
            color: #856404;
        }

        /* ===== FOOTER ===== */
        .invoice-footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #f1f3f5;
            color: #6c757d;
            font-size: 13px;
        }
        .invoice-footer .brand {
            color: #4e73df;
            font-weight: 700;
            font-size: 16px;
        }
        .invoice-footer .social-icons {
            margin-top: 10px;
            display: flex;
            justify-content: center;
            gap: 12px;
        }
        .invoice-footer .social-icons a {
            color: #adb5bd;
            font-size: 18px;
            transition: color 0.3s;
        }
        .invoice-footer .social-icons a:hover {
            color: #4e73df;
        }

        /* ===== PRINT BUTTON ===== */
        .print-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 28px;
            background: linear-gradient(135deg, #4e73df, #224abe);
            color: #fff;
            border: none;
            border-radius: 30px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 20px;
            text-decoration: none;
        }
        .print-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(78, 115, 223, 0.3);
            color: #fff;
        }
        .print-btn i {
            font-size: 16px;
        }
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 28px;
            background: #f8f9fc;
            color: #6c757d;
            border: 1px solid #dee2e6;
            border-radius: 30px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 20px;
            margin-left: 10px;
            text-decoration: none;
        }
        .back-btn:hover {
            background: #e9ecef;
            color: #2d3436;
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
                width: 100%;
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
            .print-btn, .back-btn {
                padding: 8px 20px;
                font-size: 13px;
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
            .invoice-header .logo-section h1 {
                font-size: 24px;
            }
            .invoice-header .invoice-title h2 {
                font-size: 20px;
            }
        }

        /* ===== PRINT STYLES ===== */
        @media print {
            body {
                background: #fff !important;
                padding: 20px !important;
            }
            .invoice-wrapper {
                box-shadow: none !important;
                border: 1px solid #dee2e6 !important;
                padding: 30px !important;
            }
            .invoice-wrapper::before {
                display: none !important;
            }
            .print-btn, .back-btn, .no-print {
                display: none !important;
            }
            .invoice-header .logo-section h1 {
                -webkit-text-fill-color: #4e73df !important;
            }
            .invoice-info .info-box {
                background: #f8f9fa !important;
                border-color: #dee2e6 !important;
            }
            .invoice-table thead th {
                background: #f8f9fa !important;
                color: #4e73df !important;
                border-color: #dee2e6 !important;
            }
            .invoice-totals .totals-box {
                background: #f8f9fa !important;
                border-color: #dee2e6 !important;
            }
            .invoice-totals .totals-box .total-row.grand-total .value {
                color: #4e73df !important;
            }
            .invoice-notes {
                background: #fff8e1 !important;
            }
        }
    </style>
</head>
<body>

    <!-- ===== BUTTONS ===== -->
    <div style="text-align: center; margin-bottom: 20px;" class="no-print">
        <button onclick="window.print()" class="print-btn">
            <i class="fas fa-print"></i> Print Invoice
        </button>
        <a href="{{ route('admin.orders.show', $order) }}" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Order
        </a>
        <a href="{{ route('invoice', $order->id) }}" class="back-btn" target="_blank">
            <i class="fas fa-external-link-alt"></i> Customer View
        </a>
    </div>

    <!-- ===== INVOICE ===== -->
    <div class="invoice-wrapper">

        <!-- ===== HEADER ===== -->
        <div class="invoice-header">
            <div class="logo-section">
                <h1>AzazeL Warehouse</h1>
                <p><i class="fas fa-map-marker-alt me-1"></i> Kalimantan Barat, Pontianak</p>
                <p><i class="fas fa-envelope me-1"></i> ThriftnTrend1502@gmail.com</p>
            </div>
            <div class="invoice-title">
                <h2>INVOICE</h2>
                <p class="order-number">#{{ $order->order_number }}</p>
                <p style="margin: 5px 0 0;">
                    <span class="status-badge {{ $order->status }}">
                        <i class="fas fa-circle me-1" style="font-size: 8px;"></i>
                        {{ ucfirst($order->status) }}
                    </span>
                </p>
            </div>
        </div>

        <!-- ===== INFO ===== -->
        <div class="invoice-info">
            <div class="info-box">
                <h6><i class="fas fa-user me-2"></i>Bill To</h6>
                <p><strong>{{ $order->customer_name ?? 'Guest' }}</strong></p>
                <p>{{ $order->customer_email ?? '-' }}</p>
                <p><i class="fas fa-phone me-1"></i> {{ $order->customer_phone ?? '-' }}</p>
                @if($order->shipping_address)
                    <p><i class="fas fa-map-marker-alt me-1"></i> {{ $order->shipping_address }}</p>
                    @if($order->shipping_city)
                        <p>{{ $order->shipping_city }} {{ $order->shipping_postal_code ?? '' }}</p>
                    @endif
                @endif
            </div>
            <div class="info-box">
                <h6><i class="fas fa-info-circle me-2"></i>Order Details</h6>
                <p><strong>Order Date:</strong> {{ $order->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY HH:mm') }}</p>
                <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method ?? '-')) }}</p>
                <p><strong>Payment Status:</strong> 
                    <span class="payment-badge {{ $order->payment_status }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </p>
            </div>
        </div>

        <!-- ===== ITEMS TABLE ===== -->
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
                        <td style="text-align: right;">Rp {{ number_format($item['price'] ?? 0, 0, ',', '.') }}</td>
                        <td style="text-align: right;">Rp {{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" style="text-align: center; color: #6c757d; padding: 30px 0;">
                            <i class="fas fa-box fa-2x d-block mb-2"></i>
                            No items found
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        <!-- ===== TOTALS ===== -->
        <div class="invoice-totals">
            <div class="totals-box">
                <div class="total-row">
                    <span class="label">Subtotal</span>
                    <span class="value">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                @if($order->tax > 0)
                <div class="total-row">
                    <span class="label">Tax (10%)</span>
                    <span class="value">Rp {{ number_format($order->tax, 0, ',', '.') }}</span>
                </div>
                @endif
                @if($order->shipping_cost > 0)
                <div class="total-row">
                    <span class="label">Shipping</span>
                    <span class="value">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="total-row grand-total">
                    <span class="label">TOTAL</span>
                    <span class="value">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- ===== NOTES ===== -->
        @if($order->notes)
        <div class="invoice-notes">
            <p><strong><i class="fas fa-sticky-note me-2"></i>Notes:</strong> {{ $order->notes }}</p>
        </div>
        @endif

        <!-- ===== FOOTER ===== -->
        <div class="invoice-footer">
            <p>
                <span class="brand">AzazeL Warehouse</span> &bull; Your Trusted Online Store
            </p>
            <p style="font-size: 12px; color: #adb5bd;">
                <i class="fas fa-check-circle me-1" style="color: #28a745;"></i>
                Thank you for your order! This is a computer-generated invoice.
            </p>
            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
                <a href="#"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>