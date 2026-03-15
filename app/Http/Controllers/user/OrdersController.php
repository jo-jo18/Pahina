<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    /**
     * Show user orders section.
     */
    public function index()
    {
        return view('user.home');
    }

    /**
     * Get user's orders (API).
     */
    public function getOrders()
    {
        // TODO: return orders for authenticated user
        return response()->json([]);
    }

    /**
     * Get single order details (API).
     */
    public function show($id)
    {
        // TODO: return order details with items
        return response()->json([]);
    }

    /**
     * Place an order (API).
     */
    public function store(Request $request)
    {
        // TODO: process checkout, create order
        return response()->json(['message' => 'Order placed', 'order_id' => 123]);
    }
}