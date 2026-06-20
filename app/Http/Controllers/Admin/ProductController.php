<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        
        $search = $request->get('search');
        $sort = $request->get('sort', 'latest');
        $status = $request->get('status', 'all'); 
        
        $query = Product::query();
        
        // Filter by status jika ada
        if ($status != 'all') {
            if ($status == 'active') {
                $query->where('is_active', true);
            } elseif ($status == 'inactive') {
                $query->where('is_active', false);
            } elseif ($status == 'out_of_stock') {
                $query->where('stock', '<=', 0);
            } elseif ($status == 'low_stock') {
                $query->where('stock', '>', 0)->where('stock', '<=', 5);
            }
        }
        
        // Fungsi Search
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('price', 'LIKE', "%{$search}%");
            });
        }
        
         // Fungsi Sorting
        switch ($sort) {
            case 'latest':
                $query->latest();
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->latest();
        }
        
        $products = $query->paginate(10);
        $products->appends(['search' => $search, 'sort' => $sort, 'status' => $status]);
        
        // Count untuk filter
        $counts = [
            'all' => Product::count(),
            'active' => Product::where('is_active', true)->count(),
            'inactive' => Product::where('is_active', false)->count(),
            'out_of_stock' => Product::where('stock', '<=', 0)->count(),
            'low_stock' => Product::where('stock', '>', 0)->where('stock', '<=', 5)->count(),
        ];
        
        return view('admin.products.index', compact('products', 'search', 'sort', 'status', 'counts'));
    }

    // Method untuk search via navbar (AJAX)
    public function search(Request $request)
    {
        $query = $request->get('query', '');
        $products = [];
        
        if (strlen($query) >= 2) {
            $products = Product::where('name', 'LIKE', "%{$query}%")
                ->orWhere('description', 'LIKE', "%{$query}%")
                ->limit(10)
                ->get(['id', 'name', 'price', 'image_url']);
        }
        
        return response()->json($products);
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        $data = $request->only([
            'name', 
            'description', 
            'price', 
            'sale_price', 
            'rating'
        ]);

        // ===== SET STOCK (Bisa 0) =====
        $data['stock'] = $request->has('stock') && $request->stock !== null 
            ? $request->stock 
            : 1;

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . Str::slug($request->name) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('products', $filename, 'public');
            $data['image_url'] = $path;
        } else {
            $data['image_url'] = 'https://dummyimage.com/450x300/dee2e6/6c757d.jpg';
        }

        $data['is_sale'] = !empty($data['sale_price']);
        $data['is_popular'] = $request->has('is_popular') ? true : false;
        $data['is_special'] = $request->has('is_special') ? true : false;
        $data['is_active'] = $request->has('is_active') ? true : false;

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully with stock: ' . $data['stock']);
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        $data = $request->only([
            'name', 
            'description', 
            'price', 
            'sale_price', 
            'rating'
        ]);

        // ===== UPDATE STOCK =====
        if ($request->has('stock') && $request->stock !== null) {
            $data['stock'] = $request->stock;
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // ===== HAPUS GAMBAR LAMA JIKA ADA =====
            if ($product->image_url && !filter_var($product->image_url, FILTER_VALIDATE_URL)) {
                if (Storage::disk('public')->exists($product->image_url)) {
                    Storage::disk('public')->delete($product->image_url);
                }
            }

            $image = $request->file('image');
            $filename = time() . '_' . Str::slug($request->name) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('products', $filename, 'public');
            $data['image_url'] = $path;
        }

        $data['is_sale'] = !empty($data['sale_price']);
        $data['is_popular'] = $request->has('is_popular') ? true : false;
        $data['is_special'] = $request->has('is_special') ? true : false;
        $data['is_active'] = $request->has('is_active') ? true : false;

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully');
    }

    // ===== PERBAIKAN: DELETE PRODUCT DENGAN HAPUS FILE GAMBAR =====
    public function destroy(Product $product)
    {
        // ===== HAPUS GAMBAR DARI FOLDER STORAGE =====
        $this->deleteProductImage($product);

        // ===== HAPUS DATA DARI DATABASE =====
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully! Image file also removed.');
    }

    // ===== METHOD UNTUK HAPUS GAMBAR =====
    private function deleteProductImage($product)
    {
        if (!$product->image_url) {
            return;
        }

        // Cek apakah URL adalah URL eksternal (bukan file lokal)
        if (filter_var($product->image_url, FILTER_VALIDATE_URL)) {
            return;
        }

        // Hapus file dari storage
        if (Storage::disk('public')->exists($product->image_url)) {
            Storage::disk('public')->delete($product->image_url);
        }

        // Coba juga cek di path langsung jika menggunakan storage link
        $filePath = public_path('storage/' . $product->image_url);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    // ===== DELETE IMAGE =====
    public function deleteImage(Product $product)
    {
        if (!$product->image_url || filter_var($product->image_url, FILTER_VALIDATE_URL)) {
            return redirect()->back()
                ->with('error', 'Cannot delete external image URL');
        }

        // Hapus file
        if (Storage::disk('public')->exists($product->image_url)) {
            Storage::disk('public')->delete($product->image_url);
        }

        // Update ke default
        $product->update(['image_url' => 'https://dummyimage.com/450x300/dee2e6/6c757d.jpg']);

        return redirect()->back()
            ->with('success', 'Product image deleted successfully!');
    }
}