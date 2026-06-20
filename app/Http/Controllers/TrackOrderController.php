<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class TrackOrderController extends Controller
{
    /**
     * Display track order page.
     */
    public function index(Request $request)
    {
        $order = null;
        $searched = false;
        $orderId = $request->get('order_id');
        $notFound = false;
        
        if ($orderId) {
            $order = Order::where('order_number', 'LIKE', "%{$orderId}%")
                ->orWhere('id', $orderId)
                ->first();
            
            $searched = true;
            
            if (!$order) {
                $notFound = true;
            }
        }
        
        return view('track-order', compact('order', 'searched', 'orderId', 'notFound'));
    }

    /**
     * Search order by ID.
     */
    public function search(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string|max:255',
        ]);

        $orderId = $request->get('order_id');
        
        // Cari order berdasarkan order_number atau ID
        $order = Order::where('order_number', 'LIKE', "%{$orderId}%")
            ->orWhere('id', $orderId)
            ->first();

        // Redirect dengan parameter agar link aktif
        return redirect()->route('track.order', ['order_id' => $orderId]);
    }
}