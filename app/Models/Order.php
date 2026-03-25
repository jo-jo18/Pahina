<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'subtotal',
        'shipping_fee',
        'total',
        'payment_method',
        'payment_status',
        'order_status',
        'approval_status',
        'recipient_name',
        'delivery_address',
        'contact_number',
        'delivery_instructions',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($order) {
            $order->order_number = 'ORD-' . strtoupper(uniqid());
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function paymentDetails()
    {
        return $this->hasOne(PaymentDetail::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function getStatusColorAttribute()
    {
        return match($this->order_status) {
            'delivered' => 'green',
            'shipped' => 'blue',
            'processing' => 'yellow',
            'cancelled' => 'red',
            default => 'gray',
        };
    }

    public function getPaymentStatusColorAttribute()
    {
        return match($this->payment_status) {
            'Paid' => 'green',
            'Awaiting Payment' => 'yellow',
            'Failed' => 'red',
            default => 'gray',
        };
    }
}