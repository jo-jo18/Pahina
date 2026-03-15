<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Show cart section.
     */
    public function index()
    {
        return view('user.home');
    }

    /**
     * Get current user's cart (API).
     */
    public function getCart()
    {
        // TODO: return cart items (from session or database)
        return response()->json([]);
    }

    /**
     * Add item to cart (API).
     */
    public function add(Request $request)
    {
        // TODO: validate and add to cart
        return response()->json(['message' => 'Item added to cart']);
    }

    /**
     * Update cart item quantity (API).
     */
    public function update(Request $request, $id)
    {
        // TODO: update quantity
        return response()->json(['message' => 'Cart updated']);
    }

    /**
     * Remove item from cart (API).
     */
    public function remove($id)
    {
        // TODO: remove item
        return response()->json(['message' => 'Item removed']);
    }

    /**
     * Clear cart (API).
     */
    public function clear()
    {
        // TODO: clear all items
        return response()->json(['message' => 'Cart cleared']);
    }
}