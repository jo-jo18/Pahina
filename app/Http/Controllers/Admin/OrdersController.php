<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Book;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function getOrders(Request $request)
    {
        try {
            $query = Order::with(['user', 'items.book', 'paymentDetails']);

            if ($request->has('status')) {
                $query->where('order_status', $request->status);
            }

            $orders = $query->orderBy('created_at', 'desc')->get()
                ->map(function($order) {
                    $order->items->each(function($item) {
                        if ($item->book) {
                            $item->book_image = $item->book->image;
                        }
                    });
                    return $order;
                });

            return response()->json($orders);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $order = Order::with(['user', 'items.book', 'paymentDetails'])->findOrFail($id);

            $order->items->each(function($item) {
                if ($item->book) {
                    $item->book_image = $item->book->image;
                    $item->book_title = $item->book->title;
                    $item->book_author = $item->book->author;
                    $item->book_condition = $item->book->condition;
                }
            });
            
            return response()->json($order);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Order not found'], 404);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
            ]);

            $order = Order::findOrFail($id);
            $oldStatus = $order->order_status;
            $order->order_status = $request->status;
            $order->save();

            Notification::create([
                'order_id' => $order->id,
                'title' => 'Order Status Updated',
                'message' => "Your order #{$order->order_number} status has been updated from {$oldStatus} to {$request->status}",
                'type' => 'info',
                'is_read' => false,
            ]);

            return response()->json(['success' => true, 'message' => 'Order status updated']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function approveOrder($id)
    {
        try {
            $order = Order::findOrFail($id);
            
            if ($order->approval_status !== 'pending') {
                return response()->json([
                    'error' => 'Order cannot be approved. Current status: ' . $order->approval_status
                ], 400);
            }
            foreach ($order->items as $item) {
                $book = Book::find($item->book_id);
                if (!$book || $book->stock < $item->quantity) {
                    return response()->json([
                        'error' => "Insufficient stock for {$item->title}. Available: " . ($book ? $book->stock : 0)
                    ], 422);
                }
            }

            DB::beginTransaction();
            
            try {
                foreach ($order->items as $item) {
                    $book = Book::find($item->book_id);
                    if ($book) {
                        $book->decrement('stock', $item->quantity);
                    }
                }

                $order->approval_status = 'approved';
                $order->order_status = 'processing';
                $order->save();

                Notification::create([
                    'order_id' => $order->id,
                    'title' => 'Order Approved',
                    'message' => "Your order #{$order->order_number} has been approved and is now being processed",
                    'type' => 'success',
                    'is_read' => false,
                ]);

                DB::commit();

                return response()->json(['success' => true, 'message' => 'Order approved successfully']);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function rejectOrder($id)
    {
        try {
            $order = Order::findOrFail($id);
            
            if ($order->approval_status !== 'pending') {
                return response()->json([
                    'error' => 'Order cannot be rejected. Current status: ' . $order->approval_status
                ], 400);
            }
            
            DB::beginTransaction();
            
            try {

                $order->approval_status = 'rejected';
                $order->order_status = 'cancelled';
                $order->save();

                Notification::create([
                    'order_id' => $order->id,
                    'title' => 'Order Rejected',
                    'message' => "Your order #{$order->order_number} has been rejected. Please contact support for more information.",
                    'type' => 'error',
                    'is_read' => false,
                ]);

                DB::commit();

                return response()->json(['success' => true, 'message' => 'Order rejected successfully']);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function confirmPayment($id)
    {
        try {
            $order = Order::findOrFail($id);
            
            if ($order->payment_status === 'Paid') {
                return response()->json(['error' => 'Payment already confirmed'], 400);
            }
            
            $order->payment_status = 'Paid';
            $order->save();

            Notification::create([
                'order_id' => $order->id,
                'title' => 'Payment Confirmed',
                'message' => "Your payment for order #{$order->order_number} has been confirmed",
                'type' => 'success',
                'is_read' => false,
            ]);

            return response()->json(['success' => true, 'message' => 'Payment confirmed successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $order = Order::findOrFail($id);
            
            DB::beginTransaction();
            
            try {
                if ($order->approval_status === 'approved') {
                    foreach ($order->items as $item) {
                        $book = Book::find($item->book_id);
                        if ($book) {
                            $book->increment('stock', $item->quantity);
                        }
                    }
                }

                if ($order->paymentDetails) {
                    $order->paymentDetails()->delete();
                }

                $order->items()->delete();

                $order->delete();
                
                DB::commit();

                return response()->json(['success' => true, 'message' => 'Order deleted successfully']);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}