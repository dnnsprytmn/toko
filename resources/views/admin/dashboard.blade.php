@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
@section('content')
<div class="container-fluid px-4">
    <!-- ===== WELCOME SECTION ===== -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-primary alert-dismissible fade show" role="alert">
                <i class="fas fa-hand-wave me-2"></i>
                Welcome back, <strong>{{ Auth::guard('admin')->user()->name }}</strong>! 
                Here's what's happening with your store today.
                @if(Auth::guard('admin')->user()->role)
                    <span class="badge bg-light text-dark ms-2">
                        <i class="fas fa-user-tag me-1"></i>
                        {{ ucfirst(Auth::guard('admin')->user()->role) }}
                    </span>
                @endif
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>

    <!-- ===== PAGE HEADER ===== -->
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <div>
            <h1 class="mb-0">Dashboard</h1>
            <ol class="breadcrumb mb-0 mt-2">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </div>
        <div>
            <span class="badge bg-primary fs-6 px-3 py-2">
                <i class="fas fa-calendar-alt me-2"></i>
                {{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
            </span>
        </div>
    </div>
    
    <!-- ===== STATS CARDS ===== -->
    <div class="row stats-row g-4">
        <div class="col-xl-3 col-md-6">
            <div class="stats-card bg-warning text-white">
                <div class="stats-icon"><i class="fas fa-box"></i></div>
                <div class="stats-content">
                    <div class="stats-number">{{ number_format($totalProducts) }}</div>
                    <div class="stats-label">Total Products</div>
                </div>
                <div class="stats-footer">
                    <a href="{{ route('admin.products.index') }}">View Details</a>
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stats-card bg-danger text-white">
                <div class="stats-icon"><i class="fas fa-shopping-cart"></i></div>
                <div class="stats-content">
                    <div class="stats-number">{{ number_format($totalOrders) }}</div>
                    <div class="stats-label">Total Orders</div>
                </div>
                <div class="stats-footer">
                    <a href="{{ route('admin.orders.index') }}">View Details</a>
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stats-card bg-success text-white">
                <div class="stats-icon"><i class="fas fa-money-bill-wave"></i></div>
                <div class="stats-content">
                    <div class="stats-number">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                    <div class="stats-label">Total Revenue</div>
                </div>
                <div class="stats-footer">
                    <a href="{{ route('admin.revenue.index') }}">View Details</a>
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stats-card bg-primary text-white">
                <div class="stats-icon"><i class="fas fa-users"></i></div>
                <div class="stats-content">
                    <div class="stats-number">{{ number_format($totalAdmins) }}</div>
                    <div class="stats-label">Total Admins</div>
                </div>
                <div class="stats-footer">
                    @if(Auth::guard('admin')->user()->role != 'staff')
                        <a href="{{ route('admin.admins.index') }}">View Details</a>
                        <i class="fas fa-arrow-right"></i>
                    @else
                        <span style="color: rgba(255,255,255,0.6); font-size: 13px;">
                            <i class="fas fa-lock me-1"></i> Restricted
                        </span>
                        <i class="fas fa-lock" style="opacity: 0.5;"></i>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- ===== CHARTS ===== -->
    <div class="row g-4">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-line me-1"></i>
                    Sales Overview (Last 6 Months)
                    <span class="badge bg-primary ms-2">
                        <i class="fas fa-calendar me-1"></i>
                        {{ now()->subMonths(6)->format('M Y') }} - {{ now()->format('M Y') }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>
                    Order Status Distribution
                    <span class="badge bg-primary ms-2">
                        <i class="fas fa-shopping-cart me-1"></i>
                        {{ array_sum($statusData ?? []) }} Total
                    </span>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="orderStatusChart"></canvas>
                    </div>
                    <div class="order-status-legend" id="statusLegend">
                        <!-- Legend akan diisi oleh JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- ===== RECENT ORDERS TABLE ===== -->
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <i class="fas fa-table me-1"></i>
                    Recent Orders
                    <span class="badge bg-primary ms-2">
                        <i class="fas fa-clock me-1"></i>
                        Last 5 Orders
                    </span>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-eye me-1"></i> View All Orders
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            @if($recentOrders->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th class="text-end">Total</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" width="60">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $index => $order)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>
                                <span class="fw-bold">#{{ $order->order_number ?? 'ORD-'.$order->id }}</span>
                            </td>
                            <td>
                                <div>
                                    <div class="fw-bold">{{ $order->customer_name ?? 'Guest' }}</div>
                                    <small class="text-muted">{{ $order->customer_email ?? '-' }}</small>
                                </div>
                            </td>
                            <td>{{ $order->created_at->locale('id')->isoFormat('D MMM YYYY HH:mm') }}</td>
                            <td class="text-end fw-bold">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                            <td class="text-center">
                                @php
                                    $statusClass = [
                                        'pending' => 'warning',
                                        'processing' => 'info',
                                        'shipped' => 'primary',
                                        'completed' => 'success',
                                        'cancelled' => 'danger'
                                    ][$order->status ?? 'pending'] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">
                                    {{ ucfirst($order->status ?? 'Pending') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.orders.show', $order) }}" 
                                   class="btn btn-sm btn-info" title="View Order">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-4">
                <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                <h5>No orders found</h5>
                <p class="text-muted">There are no recent orders to display.</p>
            </div>
            @endif
        </div>
    </div>
    
    <!-- ===== RECENT PRODUCTS TABLE ===== -->
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <i class="fas fa-box me-1"></i>
                    Recent Products
                    <span class="badge bg-primary ms-2">
                        <i class="fas fa-clock me-1"></i>
                        Last 5 Products
                    </span>
                </div>
                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-eye me-1"></i> Manage Products
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            @if($recentProducts->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th width="80">Image</th>
                            <th>Name</th>
                            <th class="text-end">Price</th>
                            <th>Created At</th>
                            <th class="text-center" width="60">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentProducts as $index => $product)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                     width="50" height="50" style="object-fit: cover; border-radius: 8px;">
                            </td>
                            <td>
                                <div class="fw-bold">{{ $product->name }}</div>
                                <small class="text-muted">ID: #{{ $product->id }}</small>
                            </td>
                            <td class="text-end fw-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>{{ $product->created_at->locale('id')->isoFormat('D MMM YYYY HH:mm') }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.products.edit', $product) }}" 
                                   class="btn btn-sm btn-primary" title="Edit Product">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-4">
                <i class="fas fa-box fa-3x text-muted mb-3 d-block"></i>
                <h5>No products found</h5>
                <p class="text-muted">
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i> Add your first product
                    </a>
                </p>
            </div>
            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ===== SALES CHART (Line Chart) =====
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        const chartMonths = @json($chartMonths);
        const chartSales = @json($chartSales);
        
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: chartMonths.length > 0 ? chartMonths : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Sales Revenue',
                    data: chartSales.length > 0 ? chartSales : [0, 0, 0, 0, 0, 0],
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                    fill: true,
                    tension: 0.3
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
                    }
                }
            }
        });

        // ===== ORDER STATUS CHART (Doughnut Chart) =====
        const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
        const statusLabels = @json($statusLabels);
        const statusColors = @json($statusColors);
        const statusData = @json($statusData);
        
        const totalOrders = statusData.reduce((a, b) => a + b, 0);
        
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusData,
                    backgroundColor: statusColors,
                    borderColor: '#fff',
                    borderWidth: 2,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                                return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            },
            plugins: [{
                id: 'centerText',
                beforeDraw: function(chart) {
                    const { ctx, chartArea } = chart;
                    const centerX = (chartArea.left + chartArea.right) / 2;
                    const centerY = (chartArea.top + chartArea.bottom) / 2;
                    const total = chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                    
                    ctx.save();
                    ctx.font = 'bold 24px Arial';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillStyle = '#2d3436';
                    ctx.fillText(total, centerX, centerY - 5);
                    ctx.font = '12px Arial';
                    ctx.fillStyle = '#6c757d';
                    ctx.fillText('Total Orders', centerX, centerY + 20);
                    ctx.restore();
                }
            }]
        });

        // ===== GENERATE LEGEND =====
        const legendContainer = document.getElementById('statusLegend');
        let legendHtml = '';
        statusLabels.forEach((label, index) => {
            const count = statusData[index];
            const percentage = totalOrders > 0 ? ((count / totalOrders) * 100).toFixed(1) : 0;
            legendHtml += `
                <div class="legend-item">
                    <span class="legend-color" style="background: ${statusColors[index]}"></span>
                    ${label}: <strong>${count}</strong> (${percentage}%)
                </div>
            `;
        });
        legendContainer.innerHTML = legendHtml;
    });
</script>
@endsection