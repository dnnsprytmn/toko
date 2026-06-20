@extends('layouts.admin')

@section('title', 'Revenue Overview')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Revenue Overview</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Revenue</li>
    </ol>

    <!-- Total Revenue -->
    <div class="revenue-stats">
        <div class="row align-items-center">
            <div class="col-md-8">
                <small><i class="fas fa-chart-line me-1"></i> Total Revenue</small>
                <h2>Rp. {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
                <p class="mb-0">
                    <i class="fas fa-calendar me-1"></i>
                    All time revenue from completed orders
                </p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('admin.revenue.detail') }}" class="btn btn-light">
                    <i class="fas fa-arrow-right me-1"></i> View Details
                </a>
            </div>
        </div>
    </div>

    <hr>

    <!-- Quick Stats -->
    <div class="row mb-4 mt-2">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-calendar-day"></i></div>
                <div class="stat-number">Rp. {{ number_format($stats['today'], 0, ',', '.') }}</div>
                <div class="stat-label">Today's Revenue</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-calendar-week"></i></div>
                <div class="stat-number">Rp. {{ number_format($stats['this_week'], 0, ',', '.') }}</div>
                <div class="stat-label">This Week</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-calendar-week"></i></div>
                <div class="stat-number">Rp. {{ number_format($stats['this_month'], 0, ',', '.') }}</div>
                <div class="stat-label">This Month</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-calendar-week"></i></div>
                <div class="stat-number">Rp. {{ number_format($stats['this_year'], 0, ',', '.') }}</div>
                <div class="stat-label">This Year</div>
            </div>
        </div>
    </div>

    <!-- Monthly Revenue Chart -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-chart-bar me-1"></i>
            Monthly Revenue (Last 12 Months)
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="monthlyRevenueChart"></canvas>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Revenue by Payment Method -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-credit-card me-1"></i>
                    Revenue by Payment Method
                </div>
                <div class="card-body">
                    @if($revenueByPayment->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Payment Method</th>
                                        <th>Revenue</th>
                                        <th>Percentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($revenueByPayment as $item)
                                        @php
                                            $percentage = $totalRevenue > 0 ? ($item->total / $totalRevenue * 100) : 0;
                                        @endphp
                                        <tr>
                                            <td>
                                                <span class="payment-method-badge {{ $item->payment_method ?? 'unknown' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $item->payment_method ?? 'Unknown')) }}
                                                </span>
                                            </td>
                                            <td>Rp. {{ number_format($item->total, 0, ',', '.') }}</td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" 
                                                         role="progressbar" 
                                                         style="width: {{ $percentage }}%"
                                                         aria-valuenow="{{ $percentage }}" 
                                                         aria-valuemin="0" 
                                                         aria-valuemax="100">
                                                        {{ number_format($percentage, 1) }}%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-inbox fa-2x text-muted"></i>
                            <p class="text-muted">No payment data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-history me-1"></i>
                    Recent Transactions
                    <a href="{{ route('admin.revenue.detail') }}" class="btn btn-sm btn-primary float-end">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    @if($recentTransactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Order</th>
                                        <th>Customer</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentTransactions as $transaction)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $transaction) }}">
                                                #{{ $transaction->order_number }}
                                            </a>
                                        </td>
                                        <td>{{ $transaction->customer_name ?? 'Guest' }}</td>
                                        <td>Rp. {{ number_format($transaction->total, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-inbox fa-2x text-muted"></i>
                            <p class="text-muted">No transactions found</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Monthly Revenue Chart
        const ctx = document.getElementById('monthlyRevenueChart').getContext('2d');
        const chartLabels = @json($chartLabels);
        const chartData = @json($chartData);
        
        // Default labels jika kosong
        const labels = chartLabels.length > 0 ? chartLabels : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const data = chartData.length > 0 ? chartData : [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Revenue (Rp)',
                    data: data,
                    backgroundColor: 'rgba(13, 110, 253, 0.7)',
                    borderColor: 'rgba(13, 110, 253, 1)',
                    borderWidth: 1,
                    borderRadius: 5,
                    hoverBackgroundColor: 'rgba(13, 110, 253, 0.9)'
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
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp. ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp. ' + value.toLocaleString('id-ID');
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>
@endsection