@extends('layouts.admin')

@section('title', 'Sales Report')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Sales Report</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="#">Reports</a></li>
        <li class="breadcrumb-item active">Sales</li>
    </ol>

    <!-- Filter -->
    <div class="filter-section">
        <form method="GET" action="{{ route('admin.reports.sales') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-bold">Start Date</label>
                <input type="date" class="form-control" name="start_date" value="{{ $startDate }}">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">End Date</label>
                <input type="date" class="form-control" name="end_date" value="{{ $endDate }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i> Generate Report
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="summary-card">
                <div class="icon"><i class="fas fa-shopping-cart"></i></div>
                <div class="number">{{ $summary['total_orders'] }}</div>
                <div class="label">Total Orders</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card">
                <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
                <div class="number">Rp. {{ number_format($summary['total_revenue'], 0, ',', '.') }}</div>
                <div class="label">Total Revenue</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card">
                <div class="icon"><i class="fas fa-chart-line"></i></div>
                <div class="number">Rp. {{ number_format($summary['average_order'], 0, ',', '.') }}</div>
                <div class="label">Average Order</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card">
                <div class="icon"><i class="fas fa-users"></i></div>
                <div class="number">{{ $summary['total_customers'] }}</div>
                <div class="label">Total Customers</div>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-chart-bar me-1"></i>
            Sales Trend (Last 12 Months)
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Sales Data Table -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Sales Data
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th class="text-center">Orders</th>
                            <th class="text-end">Revenue</th>
                            <th class="text-end">Average Order</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salesData as $data)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($data->date)->locale('id')->isoFormat('D MMM YYYY') }}</td>
                            <td class="text-center">{{ $data->total_orders }}</td>
                            <td class="text-end">Rp. {{ number_format($data->total_revenue, 0, ',', '.') }}</td>
                            <td class="text-end">Rp. {{ number_format($data->average_order, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                        <td colspan="4">
                                <div class="d-flex flex-column align-items-center justify-content-center py-5">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">No sales data found.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesChart').getContext('2d');
        const chartMonths = @json($chartMonths);
        const chartData = @json($chartData);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartMonths.length > 0 ? chartMonths : ['No Data'],
                datasets: [{
                    label: 'Revenue',
                    data: chartData.length > 0 ? chartData : [0],
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
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
                    }
                }
            }
        });
    });
</script>
@endsection