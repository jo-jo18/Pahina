<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('user.home');
    }

    public function getFeatured()
    {
        $books = Book::inStock()
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        return response()->json($books);
    }
}