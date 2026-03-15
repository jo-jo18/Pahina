<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InventoryController extends Controller
{

    public function index()
    {
        return view('admin.dashboard');
    }


    public function getBooks(Request $request)
    {
    
        return response()->json([]);
    }


    public function store(Request $request)
    {

        return response()->json(['message' => 'Book added successfully']);
    }


    public function update(Request $request, $id)
    {

        return response()->json(['message' => 'Book updated']);
    }

    public function destroy($id)
    {

        return response()->json(['message' => 'Book deleted']);
    }


    public function show($id)
    {
 
        return response()->json([]);
    }
}