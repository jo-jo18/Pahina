<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Book;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicController extends Controller
{
    public function getBestSellers()
    {
        try {
            $bestSellers = OrderItem::select(
                'book_id',
                'title',
                'author',
                'price',
                'condition',
                DB::raw('SUM(quantity) as total_sold'),
                DB::raw('SUM(quantity * price) as total_revenue')
            )
            ->with(['book' => function($query) {
                $query->select('id', 'isbn', 'image', 'stock');
            }])
            ->whereHas('order', function($query) {
                $query->where('payment_status', 'Paid')
                      ->where('approval_status', 'approved');
            })
            ->groupBy('book_id', 'title', 'author', 'price', 'condition')
            ->orderBy('total_sold', 'desc')
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->book_id,
                    'isbn' => $item->book ? $item->book->isbn : null,
                    'title' => $item->title,
                    'author' => $item->author,
                    'price' => $item->price,
                    'condition' => $item->condition,
                    'total_sold' => $item->total_sold,
                    'total_revenue' => $item->total_revenue,
                    'image' => $item->book ? $item->book->image : null,
                    'stock' => $item->book ? $item->book->stock : 0
                ];
            });
            
            return response()->json($bestSellers);
        } catch (\Exception $e) {
            \Log::error('Error fetching best sellers: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load best sellers'], 500);
        }
    }
    
    public function getRecentOrders()
    {
        try {
            $recentOrders = Order::with(['user', 'items'])
                ->where('payment_status', 'Paid')
                ->where('approval_status', 'approved')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($order) {
                    return [
                        'order_number' => $order->order_number,
                        'user' => [
                            'name' => $order->user ? $order->user->name : 'Guest'
                        ],
                        'created_at' => $order->created_at,
                        'total' => $order->total,
                        'payment_method' => $order->payment_method,
                        'payment_status' => $order->payment_status
                    ];
                });
            
            return response()->json($recentOrders);
        } catch (\Exception $e) {
            \Log::error('Error fetching recent orders: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load recent orders'], 500);
        }
    }
    
    public function getLowStock()
    {
        try {
            $lowStock = Book::where('stock', '<=', 5)
                ->orderBy('stock', 'asc')
                ->limit(5)
                ->get();
            
            return response()->json($lowStock);
        } catch (\Exception $e) {
            \Log::error('Error fetching low stock: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load low stock items'], 500);
        }
    }
}