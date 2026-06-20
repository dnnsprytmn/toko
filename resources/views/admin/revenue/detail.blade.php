@extends('layouts.admin')

@section('title', 'Revenue Detail')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Revenue Detail</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.revenue.index') }}">Revenue</a></li>
        <li class="breadcrumb-item active">Detail</li>
    </ol>

    <!-- Filter Section -->
    <div class="filter-section">
        <form method="GET" action="{{ route('admin.revenue.detail') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Start Date</label>
                <input type="date" class="form-control" name="start_date" value="{{ $startDate }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">End Date</label>
                <input type="date" class="form-control" name="end_date" value="{{ $endDate }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Payment Method</label>
                <select class="form-select" name="payment_method">
                    <option value="all" {{ $paymentMethod == 'all' ? 'selected' : '' }}>All Methods</option>
                    @foreach($paymentMethods as $method)
                        <option value="{{ $method }}" {{ $paymentMethod == $method ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $method)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.revenue.detail') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Total Filtered -->
    <div class="total-filtered">
        <div class="row align-items-center mt-2 mb-2">
            <div class="col-md-6">
                <small><i class="fas fa-money-bill-wave me-1"></i> Total Revenue (Filtered)</small>
                <h3>Rp. {{ number_format($totalFiltered, 0, ',', '.') }}</h3>
            </div>
            <div class="col-md-6 text-md-end">
                <span class="badge bg-light text-dark">
                    <i class="fas fa-calendar me-1"></i>
                    {{ $startDate }} - {{ $endDate }}
                </span>
                <span class="badge bg-light text-dark ms-2">
                    <i class="fas fa-list me-1"></i>
                    {{ $transactions->total() }} Transactions
                </span>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Transaction List
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                        <tr>
                            <td>
                                <strong>#{{ $transaction->order_number }}</strong>
                            </td>
                            <td>{{ $transaction->customer_name ?? 'Guest' }}</td>
                            <td>{{ $transaction->created_at->locale('id')->isoFormat('D MMM YYYY HH:mm') }}</td>
                            <td>
                                <span class="payment-method-badge {{ $transaction->payment_method ?? 'unknown' }}">
                                    {{ ucfirst(str_replace('_', ' ', $transaction->payment_method ?? 'Unknown')) }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $statusClass = [
                                        'pending' => 'warning',
                                        'processing' => 'info',
                                        'shipped' => 'primary',
                                        'completed' => 'success',
                                        'cancelled' => 'danger'
                                    ][$transaction->status ?? 'pending'] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">
                                    {{ ucfirst($transaction->status ?? 'Pending') }}
                                </span>
                            </td>
                            <td>
                                <strong>Rp. {{ number_format($transaction->total, 0, ',', '.') }}</strong>
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $transaction) }}" 
                                   class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <h5>No transactions found</h5>
                                <p class="text-muted">Try adjusting your filters</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($transactions->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Showing {{ $transactions->firstItem() ?? 0 }} to {{ $transactions->lastItem() ?? 0 }} 
                        of {{ $transactions->total() }} results
                    </div>
                    <div>
                        {{ $transactions->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection