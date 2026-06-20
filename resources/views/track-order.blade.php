@extends('layouts.app')

@section('title', 'Track Order - AzazeL Warehouse')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="page-content">
                <div class="heading-section text-center">
                    <h4><em>CEK ORDERAN</em> KAMU DISINI</h4>
                </div>

                <!-- ***** Track Order Section Start ***** -->
                <div class="track-section">
                    <div class="heading-section">
                        <h4><em>Track</em> Your Order</h4>
                        <p style="color: #666; font-size: 15px;">Enter your order ID to check the status of your order</p>
                    </div>

                    <form action="{{ route('track.order.search') }}" method="POST" class="track-form">
                        @csrf
                        <div class="input-group">
                            <input type="text" 
                                   class="form-control" 
                                   name="order_id" 
                                   placeholder="Enter your order ID (e.g. ORD-20240619-XXX)" 
                                   value="{{ request('order_id') }}"
                                   required>
                            <button type="submit" class="btn-track">
                                <i class="fa fa-search"></i> Track Order
                            </button>
                        </div>
                    </form>
                    <p class="track-info">
                        <i class="fa fa-info-circle"></i>
                        Enter your order ID to check order status
                    </p>

                    <!-- ===== ORDER NOT FOUND ===== -->
                    @if(isset($notFound) && $notFound)
                    <div class="order-not-found">
                        <span class="not-found-icon">
                            <i class="fa fa-exclamation-circle"></i>
                        </span>
                        <h4><em>Order</em> Not Found</h4>
                        <p>We couldn't find any order with the ID:</p>
                        <div class="order-id-highlight">
                            <i class="fa fa-hashtag me-1"></i> {{ $orderId }}
                        </div>
                        <p style="margin-top: 10px; font-size: 14px;">Please check your order ID and try again.</p>
                        
                        <div class="suggestions">
                            <h6><i class="fa fa-lightbulb me-2"></i> Suggestions</h6>
                            <ul>
                                <li><i class="fa fa-check-circle"></i> Make sure you entered the correct order ID</li>
                                <li><i class="fa fa-check-circle"></i> Check your email for the order confirmation</li>
                                <li><i class="fa fa-check-circle"></i> The order ID format is: ORD-YYYYMMDD-XXXXX</li>
                                <li><i class="fa fa-check-circle"></i> Contact us if you need assistance</li>
                            </ul>
                        </div>

                        <a href="{{ route('track.order') }}" class="btn-try-again">
                            <i class="fa fa-search"></i> Try Again
                        </a>
                    </div>
                    @endif

                    <!-- Order Result -->
                    @if(isset($order))
                        @if($order)
                        <div class="order-result">
                            <div class="order-header">
                                <h4><em>Order</em> #{{ $order->order_number }}</h4>
                                <span class="badge-status {{ $order->status }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>

                            <div class="order-details">
                                <div class="detail-item">
                                    <div class="label">Order Date</div>
                                    <div class="value">{{ $order->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY HH:mm') }}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="label">Total</div>
                                    <div class="value total">Rp. {{ number_format($order->total, 0, ',', '.') }}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="label">Payment Status</div>
                                    <div class="value">
                                        <span class="badge bg-{{ $order->payment_status_badge }}" style="padding: 4px 12px; border-radius: 20px;">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="order-items">
                                <h6><i class="fa fa-box me-2"></i> Order Items</h6>
                                @if($order->items)
                                    @php $items = json_decode($order->items, true); @endphp
                                    @foreach($items as $item)
                                    <div class="item-row">
                                        <span class="item-name">{{ $item['name'] ?? 'Product' }} × {{ $item['quantity'] ?? 1 }}</span>
                                        <span class="item-price">Rp. {{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 0, ',', '.') }}</span>
                                    </div>
                                    @endforeach
                                @endif
                            </div>

                            <div class="order-actions">
                                <a href="{{ route('invoice', $order->id) }}" class="btn-action btn-primary" target="_blank">
                                    <i class="fa fa-file-text"></i> View Invoice
                                </a>
                                <a href="{{ route('home') }}" class="btn-action btn-secondary">
                                    <i class="fa fa-home"></i> Back to Home
                                </a>
                            </div>
                        </div>
                        @else
                        <div class="order-not-found">
                            <i class="fa fa-exclamation-circle"></i>
                            <h5>Order Not Found</h5>
                            <p>Please check your order ID and try again.</p>
                        </div>
                        @endif
                    @endif
                </div>
                <!-- ***** Track Order Section End ***** -->

            </div>
        </div>
    </div>
</div>
@endsection