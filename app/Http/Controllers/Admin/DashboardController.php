<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function index()
    {
        return view('admin.dashboard');
    }


    public function getStats()
    {

        return response()->json([
            'totalBooks' => 0,
            'totalOrders' => 0,
            'totalUsers' => 0,
            'totalRevenue' => 0,
        ]);
    }


    public function getRecentOrders()
    {
        return response()->json([]);
    }

    public function getLowStock()
    {

        return response()->json([]);
    }
}