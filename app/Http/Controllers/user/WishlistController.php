<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WishlistController extends Controller
{
    public function index()
    {
        return view('user.home');
    }

    public function getWishlist()
    {
        try {
            $wishlist = Auth::user()->wishlist()->get();
            $wishlist = $wishlist->map(function($book) {
                return [
                    'id' => $book->id,
                    'book_id' => $book->id,
                    'isbn' => $book->isbn,
                    'title' => $book->title,
                    'author' => $book->author,
                    'price' => $book->price,
                    'image' => $book->image,
                    'condition' => $book->condition,
                    'stock' => $book->stock,
                ];
            });

            return response()->json($wishlist);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid request',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            if (!$user->wishlist()->where('book_id', $request->book_id)->exists()) {
                $user->wishlist()->attach($request->book_id);
            }

            return response()->json([
                'success' => true,
                'message' => 'Added to wishlist'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error adding to wishlist: ' . $e->getMessage()
            ], 500);
        }
    }

    public function remove($id)
    {
        try {
            Auth::user()->wishlist()->detach($id);

            return response()->json([
                'success' => true,
                'message' => 'Removed from wishlist'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error removing from wishlist: ' . $e->getMessage()
            ], 500);
        }
    }
}