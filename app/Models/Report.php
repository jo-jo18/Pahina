<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_type',
        'report_date',
        'total_revenue',
        'total_orders',
        'total_books_sold',
        'average_order_value',
        'top_books',
        'category_breakdown',
    ];

    protected $casts = [
        'report_date' => 'date',
        'total_revenue' => 'decimal:2',
        'average_order_value' => 'decimal:2',
        'top_books' => 'array',
        'category_breakdown' => 'array',
    ];

    public function scopeOfType($query, $type)
    {
        return $query->where('report_type', $type);
    }

    public function scopeBetweenDates($query, $start, $end)
    {
        return $query->whereBetween('report_date', [$start, $end]);
    }
}