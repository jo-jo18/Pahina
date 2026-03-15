<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Show inventory section.
     */
    public function index()
    {
        return view('admin.dashboard'); // same layout, JS will show inventory section
    }

    /**
     * Get all books (API).
     */
    public function getBooks(Request $request)
    {
        // TODO: return paginated list of books
        return response()->json([]);
    }

    /**
     * Add a new book (API).
     */
    public function store(Request $request)
    {
        // TODO: validate and save book
        return response()->json(['message' => 'Book added successfully']);
    }

    /**
     * Update a book (API).
     */
    public function update(Request $request, $id)
    {
        // TODO: update book
        return response()->json(['message' => 'Book updated']);
    }

    /**
     * Delete a book (API).
     */
    public function destroy($id)
    {
        // TODO: delete book
        return response()->json(['message' => 'Book deleted']);
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