<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function getStats()
    {
        try {
            $totalRevenue = Order::where('payment_status', 'Paid')
                ->where('order_status', 'delivered')
                ->sum('total');

            $pendingOrders = Order::where('approval_status', 'pending')->count();
            
            $pendingPayments = Order::whereIn('payment_status', ['Pending', 'Awaiting Payment'])->count();
            
            $totalBooks = Book::sum('stock');
            
            $totalUsers = User::where('is_admin', false)->count();

            return response()->json([
                'totalRevenue' => $totalRevenue,
                'pendingOrders' => $pendingOrders,
                'pendingPayments' => $pendingPayments,
                'totalBooks' => $totalBooks,
                'totalUsers' => $totalUsers,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'totalRevenue' => 0,
                'pendingOrders' => 0,
                'pendingPayments' => 0,
                'totalBooks' => 0,
                'totalUsers' => 0,
            ]);
        }
    }

    public function getRecentOrders()
    {
        try {
            $recentOrders = Order::with('user')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($order) {
                    return [
                        'id' => $order->id,
                        'order_number' => $order->order_number,
                        'user' => $order->user,
                        'userName' => $order->user->name ?? 'Unknown',
                        'created_at' => $order->created_at,
                        'total' => $order->total,
                        'payment_method' => $order->payment_method,
                        'payment_status' => $order->payment_status,
                    ];
                });

            return response()->json($recentOrders);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    public function getLowStock()
    {
        try {
            $lowStockBooks = Book::where('stock', '>', 0)
                ->where('stock', '<', 5)
                ->orderBy('stock', 'asc')
                ->get();

            return response()->json($lowStockBooks);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    public function getBestSellers()
    {
        try {
            $books = Book::withCount(['orderItems as sold_count' => function($query) {
                $query->whereHas('order', function($q) {
                    $q->where('order_status', 'delivered')
                      ->where('payment_status', 'Paid');
                });
            }])
            ->orderBy('sold_count', 'desc')
            ->limit(8)
            ->get()
            ->map(function($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'author' => $book->author,
                    'price' => $book->price,
                    'condition' => $book->condition,
                    'total_sold' => $book->sold_count,
                    'total_revenue' => $book->sold_count * $book->price,
                ];
            });

            return response()->json($books);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }
}