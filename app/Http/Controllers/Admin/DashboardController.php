<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ===== TOTAL ADMIN =====
        $totalAdmins = Admin::count();
        
        // Statistik
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::sum('total') ?? 0;

        // ===== TOTAL REVENUE =====
        // Hitung total pendapatan dari order yang sudah completed atau paid
        $totalRevenue = Order::where('status', 'completed')
            ->orWhere('payment_status', 'paid')
            ->sum('total') ?? 0;
        
        
        // ===== DATA UNTUK CHART SALES =====
        // Ambil data penjualan per bulan (6 bulan terakhir)
        $monthlySales = Order::select(
            DB::raw('strftime("%Y-%m", created_at) as month'),
            DB::raw('SUM(total) as total')
        )
        ->where('status', '!=', 'cancelled')
        ->groupBy('month')
        ->orderBy('month', 'ASC')
        ->limit(6)
        ->get();
        
        // Jika tidak ada data, buat data dummy
        if ($monthlySales->isEmpty()) {
            $chartMonths = [];
            $chartSales = [];
            for ($i = 5; $i >= 0; $i--) {
                $chartMonths[] = date('M Y', strtotime("-$i months"));
                $chartSales[] = 0;
            }
        } else {
            $chartMonths = [];
            $chartSales = [];
            foreach ($monthlySales as $sale) {
                $chartMonths[] = date('M Y', strtotime($sale->month . '-01'));
                $chartSales[] = (float) $sale->total;
            }
        }
        
        // ===== DATA UNTUK CHART ORDER STATUS =====
        $orderStatusData = [
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];
        
        $statusLabels = ['Pending', 'Processing', 'Shipped', 'Completed', 'Cancelled'];
        $statusColors = ['#ffc107', '#17a2b8', '#0d6efd', '#28a745', '#dc3545'];
        $statusData = array_values($orderStatusData);
        
        // Recent orders
        $recentOrders = Order::latest()->take(5)->get();
        
        // Top products
        $topProducts = Product::orderBy('id', 'DESC')->take(5)->get();
        
        // Recent products
        $recentProducts = Product::latest()->take(5)->get();
        
        return view('admin.dashboard', compact(
            'totalProducts',
            'totalOrders', 
            'totalRevenue',
            'totalAdmins',
            'recentOrders',
            'topProducts',
            'recentProducts',
            'chartMonths',
            'chartSales',
            'statusLabels',
            'statusColors',
            'statusData'
        ));
    }
}