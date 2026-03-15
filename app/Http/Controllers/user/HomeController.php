<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show home section.
     */
    public function index()
    {
        return view('user.home');
    }

    /**
     * Get featured books (API).
     */
    public function getFeatured()
    {
        // TODO: return featured books
        return response()->json([]);
    }
}