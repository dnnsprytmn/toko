<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $sort = $request->get('sort', 'latest');
        
        // Get unique customers from orders
        $query = Order::select(
            'customer_name',
            'customer_email',
            'customer_phone',
            DB::raw('MAX(shipping_address) as shipping_address'),
            DB::raw('MAX(shipping_city) as shipping_city'),
            DB::raw('COUNT(*) as total_orders'),
            DB::raw('SUM(total) as total_spent'),
            DB::raw('MAX(created_at) as last_order'),
            DB::raw('MIN(created_at) as first_order')
        )
        ->whereNotNull('customer_email')
        ->groupBy('customer_name', 'customer_email', 'customer_phone');
        
        // Search
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'LIKE', "%{$search}%")
                  ->orWhere('customer_email', 'LIKE', "%{$search}%")
                  ->orWhere('customer_phone', 'LIKE', "%{$search}%");
            });
        }
        
        // Sort
        switch ($sort) {
            case 'latest':
                $query->orderBy('last_order', 'DESC');
                break;
            case 'oldest':
                $query->orderBy('last_order', 'ASC');
                break;
            case 'most_orders':
                $query->orderBy('total_orders', 'DESC');
                break;
            case 'most_spent':
                $query->orderBy('total_spent', 'DESC');
                break;
            case 'name_asc':
                $query->orderBy('customer_name', 'ASC');
                break;
            case 'name_desc':
                $query->orderBy('customer_name', 'DESC');
                break;
            default:
                $query->orderBy('last_order', 'DESC');
        }
        
        $customers = $query->paginate(15);
        $customers->appends(['search' => $search, 'sort' => $sort]);
        
        // Stats
        // Total unique customers dari seluruh data
        $totalCustomers = Order::whereNotNull('customer_email')
            ->distinct('customer_email')
            ->count('customer_email');
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'completed')
            ->orWhere('payment_status', 'paid')
            ->sum('total') ?? 0;
        $averageSpent = $totalCustomers > 0 ? $totalRevenue / $totalCustomers : 0;
        
        return view('admin.customers.index', compact(
            'customers', 
            'search', 
            'sort',
            'totalCustomers',
            'totalOrders',
            'totalRevenue',
            'averageSpent'
        ));
    }

    /**
     * Display customer details.
     */
    public function show($email)
    {
        // Get customer data
        $customer = Order::select(
            'customer_name',
            'customer_email',
            'customer_phone',
            DB::raw('MAX(shipping_address) as shipping_address'),
            DB::raw('MAX(shipping_city) as shipping_city'),
            DB::raw('COUNT(*) as total_orders'),
            DB::raw('SUM(total) as total_spent'),
            DB::raw('MAX(created_at) as last_order'),
            DB::raw('MIN(created_at) as first_order'),
            DB::raw('GROUP_CONCAT(DISTINCT payment_method) as payment_methods')
        )
        ->where('customer_email', $email)
        ->groupBy('customer_name', 'customer_email', 'customer_phone')
        ->first();
        
        if (!$customer) {
            return redirect()->route('admin.customers.index')
                ->with('error', 'Customer not found');
        }
        
        // Get customer orders
        $orders = Order::where('customer_email', $email)
            ->latest()
            ->paginate(10);
        
        // Get order status distribution
        $statusDistribution = Order::where('customer_email', $email)
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();
        
        return view('admin.customers.show', compact('customer', 'orders', 'statusDistribution'));
    }

    /**
     * Get customer orders (AJAX).
     */
    public function orders($email)
    {
        $orders = Order::where('customer_email', $email)
            ->latest()
            ->get();
        
        return response()->json($orders);
    }
}