<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CartController extends Controller
{
    
    public function index()
    {
        return view('user.home');
    }

    
    public function getCart()
    {
        
        return response()->json([]);
    }

   
    public function add(Request $request)
    {
        
        return response()->json(['message' => 'Item added to cart']);
    }

    
    public function update(Request $request, $id)
    {
       
        return response()->json(['message' => 'Cart updated']);
    }

    
    public function remove($id)
    {
        
        return response()->json(['message' => 'Item removed']);
    }

    
    public function clear()
    {
        
        return response()->json(['message' => 'Cart cleared']);
    }
}