@extends('layouts.admin')

@section('title', 'Customer Detail')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Customer Detail</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.customers.index') }}">Customers</a></li>
        <li class="breadcrumb-item active">{{ $customer->customer_name ?? 'Guest' }}</li>
    </ol>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <div class="row">
        <!-- Customer Profile -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body customer-profile">
                    <div class="avatar-large">
                        {{ strtoupper(substr($customer->customer_name ?? 'G', 0, 2)) }}
                    </div>
                    <h4 class="customer-name">{{ $customer->customer_name ?? 'Guest' }}</h4>
                    <p class="customer-email">{{ $customer->customer_email }}</p>
                    @if($customer->customer_phone)
                        <p><i class="fas fa-phone me-2"></i>{{ $customer->customer_phone }}</p>
                    @endif
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <div class="stat-box">
                                <div class="number">{{ $customer->total_orders }}</div>
                                <div class="label">Total Orders</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-box">
                                <div class="number">Rp. {{ number_format($customer->total_spent, 0, ',', '.') }}</div>
                                <div class="label">Total Spent</div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="text-start">
                        <p><strong>First Order:</strong> {{ \Carbon\Carbon::parse($customer->first_order)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</p>
                        <p><strong>Last Order:</strong> {{ \Carbon\Carbon::parse($customer->last_order)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</p>
                        @if($customer->shipping_address)
                            <p><strong>Address:</strong> {{ $customer->shipping_address }}</p>
                        @endif
                        @if($customer->shipping_city)
                            <p><strong>City:</strong> {{ $customer->shipping_city }}</p>
                        @endif
                        @if($customer->payment_methods)
                            <p><strong>Payment Methods:</strong> {{ str_replace(',', ', ', $customer->payment_methods) }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Customer Orders -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-shopping-cart me-1"></i>
                    Order History
                    <span class="badge bg-primary ms-2">{{ $orders->total() }} Orders</span>
                </div>
                <div class="card-body">
                    @if($orders->count() > 0)
                        @foreach($orders as $order)
                        <div class="order-item">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <div class="order-number">#{{ $order->order_number }}</div>
                                    <small class="text-muted">{{ $order->created_at->locale('id')->isoFormat('D MMM YYYY HH:mm') }}</small>
                                </div>
                                <div class="col-md-3">
                                    <span class="badge bg-{{ $order->status_badge }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                    <br>
                                    <small class="text-muted">{{ ucfirst($order->payment_status) }}</small>
                                </div>
                                <div class="col-md-3">
                                    <div class="order-total">Rp. {{ number_format($order->total, 0, ',', '.') }}</div>
                                </div>
                                <div class="col-md-2 text-end">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                        <!-- Pagination -->
                        @if($orders->hasPages())
                            <div class="mt-3">
                                {{ $orders->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                            <h5>No orders found</h5>
                            <p class="text-muted">This customer hasn't placed any orders yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection