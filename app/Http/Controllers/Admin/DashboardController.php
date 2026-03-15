<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard (main view, dashboard section active).
     */
    public function index()
    {
        return view('admin.dashboard');
    }

    /**
     * Get dashboard statistics (API).
     */
    public function getStats()
    {
        // TODO: return counts (total books, orders, users, revenue)
        return response()->json([
            'totalBooks' => 0,
            'totalOrders' => 0,
            'totalUsers' => 0,
            'totalRevenue' => 0,
        ]);
    }

    /**
     * Get recent orders for dashboard preview (API).
     */
    public function getRecentOrders()
    {
        // TODO: return last 5 orders
        return response()->json([]);
    }

    /**
     * Get low stock books for dashboard alert (API).
     */
    public function getLowStock()
    {
        // TODO: return books with stock below threshold
        return response()->json([]);
    }
}