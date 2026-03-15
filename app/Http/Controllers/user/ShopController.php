<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Show shop section.
     */
    public function index()
    {
        return view('user.home');
    }

    /**
     * Get books with optional search (API).
     */
    public function getBooks(Request $request)
    {
        $search = $request->get('search');
        // TODO: return paginated books (filter by search)
        return response()->json([]);
    }

    /**
     * Get single book details (API).
     */
    public function show($id)
    {
        // TODO: return book details
        return response()->json([]);
    }
}