@extends('layouts.admin')

@section('title', 'Customer Report')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <div>
            <h1 class="mb-0">Customer Report</h1>
            <ol class="breadcrumb mb-0 mt-2">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">Reports</a></li>
                <li class="breadcrumb-item active">Customers</li>
            </ol>
        </div>
        <div>
            <span class="badge bg-primary fs-6 px-3 py-2">
                <i class="fas fa-users me-2"></i>{{ number_format($totalCustomers) }} Customers
            </span>
        </div>
    </div>

    <!-- ===== STATS CARDS ===== -->
    <div class="row stats-row g-4">
        <div class="col-xl-3 col-md-6">
            <div class="stats-card bg-primary text-white">
                <div class="stats-icon"><i class="fas fa-users"></i></div>
                <div class="stats-content">
                    <div class="stats-number">{{ number_format($totalCustomers) }}</div>
                    <div class="stats-label">Total Customers</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card bg-success text-white">
                <div class="stats-icon"><i class="fas fa-shopping-cart"></i></div>
                <div class="stats-content">
                    <div class="stats-number">{{ number_format($totalOrders) }}</div>
                    <div class="stats-label">Total Orders</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card bg-warning text-white">
                <div class="stats-icon"><i class="fas fa-money-bill-wave"></i></div>
                <div class="stats-content">
                    <div class="stats-number">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                    <div class="stats-label">Total Revenue</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card bg-info text-white">
                <div class="stats-icon"><i class="fas fa-chart-line"></i></div>
                <div class="stats-content">
                    <div class="stats-number">Rp {{ number_format($averageSpent, 0, ',', '.') }}</div>
                    <div class="stats-label">Average Spent</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== FILTER SECTION ===== -->
    <div class="filter-section">
        <form method="GET" action="{{ route('admin.reports.customers') }}" id="filterForm">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="filter-label">Start Date</label>
                    <input type="date" class="form-control form-control-sm" name="start_date" value="{{ $startDate ?? now()->startOfMonth()->format('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label class="filter-label">End Date</label>
                    <input type="date" class="form-control form-control-sm" name="end_date" value="{{ $endDate ?? now()->format('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label class="filter-label">Search Customer</label>
                    <input type="text" class="form-control form-control-sm" name="search" 
                           placeholder="Name or email..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="fas fa-search me-1"></i> Filter
                        </button>
                        <a href="{{ route('admin.reports.customers') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-undo"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- ===== CHART & TOP CUSTOMERS ===== -->
    <div class="row g-4">
        <!-- Chart -->
        <div class="col-lg-7">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Top Customers by Spending
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="customerChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top 10 Customers -->
        <div class="col-lg-5">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-trophy me-1"></i>
                    Top 10 Customers
                    <span class="badge bg-primary ms-2">
                        <i class="fas fa-shopping-cart me-1"></i>
                        {{ number_format($topCustomers->sum('total_orders')) }} Orders
                    </span>
                </div>
                <div class="card-body p-0">
                    @if($topCustomers->count() > 0)
                        @foreach($topCustomers as $index => $customer)
                        <div class="customer-item">
                            <div class="rank-badge 
                                @if($loop->iteration == 1) gold
                                @elseif($loop->iteration == 2) silver
                                @elseif($loop->iteration == 3) bronze
                                @else normal @endif">
                                {{ $loop->iteration }}
                            </div>
                            <div class="customer-info">
                                <div class="customer-name">{{ $customer->customer_name ?? 'Guest' }}</div>
                                <div class="customer-email">{{ $customer->customer_email }}</div>
                            </div>
                            <div class="customer-stats">
                                <div class="spent">Rp {{ number_format($customer->total_spent, 0, ',', '.') }}</div>
                                <div class="orders">{{ $customer->total_orders }} orders</div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="d-flex flex-column align-items-center justify-content-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">No customers found</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ===== CUSTOMERS TABLE ===== -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <i class="fas fa-table me-1"></i>
                    All Customers
                    <span class="badge bg-primary ms-2">{{ number_format($customers->total()) }} Total</span>
                </div>
                <div>
                    <form method="GET" action="{{ route('admin.reports.customers') }}" id="sortForm">
                        @foreach(request()->query() as $key => $value)
                            @if($key != 'sort')
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <select class="form-select form-select-sm" name="sort" onchange="document.getElementById('sortForm').submit()" style="width: auto;">
                            <option value="most_orders" {{ request('sort') == 'most_orders' ? 'selected' : '' }}>Most Orders</option>
                            <option value="most_spent" {{ request('sort') == 'most_spent' ? 'selected' : '' }}>Most Spent</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>Customer</th>
                            <th class="text-center" width="100">Orders</th>
                            <th class="text-end" width="150">Total Spent</th>
                            <th class="text-end" width="130">Avg Order</th>
                            <th width="130">Last Order</th>
                            <th class="text-center" width="60">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $index => $customer)
                        <tr>
                            <td class="text-center">{{ $customers->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div>
                                        <div class="fw-bold">{{ $customer->customer_name ?? 'Guest' }}</div>
                                        <small class="text-muted">{{ $customer->customer_email }}</small>
                                        @if($customer->customer_phone)
                                            <br><small class="text-muted"><i class="fas fa-phone me-1"></i>{{ $customer->customer_phone }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge-customer orders">{{ $customer->total_orders }}</span>
                            </td>
                            <td class="text-end">
                                <span class="badge-customer spent">Rp {{ number_format($customer->total_spent, 0, ',', '.') }}</span>
                            </td>
                            <td class="text-end">
                                Rp {{ number_format($customer->average_order, 0, ',', '.') }}
                            </td>
                            <td>
                                @if($customer->last_order)
                                    {{ \Carbon\Carbon::parse($customer->last_order)->locale('id')->isoFormat('D MMM YYYY') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.customers.show', $customer->customer_email) }}" 
                                   class="btn btn-sm btn-info" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">
                                <div class="d-flex flex-column align-items-center justify-content-center py-5">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No customers found</h5>
                                    <p class="text-muted small mb-3">Try adjusting your filters</p>
                                    @if(request('search'))
                                        <a href="{{ route('admin.reports.customers') }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-undo me-1"></i> Clear Search
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- ===== PAGINATION ===== -->
            @if($customers->hasPages())
                <div class="pagination-wrapper">
                    <div class="pagination-info">
                        <i class="fas fa-list me-1"></i>
                        Showing 
                        <strong>{{ $customers->firstItem() ?? 0 }}</strong> 
                        to 
                        <strong>{{ $customers->lastItem() ?? 0 }}</strong> 
                        of 
                        <strong>{{ number_format($customers->total()) }}</strong> 
                        customers
                    </div>
                    <div class="pagination-links">
                        {{ $customers->appends(request()->query())->links() }}
                    </div>
                </div>
            @else
                <div class="text-muted small p-3">
                    <i class="fas fa-info-circle me-1"></i>
                    Showing <strong>{{ $customers->count() }}</strong> customers
                </div>
            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ===== CUSTOMER CHART =====
        const ctx = document.getElementById('customerChart').getContext('2d');
        const chartLabels = @json($chartLabels ?? []);
        const chartData = @json($chartData ?? []);
        const chartColors = @json($chartColors ?? []);
        
        const labels = chartLabels.length > 0 ? chartLabels : ['No Data'];
        const data = chartData.length > 0 ? chartData : [0];
        const colors = chartColors.slice(0, labels.length);
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Spent (Rp)',
                    data: data,
                    backgroundColor: colors,
                    borderColor: colors.map(function(c) { return c + 'cc'; }),
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: { size: 12 }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 30
                        }
                    }
                }
            }
        });
    });
</script>
@endsection