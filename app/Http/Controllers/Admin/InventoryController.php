<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class InventoryController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function getBooks(Request $request)
    {
        $books = Book::orderBy('created_at', 'desc')->get();
        return response()->json($books);
    }

    public function store(Request $request)
    {
        $request->validate([
            'isbn' => 'required|unique:books',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'synopsis' => 'required|string',
            'condition' => 'required|in:new,like-new,good,acceptable',
            'image' => 'nullable|image|max:10240'
        ]);

        $data = $request->except('image');
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('books', 'public');
            $data['image'] = $path;
        }

        $book = Book::create($data);
        
        return response()->json([
            'message' => 'Book added successfully',
            'book' => $book
        ]);
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        
        $request->validate([
            'isbn' => 'required|unique:books,isbn,' . $id,
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'synopsis' => 'required|string',
            'condition' => 'required|in:new,like-new,good,acceptable',
            'image' => 'nullable|image|max:10240'
        ]);

        $data = $request->except('image');
        
        if ($request->hasFile('image')) {
            if ($book->image) {
                Storage::disk('public')->delete($book->image);
            }
            $path = $request->file('image')->store('books', 'public');
            $data['image'] = $path;
        }

        $book->update($data);
        
        return response()->json([
            'message' => 'Book updated successfully',
            'book' => $book
        ]);
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);

        if ($book->image) {
            Storage::disk('public')->delete($book->image);
        }
        
        $book->delete();
        
        return response()->json(['message' => 'Book deleted successfully']);
    }

    public function show($id)
    {
        $book = Book::findOrFail($id);
        return response()->json($book);
    }
}