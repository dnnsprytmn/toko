@extends('layouts.app')

@section('title', 'Order Success')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="page-content">
                <div class="heading-section text-center">
                    <h4><em>CHECKOUT</em> BERHASIL</h4>
                </div>
                <!-- ***** Success Section Start ***** -->
                <div class="success-section">
                    <!-- Icon -->
                    <span class="success-icon">
                        <i class="fa fa-check-circle"></i>
                    </span>

                    <!-- Title -->
                    <h2>Thank You for <em>Your Order!</em></h2>
                    <p class="subtitle">Your order has been placed successfully.</p>

                    <!-- Order Details -->
                    <div class="order-card">
                        <div class="order-row">
                            <span class="label">Order Number</span>
                            <span class="value"><strong>#{{ $order->order_number }}</strong></span>
                        </div>
                        <div class="order-row">
                            <span class="label">Order Date</span>
                            <span class="value">{{ $order->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY HH:mm') }}</span>
                        </div>
                        <div class="order-row">
                            <span class="label">Total</span>
                            <span class="value total">Rp. {{ number_format($order->total, 0, ',', '.') }}</span>
                        </div>
                        <div class="order-row">
                            <span class="label">Status</span>
                            <span class="value">
                                <span class="badge-status bg-{{ $order->status_badge }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </span>
                        </div>
                        <div class="order-row">
                            <span class="label">Payment Status</span>
                            <span class="value">
                                <span class="badge-status bg-{{ $order->payment_status_badge }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </span>
                        </div>
                        <div class="order-row">
                            <span class="label">Payment Method</span>
                            <span class="value">{{ ucfirst(str_replace('_', ' ', $order->payment_method ?? '-')) }}</span>
                        </div>
                    </div>

                    <!-- Email Info -->
                    <div class="email-info">
                        <i class="fa fa-envelope"></i>
                        We have sent a confirmation email to <strong>{{ $order->customer_email }}</strong>
                    </div>

                    <!-- Action Buttons -->
                    <div class="btn-group-action">
                        <a href="{{ route('invoice', $order->id) }}" class="btn-action btn-primary" target="_blank">
                            <i class="fa fa-file-text"></i> View Invoice
                        </a>
                        <a href="{{ route('home') }}" class="btn-action btn-secondary">
                            <i class="fa fa-home"></i> Kembali ke Beranda
                        </a>
                        <a href="{{ route('shop.all') }}" class="btn-action btn-success">
                            <i class="fa fa-shopping-bag"></i> Shop More
                        </a>
                    </div>
                </div>
                <!-- ***** Success Section End ***** -->

            </div>
        </div>
    </div>
</div>
@endsection