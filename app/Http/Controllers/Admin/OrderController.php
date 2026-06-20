<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $search = $request->get('search');
        
        $query = Order::query();
        
        // Filter by status
        if ($status != 'all') {
            $query->where('status', $status);
        }
        
        // Search
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'LIKE', "%{$search}%")
                  ->orWhere('customer_name', 'LIKE', "%{$search}%")
                  ->orWhere('customer_email', 'LIKE', "%{$search}%");
            });
        }
        
        $orders = $query->latest()->paginate(10);
        $orders->appends(['status' => $status, 'search' => $search]);
        
        // Count by status
        $counts = [
            'all' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];
        
        return view('admin.orders.index', compact('orders', 'status', 'search', 'counts'));
    }

    public function show(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled',
        ]);
        
        $oldStatus = $order->status;
        $newStatus = $request->status;
        
        DB::beginTransaction();
        
        try {
            // ===== JIKA STATUS DIUBAH MENJADI CANCELLED =====
            // Kembalikan stok jika order dibatalkan
            if ($newStatus == 'cancelled' && $oldStatus != 'cancelled') {
                $orderItems = OrderItem::where('order_id', $order->id)->get();
                foreach ($orderItems as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->stock += $item->quantity;
                        $product->save();
                        Log::info('Stock restored for product: ' . $product->name . ' (New stock: ' . $product->stock . ')');
                    }
                }
            }
            
            // ===== JIKA STATUS DIUBAH DARI CANCELLED MENJADI LAINNYA =====
            // Kurangi stok lagi jika order dibatalkan lalu diaktifkan kembali
            if ($oldStatus == 'cancelled' && $newStatus != 'cancelled') {
                $orderItems = OrderItem::where('order_id', $order->id)->get();
                foreach ($orderItems as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        if ($product->stock >= $item->quantity) {
                            $product->stock -= $item->quantity;
                        } else {
                            $product->stock = 0;
                        }
                        $product->save();
                        Log::info('Stock reduced for product: ' . $product->name . ' (New stock: ' . $product->stock . ')');
                    }
                }
            }
            
            // Update status order
            $order->update(['status' => $newStatus]);
            
            // Jika status diubah menjadi completed, update payment status
            if ($newStatus == 'completed') {
                $order->update(['payment_status' => 'paid']);
            }
            
            DB::commit();
            
            $message = 'Order status updated successfully!';
            if ($newStatus == 'cancelled' && $oldStatus != 'cancelled') {
                $message .= ' Stock has been restored.';
            }
            
            return redirect()->back()->with('success', $message);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating order status: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update order status: ' . $e->getMessage());
        }
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:unpaid,paid,refunded',
        ]);
        
        $order->update(['payment_status' => $request->payment_status]);
        
        return redirect()->back()->with('success', 'Payment status updated successfully');
    }

    public function destroy(Order $order)
    {
        DB::beginTransaction();
        
        try {
            // ===== KEMBALIKAN STOK SAAT ORDER DIHAPUS =====
            $orderItems = OrderItem::where('order_id', $order->id)->get();
            foreach ($orderItems as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->stock += $item->quantity;
                    $product->save();
                    Log::info('Stock restored for deleted order: ' . $product->name . ' (New stock: ' . $product->stock . ')');
                }
            }
            
            $order->delete();
            
            DB::commit();
            
            return redirect()->route('admin.orders.index')
                ->with('success', 'Order deleted successfully! Stock has been restored.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting order: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete order: ' . $e->getMessage());
        }
    }

    public function print(Order $order)
    {
        return view('admin.orders.print', compact('order'));
    }
}