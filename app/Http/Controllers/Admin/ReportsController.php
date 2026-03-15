<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    /**
     * Show reports section.
     */
    public function index()
    {
        return view('admin.dashboard');
    }

    /**
     * Get sales reports (API).
     */
    public function getSalesReports(Request $request)
    {
        $period = $request->get('period', 'today'); // today, week, month, year, all
        // TODO: aggregate sales data based on period
        return response()->json([]);
    }

    /**
     * Get top selling books (API).
     */
    public function getTopBooks()
    {
        // TODO: return top 10 books by quantity sold
        return response()->json([]);
    }

    /**
     * Export data (API) – returns a downloadable file.
     */
    public function export(Request $request)
    {
        // TODO: generate CSV/Excel export
        return response()->json(['message' => 'Export not implemented']);
    }
}