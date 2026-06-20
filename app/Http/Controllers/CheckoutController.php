<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Mail\OrderConfirmationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('home')->with('error', 'Your cart is empty!');
        }
        
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        $subtotal = $total;
        // $shipping = 10000;
        $grandTotal = $subtotal;
        
        return view('checkout', compact('cart', 'subtotal', 'grandTotal'));
    }

    public function process(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'shipping_city' => 'required|string|max:100',
            'shipping_postal_code' => 'required|string|max:10',
            'payment_method' => 'required|in:bank_transfer,credit_card,e_wallet',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('home')->with('error', 'Your cart is empty!');
        }

        // ===== CEK STOK SEBELUM CHECKOUT =====
        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);
            if (!$product) {
                return redirect()->back()->with('error', 'Product not found: ' . $item['name']);
            }
            if ($product->stock < $item['quantity']) {
                return redirect()->back()->with('error', 'Insufficient stock for: ' . $product->name . ' (Available: ' . $product->stock . ')');
            }
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        // $shipping = 10000;
        $total = $subtotal;

        try {
            // ===== SIMPAN ORDER =====
            $order = Order::create([
                'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_postal_code' => $request->shipping_postal_code,
                'subtotal' => $subtotal,
                // 'shipping_cost' => $shipping,
                'total' => $total,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'payment_method' => $request->payment_method,
                'items' => json_encode($cart),
                'notes' => $request->notes,
            ]);

            // ===== SIMPAN ORDER ITEMS DAN KURANGI STOK =====
            foreach ($cart as $productId => $item) {
                // Simpan order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                // ===== KURANGI STOK PRODUK =====
                $product = Product::find($productId);
                if ($product) {
                    $product->stock -= $item['quantity'];
                    
                    // Pastikan stok tidak negatif
                    if ($product->stock < 0) {
                        $product->stock = 0;
                    }
                    
                    $product->save();
                    
                    // Log untuk debugging
                    Log::info('Stock updated for product: ' . $product->name . ' (New stock: ' . $product->stock . ')');
                }
            }

            // ===== KIRIM EMAIL =====
            try {
                Mail::to($request->customer_email)->send(new OrderConfirmationMail($order));
                Log::info('Email konfirmasi terkirim ke: ' . $request->customer_email);
            } catch (\Exception $e) {
                Log::error('Gagal mengirim email: ' . $e->getMessage());
            }

            session()->forget('cart');

            return redirect()->route('checkout.success', ['order' => $order->id])
                ->with('success', 'Order placed successfully! Stock has been updated.');
                
        } catch (\Exception $e) {
            Log::error('Checkout error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to place order: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function success(Order $order)
    {
        return view('checkout-success', compact('order'));
    }

    public function invoice(Order $order)
    {
        return view('invoice', compact('order'));
    }
}