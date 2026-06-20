@extends('layouts.admin')

@section('title', 'Orders Management')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Orders Management</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item active">All Orders</li>
    </ol>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-shopping-cart me-1"></i>
                    Orders List
                </div>
                <div>
                    <span class="badge bg-primary">Total: {{ $counts['all'] }}</span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Filter Section -->
            <div class="filter-section">
                <div class="row align-items-end">
                    <div class="col-md-8">
                        <div class="status-filter">
                            <label class="fw-bold mb-2">Filter by Status:</label>
                            <div>
                                <a href="{{ route('admin.orders.index', ['status' => 'all']) }}" 
                                   class="btn btn-sm {{ $status == 'all' ? 'btn-primary' : 'btn-outline-secondary' }}">
                                    All ({{ $counts['all'] }})
                                </a>
                                <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" 
                                   class="btn btn-sm {{ $status == 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
                                    Pending ({{ $counts['pending'] }})
                                </a>
                                <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" 
                                   class="btn btn-sm {{ $status == 'processing' ? 'btn-info' : 'btn-outline-info' }}">
                                    Processing ({{ $counts['processing'] }})
                                </a>
                                <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" 
                                   class="btn btn-sm {{ $status == 'shipped' ? 'btn-primary' : 'btn-outline-primary' }}">
                                    Shipped ({{ $counts['shipped'] }})
                                </a>
                                <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" 
                                   class="btn btn-sm {{ $status == 'completed' ? 'btn-success' : 'btn-outline-success' }}">
                                    Completed ({{ $counts['completed'] }})
                                </a>
                                <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" 
                                   class="btn btn-sm {{ $status == 'cancelled' ? 'btn-danger' : 'btn-outline-danger' }}">
                                    Cancelled ({{ $counts['cancelled'] }})
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <form method="GET" action="{{ route('admin.orders.index') }}">
                            <input type="hidden" name="status" value="{{ $status }}">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" 
                                       placeholder="Search orders..." value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td>
                                <strong>{{ $order->order_number }}</strong>
                            </td>
                            <td>
                                <div>{{ $order->customer_name ?? 'Guest' }}</div>
                                <small class="text-muted">{{ $order->customer_email }}</small>
                            </td>
                            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <strong>Rp. {{ number_format($order->total, 2) }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-{{ $order->status_badge }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $order->payment_status_badge }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('admin.orders.destroy', $order) }}" 
                                          method="POST" 
                                          style="display: inline-block;"
                                          onsubmit="return confirm('Are you sure you want to delete this order?')">
                                <div class="btn">
                                    <a href="{{ route('admin.orders.show', $order) }}" 
                                       class="btn btn-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.orders.print', $order) }}" 
                                       class="btn btn-secondary" title="Print" target="_blank">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <h5>No orders found</h5>
                                <p class="text-muted">There are no orders to display</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class=" flex-wrap justify-content-between align-items-center mt-3 pt-2 border-top">
                <div>
                    @if($orders->hasPages())
                        {{ $orders->appends(request()->query())->links('pagination::bootstrap-5') }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection