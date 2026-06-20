<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Products Report
     */
    public function products(Request $request)
    {
        $sort = $request->get('sort', 'most_sold');
        $search = $request->get('search');
        $perPage = $request->get('per_page', 10);

        // ===== QUERY PRODUK DENGAN PAGINATION =====
        $query = Product::query();
        
        // ===== FILTER SEARCH =====
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
        
        // ===== SORT =====
        switch ($sort) {
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'highest_price':
                $query->orderBy('price', 'desc');
                break;
            case 'lowest_price':
                $query->orderBy('price', 'asc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->orderBy('id', 'desc');
        }
        
        // ===== PAGINATE =====
        $products = $query->paginate($perPage);
        $products->appends(['search' => $search, 'sort' => $sort, 'per_page' => $perPage]);
        
        // ===== HITUNG UNITS SOLD UNTUK SETIAP PRODUK (HANYA COMPLETED) =====
        foreach ($products as $product) {
            // Hanya menghitung order dengan status COMPLETED
            $product->units_sold = OrderItem::where('product_id', $product->id)
                ->whereHas('order', function($q) {
                    $q->where('status', 'completed');
                })
                ->sum('quantity') ?? 0;
            
            // Hanya menghitung revenue dari order dengan status COMPLETED
            $product->total_revenue = OrderItem::where('product_id', $product->id)
                ->whereHas('order', function($q) {
                    $q->where('status', 'completed');
                })
                ->sum(DB::raw('quantity * price')) ?? 0;
            
            $product->average_price = $product->units_sold > 0 
                ? ($product->total_revenue / $product->units_sold) 
                : 0;
        }
        
        // ===== TOP 10 BEST SELLER (HANYA COMPLETED) =====
        $topProducts = Product::all()->map(function($product) {
            $product->units_sold = OrderItem::where('product_id', $product->id)
                ->whereHas('order', function($q) {
                    $q->where('status', 'completed');
                })
                ->sum('quantity') ?? 0;
            
            $product->total_revenue = OrderItem::where('product_id', $product->id)
                ->whereHas('order', function($q) {
                    $q->where('status', 'completed');
                })
                ->sum(DB::raw('quantity * price')) ?? 0;
            
            return $product;
        })->sortByDesc('units_sold')->take(10);
        
        // ===== STATISTICS (HANYA COMPLETED) =====
        $totalSold = OrderItem::whereHas('order', function($q) {
            $q->where('status', 'completed');
        })->sum('quantity') ?? 0;
        
        $totalRevenue = OrderItem::whereHas('order', function($q) {
            $q->where('status', 'completed');
        })->sum(DB::raw('quantity * price')) ?? 0;
        
        $stats = [
            'total_products' => Product::count(),
            'total_sold' => $totalSold,
            'total_revenue' => $totalRevenue,
            'average_price' => Product::avg('price') ?? 0,
        ];
        
        // ===== CHART DATA =====
        $chartProducts = $topProducts->take(7);
        $chartLabels = [];
        $chartData = [];
        $chartColors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#6f42c1'];
        
        foreach ($chartProducts as $index => $product) {
            $chartLabels[] = $product->name;
            $chartData[] = $product->units_sold ?? 0;
        }
        
        return view('admin.reports.products', compact(
            'products',
            'sort',
            'search',
            'topProducts',
            'stats',
            'chartLabels',
            'chartData',
            'chartColors',
            'perPage'
        ));
    }

    /**
     * Sales Report
     */
    public function sales(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        
        // ===== SALES DATA (HANYA COMPLETED) =====
        $salesData = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total_orders'),
            DB::raw('SUM(total) as total_revenue'),
            DB::raw('AVG(total) as average_order')
        )
        ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
        ->where('status', 'completed')
        ->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get();
        
        // ===== SUMMARY (HANYA COMPLETED) =====
        $summary = [
            'total_orders' => Order::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->where('status', 'completed')
                ->count(),
            'total_revenue' => Order::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->where('status', 'completed')
                ->sum('total') ?? 0,
            'average_order' => Order::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->where('status', 'completed')
                ->avg('total') ?? 0,
            'total_customers' => Order::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->where('status', 'completed')
                ->distinct('customer_email')
                ->count('customer_email'),
        ];
        
        // ===== MONTHLY SALES (HANYA COMPLETED) =====
        $monthlySales = Order::select(
            DB::raw('strftime("%Y-%m", created_at) as month'),
            DB::raw('SUM(total) as total')
        )
        ->where('status', 'completed')
        ->groupBy('month')
        ->orderBy('month', 'ASC')
        ->limit(12)
        ->get();
        
        $chartMonths = [];
        $chartData = [];
        foreach ($monthlySales as $sale) {
            $chartMonths[] = date('M Y', strtotime($sale->month . '-01'));
            $chartData[] = (float) $sale->total;
        }
        
        return view('admin.reports.sales', compact(
            'salesData',
            'summary',
            'startDate',
            'endDate',
            'chartMonths',
            'chartData'
        ));
    }

    /**
     * Customers Report
     */
    public function customers(Request $request)
    {
        $sort = $request->get('sort', 'most_orders');
        $search = $request->get('search');
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $perPage = $request->get('per_page', 15);

        // ===== QUERY CUSTOMERS (HANYA COMPLETED) =====
        $query = Order::select(
            'customer_name',
            'customer_email',
            'customer_phone',
            DB::raw('COUNT(*) as total_orders'),
            DB::raw('SUM(total) as total_spent'),
            DB::raw('AVG(total) as average_order'),
            DB::raw('MAX(created_at) as last_order'),
            DB::raw('MIN(created_at) as first_order'),
            DB::raw('GROUP_CONCAT(DISTINCT status) as order_statuses'),
            DB::raw('GROUP_CONCAT(DISTINCT payment_method) as payment_methods')
        )
        ->whereNotNull('customer_email')
        ->where('status', 'completed')
        ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

        // ===== SEARCH =====
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'LIKE', "%{$search}%")
                  ->orWhere('customer_email', 'LIKE', "%{$search}%")
                  ->orWhere('customer_phone', 'LIKE', "%{$search}%");
            });
        }

        // ===== GROUP BY =====
        $query->groupBy('customer_name', 'customer_email', 'customer_phone');

        // ===== SORT =====
        switch ($sort) {
            case 'most_orders':
                $query->orderBy('total_orders', 'DESC');
                break;
            case 'most_spent':
                $query->orderBy('total_spent', 'DESC');
                break;
            case 'newest':
                $query->orderBy('last_order', 'DESC');
                break;
            case 'oldest':
                $query->orderBy('first_order', 'ASC');
                break;
            case 'name_asc':
                $query->orderBy('customer_name', 'ASC');
                break;
            case 'name_desc':
                $query->orderBy('customer_name', 'DESC');
                break;
            default:
                $query->orderBy('total_orders', 'DESC');
        }

        $customers = $query->paginate($perPage);
        $customers->appends(['search' => $search, 'sort' => $sort, 'start_date' => $startDate, 'end_date' => $endDate, 'per_page' => $perPage]);

        // ===== STATISTICS (HANYA COMPLETED) =====
        $totalCustomers = Order::whereNotNull('customer_email')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->distinct('customer_email')
            ->count('customer_email');

        $totalOrders = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->count();
            
        $totalRevenue = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->sum('total') ?? 0;
            
        $averageSpent = $totalCustomers > 0 ? $totalRevenue / $totalCustomers : 0;

        // ===== TOP 10 CUSTOMERS (HANYA COMPLETED) =====
        $topCustomers = Order::select(
            'customer_name',
            'customer_email',
            DB::raw('COUNT(*) as total_orders'),
            DB::raw('SUM(total) as total_spent'),
            DB::raw('MAX(created_at) as last_order')
        )
        ->whereNotNull('customer_email')
        ->where('status', 'completed')
        ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
        ->groupBy('customer_name', 'customer_email')
        ->orderBy('total_spent', 'DESC')
        ->limit(10)
        ->get();

        // ===== CHART DATA =====
        $chartCustomers = $topCustomers->take(7);
        $chartLabels = [];
        $chartData = [];
        $chartColors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#6f42c1'];

        foreach ($chartCustomers as $index => $customer) {
            $chartLabels[] = $customer->customer_name ?? 'Guest';
            $chartData[] = $customer->total_spent ?? 0;
        }

        return view('admin.reports.customers', compact(
            'customers',
            'topCustomers',
            'totalCustomers',
            'totalOrders',
            'totalRevenue',
            'averageSpent',
            'sort',
            'search',
            'startDate',
            'endDate',
            'chartLabels',
            'chartData',
            'chartColors',
            'perPage'
        ));
    }

    /**
     * Export Report
     */
    public function export($type, Request $request)
    {
        return redirect()->back()->with('info', 'Export feature coming soon!');
    }
}