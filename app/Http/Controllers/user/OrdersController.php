<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Book;
use App\Models\PaymentDetail;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class OrdersController extends Controller
{
    const SHIPPING_FEE = 5.00;

    public function index()
    {
        return view('user.home');
    }

    public function getOrders()
    {
        try {
            $orders = Order::with(['items', 'paymentDetails'])
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json($orders);
        } catch (\Exception $e) {
            Log::error('Error fetching orders: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $order = Order::with(['items', 'paymentDetails', 'user'])
                ->where('user_id', Auth::id())
                ->where('id', $id)
                ->firstOrFail();

            return response()->json($order);
        } catch (\Exception $e) {
            Log::error('Error fetching order details: ' . $e->getMessage());
            return response()->json(['error' => 'Order not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|in:cod,bank',
            'recipient_name' => 'required_if:payment_method,cod|string|max:255',
            'delivery_address' => 'required_if:payment_method,cod|string',
            'contact_number' => 'required_if:payment_method,cod|string|max:20',
            'delivery_instructions' => 'nullable|string',
            'payment_details' => 'required_if:payment_method,bank|array',
            'payment_details.sender_bank' => 'required_if:payment_method,bank|string',
            'payment_details.sender_account_name' => 'required_if:payment_method,bank|string',
            'payment_details.sender_account_number' => 'required_if:payment_method,bank|string',
            'payment_details.reference_number' => 'required_if:payment_method,bank|string',
            'payment_details.transfer_date' => 'required_if:payment_method,bank|date',
            'payment_details.transfer_amount' => 'required_if:payment_method,bank|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.isbn' => 'required|exists:books,isbn',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();

        DB::beginTransaction();

        try {
            $subtotal = 0;
            $orderItems = [];
            $bookIds = [];


            foreach ($request->items as $item) {
                $book = Book::where('isbn', $item['isbn'])->first();
                
                if (!$book) {
                    throw new \Exception("Book not found with ISBN: {$item['isbn']}");
                }

                if ($book->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$book->title}. Available: {$book->stock}");
                }

                $itemTotal = $book->price * $item['quantity'];
                $subtotal += $itemTotal;
                
                $orderItems[] = [
                    'book_id' => $book->id,
                    'isbn' => $book->isbn,
                    'title' => $book->title,
                    'author' => $book->author,
                    'price' => $book->price,
                    'quantity' => $item['quantity']
                ];
                
                $bookIds[] = $book->id;
            }

            $total = $subtotal + self::SHIPPING_FEE;

            $orderNumber = 'ORD-' . strtoupper(uniqid());

            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $user->id,
                'subtotal' => $subtotal,
                'shipping_fee' => self::SHIPPING_FEE,
                'total' => $total,
                'payment_method' => $request->payment_method === 'cod' ? 'Cash on Delivery' : 'Bank Transfer',
                'payment_status' => $request->payment_method === 'cod' ? 'Pending' : 'Awaiting Payment',
                'order_status' => 'pending',
                'approval_status' => 'pending',
                'recipient_name' => $request->recipient_name ?? $user->name,
                'delivery_address' => $request->delivery_address ?? $user->address,
                'contact_number' => $request->contact_number ?? $user->phone,
                'delivery_instructions' => $request->delivery_instructions,
            ]);

            foreach ($orderItems as $item) {
                $order->items()->create($item);
            }

            if ($request->payment_method === 'bank' && $request->has('payment_details')) {
                $order->paymentDetails()->create($request->payment_details);
            }

            Cart::where('user_id', $user->id)
                ->whereIn('book_id', $bookIds)
                ->delete();

            Notification::create([
                'title' => 'New Order',
                'message' => "New order #{$order->order_number} from {$user->name} - $" . number_format($order->total, 2),
                'type' => 'info'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully',
                'order' => $order->load('items')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Order failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cancel($id)
    {
        try {
            $orderId = $id;
            
            Log::info('Attempting to cancel order: ' . $orderId . ' for user: ' . Auth::id());

            $order = Order::where('user_id', Auth::id())
                ->where('id', $orderId)
                ->first();
            
            if (!$order) {
                Log::warning('Order not found: ' . $orderId . ' for user: ' . Auth::id());
                return response()->json([
                    'message' => 'Order not found'
                ], 404);
            }
            
            Log::info('Order found: ' . $order->order_number . ' with status: ' . $order->approval_status);

            if ($order->approval_status !== 'pending') {
                return response()->json([
                    'message' => 'Order cannot be cancelled because it has already been ' . 
                        ($order->approval_status === 'approved' ? 'approved' : 'rejected')
                ], 400);
            }
            
            if ($order->payment_status === 'Paid') {
                return response()->json([
                    'message' => 'Cannot cancel order that has already been paid'
                ], 400);
            }

            DB::beginTransaction();
            
            try {
                foreach ($order->items as $item) {
                    $book = Book::find($item->book_id);
                    if ($book) {
                        $book->increment('stock', $item->quantity);
                        Log::info('Restored stock for book: ' . $book->title . ' +' . $item->quantity);
                    }
                }

                $order->update([
                    'approval_status' => 'rejected',
                    'order_status' => 'cancelled'
                ]);

                DB::commit();
                
                Log::info('Order cancelled successfully: ' . $order->order_number);

                return response()->json([
                    'success' => true,
                    'message' => 'Order cancelled successfully'
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error during order cancellation transaction: ' . $e->getMessage());
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Error cancelling order: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error cancelling order: ' . $e->getMessage()
            ], 500);
        }
    }
}