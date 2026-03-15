<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    /**
     * Show orders section.
     */
    public function index()
    {
        return view('admin.dashboard');
    }

    /**
     * Get all orders (API).
     */
    public function getOrders(Request $request)
    {
        // TODO: return paginated orders
        return response()->json([]);
    }

    /**
     * Get single order details (API).
     */
    public function show($id)
    {
        // TODO: return order with items
        return response()->json([]);
    }

    /**
     * Update order status (API).
     */
    public function updateStatus(Request $request, $id)
    {
        // TODO: change order status (processing, shipped, delivered)
        return response()->json(['message' => 'Order status updated']);
    }

    /**
     * Approve payment (API).
     */
    public function approvePayment(Request $request, $id)
    {
        // TODO: mark payment as approved, update order
        return response()->json(['message' => 'Payment approved']);
    }
}
