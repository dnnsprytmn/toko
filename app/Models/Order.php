<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'shipping_city',
        'shipping_postal_code',
        'subtotal',
        'tax',
        'shipping_cost',
        'total',
        'status',
        'payment_status',
        'payment_method',
        'items',
        'notes'
    ];

    protected $casts = [
        'items' => 'array',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // ===== RELATIONSHIPS =====
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    // Generate order number automatically
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->order_number = 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());
        });
    }

    // Accessor untuk status badge
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'processing' => 'info',
            'shipped' => 'primary',
            'completed' => 'success',
            'cancelled' => 'danger'
        ];
        
        return $badges[$this->status] ?? 'secondary';
    }

    // Accessor untuk payment status badge
    public function getPaymentStatusBadgeAttribute()
    {
        $badges = [
            'unpaid' => 'warning',
            'paid' => 'success',
            'refunded' => 'danger'
        ];
        
        return $badges[$this->payment_status] ?? 'secondary';
    }

    // Scope untuk filter status
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}