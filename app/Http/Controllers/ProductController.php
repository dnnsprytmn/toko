<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        // ===== TAMPILKAN PRODUK DENGAN STOK > 0 DAN BELUM SOLD OUT =====
        $products = Product::where('stock', '>', 0)
            ->where('is_active', true)
            ->whereDoesntHave('orderItems', function($q) {
                $q->whereHas('order', function($orderQ) {
                    $orderQ->where('status', 'completed');
                });
            });
        
        if ($search) {
            $products->where(function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
        
        $products = $products->get();
        
        return view('home', compact('products', 'search'));
    }

    // ===== TRACK ORDER =====
    public function trackOrder(Request $request)
    {
        $search = $request->get('search');
        $orderId = $request->get('order_id');
        
        $products = Product::where('stock', '>', 0)
            ->where('is_active', true)
            ->whereDoesntHave('orderItems', function($q) {
                $q->whereHas('order', function($orderQ) {
                    $orderQ->where('status', 'completed');
                });
            });
        
        if ($search) {
            $products->where(function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
        
        $products = $products->get();
        $order = null;
        
        // Cari order
        if ($orderId) {
            $order = Order::where('order_number', 'LIKE', "%{$orderId}%")
                ->orWhere('id', $orderId)
                ->first();
        }
        
        return view('home', compact('products', 'search', 'order'));
    }

    // ===== GET CART COUNT (AJAX) =====
    public function cartCount()
    {
        $cart = session()->get('cart', []);
        $count = 0;
        foreach ($cart as $item) {
            $count += $item['quantity'];
        }
        return response()->json(['count' => $count]);
    }

    // ===== TAMPILKAN PRODUK SOLD OUT =====
    public function soldOut()
    {
        // ===== PRODUK SOLD OUT =====
        // 1. Stock <= 0 (habis)
        // 2. ATAU produk yang sudah complete order-nya
        $products = Product::where(function($query) {
            $query->where('stock', '<=', 0)
                  ->orWhereHas('orderItems', function($q) {
                      $q->whereHas('order', function($orderQ) {
                          $orderQ->where('status', 'completed');
                      });
                  });
            })
            ->where('is_active', true)
            ->distinct()
            ->get();
        
        // Filter produk yang benar-benar sold out
        $soldOutProducts = $products->filter(function($product) {
            $soldQuantity = $product->orderItems()
                ->whereHas('order', function($q) {
                    $q->where('status', 'completed');
                })
                ->sum('quantity');
            
            // Jika stock <= 0 atau total sold >= stock awal, maka sold out
            return $product->stock <= 0 || $soldQuantity >= $product->stock;
        });
        
        $title = 'Sold Out Products';
        return view('shop', ['products' => $soldOutProducts, 'title' => $title]);
    }

    // ===== TAMPILKAN PRODUK DENGAN FILTER STOK =====
    public function filterByStock($status)
    {
        $title = '';
        $products = Product::where('is_active', true);
        
        switch ($status) {
            case 'available':
                $products->where('stock', '>', 0)
                         ->whereDoesntHave('orderItems', function($q) {
                             $q->whereHas('order', function($orderQ) {
                                 $orderQ->where('status', 'completed');
                             });
                         });
                $title = 'Available Products';
                break;
            case 'soldout':
                $products->where(function($query) {
                    $query->where('stock', '<=', 0)
                          ->orWhereHas('orderItems', function($q) {
                              $q->whereHas('order', function($orderQ) {
                                  $orderQ->where('status', 'completed');
                              });
                          });
                });
                $title = 'Sold Out Products';
                break;
            case 'lowstock':
                $products->where('stock', '>', 0)
                         ->where('stock', '<=', 5)
                         ->whereDoesntHave('orderItems', function($q) {
                             $q->whereHas('order', function($orderQ) {
                                 $orderQ->where('status', 'completed');
                             });
                         });
                $title = 'Low Stock Products';
                break;
            default:
                $products->where('stock', '>', 0)
                         ->whereDoesntHave('orderItems', function($q) {
                             $q->whereHas('order', function($orderQ) {
                                 $orderQ->where('status', 'completed');
                             });
                         });
                $title = 'All Products';
        }
        
        $products = $products->get();
        return view('shop', compact('products', 'title'));
    }

    public function show($id)
    {
        // ===== PERBAIKAN: Cek stok sebelum menampilkan detail =====
        $product = Product::where('id', $id)
            ->where('stock', '>', 0)
            ->where('is_active', true)
            ->whereDoesntHave('orderItems', function($q) {
                $q->whereHas('order', function($orderQ) {
                    $orderQ->where('status', 'completed');
                });
            })
            ->firstOrFail();
            
        return view('product-detail', compact('product'));
    }

    public function addToCart($id)
    {
        $product = Product::findOrFail($id);
        
        if ($product->stock <= 0) {
            return redirect()->back()->with('error', 'Product is out of stock!');
        }
        
        $cart = session()->get('cart', []);
        $quantity = 1;
        $isNew = true;
        
        if(isset($cart[$id])) {
            if ($cart[$id]['quantity'] + 1 > $product->stock) {
                return redirect()->back()->with('error', 'Not enough stock available. Available: ' . $product->stock);
            }
            $cart[$id]['quantity']++;
            $quantity = $cart[$id]['quantity'];
            $isNew = false;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->sale_price ?? $product->price,
                "image" => $product->image_url,
                "stock" => $product->stock
            ];
        }
        
        session()->put('cart', $cart);
        
        // Toast notification
        if ($isNew) {
            return redirect()->back()->with('toast', [
                'type' => 'cart_add',
                'product' => $product->name,
                'quantity' => 1
            ]);
        } else {
            return redirect()->back()->with('toast', [
                'type' => 'cart_update',
                'product' => $product->name,
                'quantity' => $quantity
            ]);
        }
    }

    public function removeFromCart($id)
    {
        $cart = session()->get('cart', []);
        $productName = '';
        
        if(isset($cart[$id])) {
            $productName = $cart[$id]['name'];
            unset($cart[$id]);
            session()->put('cart', $cart);
            
            return redirect()->back()->with('toast', [
                'type' => 'cart_remove',
                'product' => $productName
            ]);
        }
        
        return redirect()->back()->with('error', 'Product not found in cart');
    }

    public function cart()
    {
        return view('cart');
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }

    // ===== SHOP FILTER FUNCTIONS =====
    
    // All Products
    public function allProducts()
    {
        // ===== PERBAIKAN: Hanya produk dengan stok > 0 dan belum sold out =====
        $products = Product::where('stock', '>', 0)
            ->where('is_active', true)
            ->whereDoesntHave('orderItems', function($q) {
                $q->whereHas('order', function($orderQ) {
                    $orderQ->where('status', 'completed');
                });
            })
            ->get();
        $title = 'All Products';
        return view('shop', compact('products', 'title'));
    }

    // Popular Products
    public function popularProducts()
    {
        // ===== PERBAIKAN: Hanya produk dengan stok > 0 dan belum sold out =====
        $products = Product::where('is_popular', true)
            ->where('stock', '>', 0)
            ->where('is_active', true)
            ->whereDoesntHave('orderItems', function($q) {
                $q->whereHas('order', function($orderQ) {
                    $orderQ->where('status', 'completed');
                });
            })
            ->get();
        $title = 'Popular Items';
        return view('shop', compact('products', 'title'));
    }

    // New Products (terbaru)
    public function newProducts()
    {
        // ===== PERBAIKAN: Hanya produk dengan stok > 0 dan belum sold out =====
        $products = Product::where('stock', '>', 0)
            ->where('is_active', true)
            ->whereDoesntHave('orderItems', function($q) {
                $q->whereHas('order', function($orderQ) {
                    $orderQ->where('status', 'completed');
                });
            })
            ->latest()
            ->limit(12)
            ->get();
        $title = 'New Arrivals';
        return view('shop', compact('products', 'title'));
    }

    // Sale Products
    public function saleProducts()
    {
        // ===== PERBAIKAN: Hanya produk dengan stok > 0 dan belum sold out =====
        $products = Product::where('stock', '>', 0)
            ->where('is_active', true)
            ->whereDoesntHave('orderItems', function($q) {
                $q->whereHas('order', function($orderQ) {
                    $orderQ->where('status', 'completed');
                });
            })
            ->where(function($query) {
                $query->where('is_sale', true)
                      ->orWhereNotNull('sale_price');
            })
            ->get();
        $title = 'Sale Items';
        return view('shop', compact('products', 'title'));
    }

    // Products by Category (contoh)
    public function categoryProducts($category)
    {
        // ===== PERBAIKAN: Hanya produk dengan stok > 0 dan belum sold out =====
        $products = Product::where('stock', '>', 0)
            ->where('is_active', true)
            ->whereDoesntHave('orderItems', function($q) {
                $q->whereHas('order', function($orderQ) {
                    $orderQ->where('status', 'completed');
                });
            })
            ->where(function($query) use ($category) {
                $query->where('name', 'LIKE', "%{$category}%")
                      ->orWhere('description', 'LIKE', "%{$category}%");
            })
            ->get();
        $title = ucfirst($category);
        return view('shop', compact('products', 'title'));
    }

    // Search Product (AJAX)
    public function search(Request $request)
    {
        $query = $request->get('query');
        $products = collect();
        
        if ($query && strlen($query) >= 2) {
            // ===== PERBAIKAN: Hanya produk dengan stok > 0 dan belum sold out =====
            $products = Product::where('stock', '>', 0)
                ->where('is_active', true)
                ->whereDoesntHave('orderItems', function($q) {
                    $q->whereHas('order', function($orderQ) {
                        $orderQ->where('status', 'completed');
                    });
                })
                ->where(function($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                      ->orWhere('description', 'LIKE', "%{$query}%");
                })
                ->limit(10)
                ->get();
        }
        
        return response()->json($products);
    }

    // Search Order
    public function searchOrder(Request $request)
    {
        $query = $request->get('query');
        $order = null;
        
        if ($query && strlen($query) >= 3) {
            $order = Order::where('order_number', 'LIKE', "%{$query}%")
                ->orWhere('id', $query)
                ->first();
        }
        
        return response()->json($order);
    }

    public function orderResult($id)
    {
        try {
            $order = Order::findOrFail($id);
            return view('order-detail', compact('order'));
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Order not found!');
        }
    }

    // ===== UPDATE QUANTITY =====
    public function updateCart(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        
        if(isset($cart[$id])) {
            $product = Product::find($id);
            $quantity = $request->get('quantity', 1);
            
            // ===== PERBAIKAN: Validasi stok =====
            if ($product && $quantity > $product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not enough stock available. Available: ' . $product->stock
                ], 400);
            }
            
            // Minimum quantity adalah 1
            if($quantity < 1) {
                $quantity = 1;
            }
            
            // Maximum quantity adalah 100
            if($quantity > 100) {
                $quantity = 100;
            }
            
            $cart[$id]['quantity'] = $quantity;
            session()->put('cart', $cart);
            
            // Hitung ulang total
            $total = 0;
            foreach($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully',
                'subtotal' => $cart[$id]['price'] * $quantity,
                'total' => $total,
                'quantity' => $quantity
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Product not found in cart'
        ], 404);
    }

    public function getCartTotal()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return response()->json(['total' => $total]);
    }
}