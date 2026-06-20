<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'sale_price',
        'stock',
        'is_active',
        'image_url',
        'rating',
        'is_sale',
        'is_popular',
        'is_special'
    ];

    // ===== RELATIONSHIPS =====
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    // ===== SCOPES =====
    // Scope untuk produk yang aktif dan stok > 0
    public function scopeAvailable($query)
    {
        return $query->where('is_active', true)
                     ->where('stock', '>', 0);
    }

    // Scope untuk produk yang stoknya habis
    public function scopeOutOfStock($query)
    {
        return $query->where('stock', '<=', 0);
    }

    // ===== ACCESSORS =====
    public function getImageUrlAttribute($value)
    {
        if ($value && !filter_var($value, FILTER_VALIDATE_URL)) {
            return asset('storage/' . $value);
        }
        return $value ?? 'https://dummyimage.com/450x300/dee2e6/6c757d.jpg';
    }

    public function getStockStatusAttribute()
    {
        if ($this->stock <= 0) {
            return 'out_of_stock';
        } elseif ($this->stock <= 5) {
            return 'low_stock';
        } else {
            return 'in_stock';
        }
    }

    public function getStockLabelAttribute()
    {
        $labels = [
            'out_of_stock' => '<span class="badge bg-danger">Out of Stock</span>',
            'low_stock' => '<span class="badge bg-warning text-dark">Low Stock</span>',
            'in_stock' => '<span class="badge bg-success">In Stock</span>'
        ];
        return $labels[$this->stock_status] ?? '';
    }

    // ===== FUNCTIONS =====
    public function reduceStock($quantity)
    {
        if ($this->stock >= $quantity) {
            $this->stock -= $quantity;
            $this->save();
            return true;
        }
        return false;
    }

    public function increaseStock($quantity)
    {
        $this->stock += $quantity;
        $this->save();
        return true;
    }

    // ===== BOOT METHOD =====
    protected static function boot()
    {
        parent::boot();

        // ===== HAPUS GAMBAR SAAT PRODUCT DIHAPUS =====
        static::deleting(function ($product) {
            if ($product->image_url && !filter_var($product->image_url, FILTER_VALIDATE_URL)) {
                if (Storage::disk('public')->exists($product->image_url)) {
                    Storage::disk('public')->delete($product->image_url);
                }
            }
        });
    }
}