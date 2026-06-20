@extends('layouts.admin')

@section('title', 'Order Detail')

@section('styles')
<style>
    .order-info {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .order-info .info-item {
        margin-bottom: 10px;
    }
    .order-info .info-item strong {
        display: inline-block;
        width: 150px;
    }
    .product-item {
        border-bottom: 1px solid #e9ecef;
        padding: 15px 0;
    }
    .product-item:last-child {
        border-bottom: none;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Order Detail</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
        <li class="breadcrumb-item active">{{ $order->order_number }}</li>
    </ol>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <div class="row">
        <div class="col-md-8">
            <!-- Order Items -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-box me-1"></i>
                    Order Items
                </div>
                <div class="card-body">
                    @if($order->items)
                        @foreach(json_decode($order->items, true) as $item)
                        <div class="product-item d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="mb-0">{{ $item['name'] ?? 'Product' }}</h6>
                                <small class="text-muted">
                                    Qty: {{ $item['quantity'] ?? 1 }} × Rp. {{ number_format($item['price'] ?? 0, 2) }}
                                </small>
                            </div>
                            <div>
                                <strong>Rp. {{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 2) }}</strong>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted">No items found</p>
                    @endif
                </div>
            </div>
            
            <!-- Order Notes -->
            @if($order->notes)
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-sticky-note me-1"></i>
                    Order Notes
                </div>
                <div class="card-body">
                    {{ $order->notes }}
                </div>
            </div>
            @endif
        </div>
        
        <div class="col-md-4">
            <!-- Order Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle me-1"></i>
                    Order Information
                </div>
                <div class="card-body">
                    <div class="order-info">
                        <div class="info-item">
                            <strong>Order ID:</strong>
                            <span>{{ $order->order_number }}</span>
                        </div>
                        <div class="info-item">
                            <strong>Date:</strong>
                            <span>{{ $order->created_at->format('d M Y H:i') }}</span>
                        </div>
                        <div class="info-item">
                            <strong>Status:</strong>
                            <span class="badge bg-{{ $order->status_badge }}">{{ ucfirst($order->status) }}</span>
                        </div>
                        <div class="info-item">
                            <strong>Payment:</strong>
                            <span class="badge bg-{{ $order->payment_status_badge }}">{{ ucfirst($order->payment_status) }}</span>
                        </div>
                        @if($order->payment_method)
                        <div class="info-item">
                            <strong>Payment Method:</strong>
                            <span>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                        </div>
                        @endif
                    </div>
                    
                    <h6 class="mt-3">Customer Details</h6>
                    <div class="order-info">
                        <div class="info-item">
                            <strong>Name:</strong>
                            <span>{{ $order->customer_name ?? 'Guest' }}</span>
                        </div>
                        <div class="info-item">
                            <strong>Email:</strong>
                            <span>{{ $order->customer_email ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <strong>Phone:</strong>
                            <span>{{ $order->customer_phone ?? '-' }}</span>
                        </div>
                        @if($order->shipping_address)
                        <div class="info-item">
                            <strong>Address:</strong>
                            <span>{{ $order->shipping_address }}</span>
                        </div>
                        @endif
                        @if($order->shipping_city)
                        <div class="info-item">
                            <strong>City:</strong>
                            <span>{{ $order->shipping_city }}</span>
                        </div>
                        @endif
                    </div>
                    
                    <h6 class="mt-3">Order Summary</h6>
                    <div class="order-info">
                        <div class="info-item">
                            <strong>Subtotal:</strong>
                            <span>Rp. {{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        @if($order->shipping_cost > 0)
                        <div class="info-item">
                            <strong>Shipping:</strong>
                            <span>Rp. {{ number_format($order->shipping_cost, 2) }}</span>
                        </div>
                        @endif
                        <div class="info-item">
                            <strong>Total:</strong>
                            <span class="h5 text-primary">Rp. {{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Update Status -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-edit me-1"></i>
                    Update Status
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Order Status</label>
                            <select name="status" class="form-select">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-1"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Update Payment Status -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-credit-card me-1"></i>
                    Update Payment
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update-payment', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Payment Status</label>
                            <select name="payment_status" class="form-select">
                                <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-save me-1"></i> Update Payment
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Print Button -->
            <div class="card mb-4">
                <div class="card-body">
                    <a href="{{ route('admin.orders.print', $order) }}" class="btn btn-secondary w-100" target="_blank">
                        <i class="fas fa-print me-1"></i> Print Order
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection