@extends('layouts.admin')

@section('title', 'Product Report')

@section('content')
@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <div>
            <h1 class="mb-0">Product Report</h1>
            <ol class="breadcrumb mb-0 mt-2">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">Reports</a></li>
                <li class="breadcrumb-item active">Products</li>
            </ol>
        </div>
        <div>
            <span class="badge bg-primary fs-6 px-3 py-2">
                <i class="fas fa-box me-2"></i>{{ number_format($stats['total_products'] ?? 0) }} Products
            </span>
        </div>
    </div>

    <!-- ===== STATS CARDS ===== -->
    <div class="row stats-row g-4">
        <div class="col-xl-3 col-md-6">
            <div class="stats-card bg-primary text-white">
                <div class="stats-icon"><i class="fas fa-box"></i></div>
                <div class="stats-content">
                    <div class="stats-number">{{ number_format($stats['total_products'] ?? 0) }}</div>
                    <div class="stats-label">Total Products</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card bg-danger text-white">
                <div class="stats-icon"><i class="fas fa-shopping-cart"></i></div>
                <div class="stats-content">
                    <div class="stats-number">{{ number_format($stats['total_sold'] ?? 0) }}</div>
                    <div class="stats-label">Total Sold</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card bg-success text-white">
                <div class="stats-icon"><i class="fas fa-money-bill-wave"></i></div>
                <div class="stats-content">
                    <div class="stats-number">Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}</div>
                    <div class="stats-label">Total Revenue</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card bg-info text-white">
                <div class="stats-icon"><i class="fas fa-tag"></i></div>
                <div class="stats-content">
                    <div class="stats-number">Rp {{ number_format($stats['average_price'] ?? 0, 0, ',', '.') }}</div>
                    <div class="stats-label">Average Price</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== TOP PRODUCTS CHART ===== -->
    <div class="row g-4">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Top Products (Most Sold)
                    <span class="badge bg-primary ms-2">
                        <i class="fas fa-shopping-cart me-1"></i>
                        Total Units: {{ number_format(array_sum($chartData ?? [])) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="productChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== FILTER SECTION ===== -->
    <div class="filter-section">
        <form method="GET" action="{{ route('admin.reports.products') }}" id="filterForm">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="filter-label">Search Product</label>
                    <input type="text" class="form-control form-control-sm" name="search" 
                           placeholder="Search by product name..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <label class="filter-label">Sort By</label>
                    <select class="form-select form-select-sm" name="sort" onchange="document.getElementById('filterForm').submit()">
                        <option value="most_sold" {{ request('sort') == 'most_sold' ? 'selected' : '' }}>Most Sold</option>
                        <option value="most_revenue" {{ request('sort') == 'most_revenue' ? 'selected' : '' }}>Most Revenue</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                        <option value="highest_price" {{ request('sort') == 'highest_price' ? 'selected' : '' }}>Highest Price</option>
                        <option value="lowest_price" {{ request('sort') == 'lowest_price' ? 'selected' : '' }}>Lowest Price</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-search me-1"></i> Apply
                    </button>
                </div>
            </div>
            @if(request('search'))
                <div class="mt-3">
                    <span class="badge bg-info">
                        <i class="fas fa-info-circle me-1"></i>
                        Showing results for: "{{ request('search') }}"
                    </span>
                    <a href="{{ route('admin.reports.products') }}" class="btn btn-secondary btn-sm ms-2">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            @endif
        </form>
    </div>

    <!-- ===== PRODUCTS TABLE ===== -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <i class="fas fa-table me-1"></i>
                    All Products Performance
                    <span class="badge bg-primary ms-2">{{ number_format($products->count() ?? 0) }} Products</span>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>Product</th>
                            <th class="text-center" width="100">Sold</th>
                            <th class="text-end" width="150">Revenue</th>
                            <th class="text-end" width="130">Avg Order</th>
                            <th class="text-end" width="130">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products ?? [] as $index => $product)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-image">
                                    <div>
                                        <div class="fw-bold">{{ $product->name }}</div>
                                        <small class="text-muted">ID: #{{ $product->id }}</small>
                                        @if($product->is_sale)
                                            <span class="badge bg-danger ms-1">Sale</span>
                                        @endif
                                        @if($product->is_popular)
                                            <span class="badge bg-warning text-dark ms-1">Popular</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge-sold">{{ number_format($product->units_sold ?? 0) }}</span>
                            </td>
                            <td class="text-end">
                                <span class="badge-revenue">
                                    Rp {{ number_format($product->total_revenue ?? 0, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="text-end">
                                Rp {{ number_format($product->average_price ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="text-end">
                                <span class="fw-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="d-flex flex-column align-items-center justify-content-center py-5">
                                    <i class="fas fa-box fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No products found</h5>
                                    <p class="text-muted small mb-3">Try adjusting your search or filters</p>
                                    @if(request('search'))
                                        <a href="{{ route('admin.reports.products') }}" class="btn btn-primary btn-sm">
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
            
            <!-- Pagination -->
            <div class=" flex-wrap justify-content-between align-items-center mt-3 pt-2 border-top">
                <div>
                    @if($products->hasPages())
                        {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ===== PRODUCT CHART =====
        const ctx = document.getElementById('productChart').getContext('2d');
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
                    label: 'Units Sold',
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
                                return context.parsed.y + ' units sold';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
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