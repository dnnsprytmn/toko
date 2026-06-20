<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RevenueController extends Controller
{
    /**
     * Display a listing of revenue.
     */
    public function index()
    {
        // Total revenue
        $totalRevenue = Order::where('status', 'completed')
            ->orWhere('payment_status', 'paid')
            ->sum('total') ?? 0;
        
        // ===== REVENUE PER MONTH (LAST 12 MONTHS) =====
        $monthlyRevenue = Order::select(
            DB::raw('strftime("%Y-%m", created_at) as month'),
            DB::raw('SUM(total) as total')
        )
        ->where('status', '!=', 'cancelled')
        ->groupBy('month')
        ->orderBy('month', 'ASC')
        ->limit(12)
        ->get();
        
        // Jika tidak ada data, buat data dummy dari bulan-bulan terakhir
        if ($monthlyRevenue->isEmpty()) {
            $monthlyRevenue = collect();
            for ($i = 11; $i >= 0; $i--) {
                $month = date('Y-m', strtotime("-$i months"));
                $monthlyRevenue->push((object) [
                    'month' => $month,
                    'total' => 0
                ]);
            }
        }
        
        // Format untuk chart
        $chartLabels = [];
        $chartData = [];
        foreach ($monthlyRevenue as $item) {
            $monthName = date('M Y', strtotime($item->month . '-01'));
            $chartLabels[] = $monthName;
            $chartData[] = (float) $item->total;
        }
        
        // Revenue by payment method
        $revenueByPayment = Order::select(
            'payment_method',
            DB::raw('SUM(total) as total')
        )
        ->where('payment_status', 'paid')
        ->groupBy('payment_method')
        ->get();
        
        // Recent revenue transactions
        $recentTransactions = Order::where('status', 'completed')
            ->orWhere('payment_status', 'paid')
            ->latest()
            ->limit(10)
            ->get();
        
        // Revenue statistics
        $stats = [
            'today' => Order::whereDate('created_at', today())
                ->where('status', 'completed')
                ->sum('total') ?? 0,
            'this_week' => Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->where('status', 'completed')
                ->sum('total') ?? 0,
            'this_month' => Order::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->where('status', 'completed')
                ->sum('total') ?? 0,
            'this_year' => Order::whereYear('created_at', now()->year)
                ->where('status', 'completed')
                ->sum('total') ?? 0,
        ];
        
        return view('admin.revenue.index', compact(
            'totalRevenue',
            'monthlyRevenue',
            'chartLabels',
            'chartData',
            'revenueByPayment',
            'recentTransactions',
            'stats'
        ));
    }

    /**
     * Display revenue detail with filters.
     */
    public function detail(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $paymentMethod = $request->get('payment_method', 'all');
        $status = $request->get('status', 'all');
        
        $query = Order::where(function($q) {
            $q->where('status', 'completed')
              ->orWhere('payment_status', 'paid');
        });
        
        // Filter by date range
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }
        
        // Filter by payment method
        if ($paymentMethod != 'all') {
            $query->where('payment_method', $paymentMethod);
        }
        
        // Filter by status
        if ($status != 'all') {
            $query->where('status', $status);
        }
        
        $transactions = $query->latest()->paginate(15);
        $totalFiltered = $query->sum('total') ?? 0;
        
        // Get payment methods for filter
        $paymentMethods = Order::distinct()->pluck('payment_method')->filter()->values();
        $statuses = ['pending', 'processing', 'shipped', 'completed', 'cancelled'];
        
        return view('admin.revenue.detail', compact(
            'transactions',
            'totalFiltered',
            'startDate',
            'endDate',
            'paymentMethod',
            'status',
            'paymentMethods',
            'statuses'
        ));
    }
}