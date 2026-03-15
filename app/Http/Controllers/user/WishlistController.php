<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Show wishlist section.
     */
    public function index()
    {
        return view('user.home');
    }

    /**
     * Get user's wishlist (API).
     */
    public function getWishlist()
    {
        // TODO: return wishlist items
        return response()->json([]);
    }

    /**
     * Add to wishlist (API).
     */
    public function add(Request $request)
    {
        // TODO: add book to wishlist
        return response()->json(['message' => 'Added to wishlist']);
    }

    /**
     * Remove from wishlist (API).
     */
    public function remove($id)
    {
        // TODO: remove from wishlist
        return response()->json(['message' => 'Removed from wishlist']);
    }
}