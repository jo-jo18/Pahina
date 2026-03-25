<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'isbn',
        'title',
        'author',
        'price',
        'stock',
        'synopsis',
        'condition',
        'image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    public function wishlistedBy()
    {
        return $this->belongsToMany(User::class, 'wishlists')->withTimestamps();
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStockStatusAttribute()
    {
        if ($this->stock > 10) return 'in-stock';
        if ($this->stock > 0) return 'low-stock';
        return 'out-of-stock';
    }

    public function getStockTextAttribute()
    {
        if ($this->stock > 10) return 'In Stock';
        if ($this->stock > 0) return "Only {$this->stock} left";
        return 'Out of Stock';
    }

    public function getTotalSoldAttribute()
    {
        return $this->orderItems()
            ->whereHas('order', function($query) {
                $query->where('order_status', 'delivered')
                      ->where('payment_status', 'Paid');
            })
            ->sum('quantity');
    }

    public function getTotalRevenueAttribute()
    {
        return $this->orderItems()
            ->whereHas('order', function($query) {
                $query->where('order_status', 'delivered')
                      ->where('payment_status', 'Paid');
            })
            ->get()
            ->sum(function($item) {
                return $item->quantity * $item->price;
            });
    }
}