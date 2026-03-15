<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportsController extends Controller
{

    public function index()
    {
        return view('admin.dashboard');
    }

    public function getSalesReports(Request $request)
    {
        $period = $request->get('period', 'today'); 

        return response()->json([]);
    }


    public function getTopBooks()
    {

        return response()->json([]);
    }

    public function export(Request $request)
    {

        return response()->json(['message' => 'Export not implemented']);
    }
}