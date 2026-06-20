@extends('layouts.admin')

@section('title', 'Customers Management')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Customers Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Customers</li>
    </ol>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="card bg-danger text-white mb-4 stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small">Total Orders</div>
                            <h2 class="display-6">{{ $totalOrders }}</h2>
                        </div>
                        <i class="fas fa-shopping-cart fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card bg-success text-white mb-4 stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small">Total Revenue</div>
                            <h2 class="display-6">Rp. {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
                        </div>
                        <i class="fas fa-money-bill-wave fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card bg-primary text-white mb-4 stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small">Average Spent</div>
                            <h2 class="display-6">Rp. {{ number_format($averageSpent, 0, ',', '.') }}</h2>
                        </div>
                        <i class="fas fa-chart-line fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-users me-1"></i>
                    Customers List
                    <!-- ===== PERBAIKAN: TAMPILKAN TOTAL CUSTOMERS YANG BENAR ===== -->
                    <span class="badge bg-primary ms-2">{{ $totalCustomers }} Total</span>
                </div>
                <div>
                    <span class="text-muted small">
                        <i class="fas fa-info-circle me-1"></i>
                        Unique customers by email
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Filter Section -->
            <div class="filter-section">
                <form method="GET" action="{{ route('admin.customers.index') }}" id="searchForm">
                    <div class="row align-items-end mb-3">
                        <div class="col-md-6">
                            <div class="search-box">
                                <input type="text" 
                                       class="form-control" 
                                       id="searchInput" 
                                       name="search" 
                                       placeholder="Search by name, email, or phone..."
                                       value="{{ request('search') }}"
                                       oninput="document.getElementById('searchForm').submit()">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select sort-select" name="sort" onchange="this.form.submit()">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest Order</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest Order</option>
                                <option value="most_orders" {{ request('sort') == 'most_orders' ? 'selected' : '' }}>Most Orders</option>
                                <option value="most_spent" {{ request('sort') == 'most_spent' ? 'selected' : '' }}>Most Spent</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            @if(request('search') || request('sort'))
                                <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary w-100">
                                    <i class="fas fa-times"></i> Clear
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
                
                @if(request('search'))
                    <div class="mt-2">
                        <span class="text-muted">
                            Showing results for: <strong>"{{ request('search') }}"</strong>
                            <span class="badge bg-primary ms-2">{{ $customers->total() }} results</span>
                        </span>
                    </div>
                @endif
            </div>

            <!-- Customers Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>Customer</th>
                            <th class="text-center">Orders</th>
                            <th class="text-end">Total Spent</th>
                            <th>Last Order</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <a href="{{ route('admin.customers.show', $customer->customer_email) }}" class="customer-link">
                                    <div class="customer-name mb-1">{{ $customer->customer_name ?? 'Guest' }}</div>
                                    <div class="customer-email mb-1">{{ $customer->customer_email }}</div>
                                    @if($customer->customer_phone)
                                        <small class="text-muted"><i class="fas fa-phone me-1"></i>{{ $customer->customer_phone }}</small>
                                    @endif
                                </a>
                            </td>
                            <td class="text-center">
                                <span class="badge-order">{{ $customer->total_orders }} orders</span>
                            </td>
                            <td class="text-end">
                                <span class="badge-spent">Rp. {{ number_format($customer->total_spent, 0, ',', '.') }}</span>
                            </td>
                            <td>
                                @if($customer->last_order)
                                    {{ \Carbon\Carbon::parse($customer->last_order)->locale('id')->isoFormat('D MMM YYYY') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.customers.show', $customer->customer_email) }}" 
                                       class="btn btn-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-users fa-3x text-muted mb-3 d-block"></i>
                                <h5>No customers found</h5>
                                <p class="text-muted">There are no customers registered yet</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($customers->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Showing {{ $customers->firstItem() ?? 0 }} to {{ $customers->lastItem() ?? 0 }} 
                        of {{ $customers->total() }} customers
                    </div>
                    <div>
                        {{ $customers->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    // Auto submit on enter key
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('searchForm').submit();
        }
    });
</script>
@endsection