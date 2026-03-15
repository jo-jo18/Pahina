<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrdersController extends Controller
{

    public function index()
    {
        return view('admin.dashboard');
    }

    public function getOrders(Request $request)
    {
 
        return response()->json([]);
    }


    public function show($id)
    {

        return response()->json([]);
    }

    public function updateStatus(Request $request, $id)
    {
        return response()->json(['message' => 'Order status updated']);
    }


    public function approvePayment(Request $request, $id)
    {

        return response()->json(['message' => 'Payment approved']);
    }
}
