<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function index()
    {
        return view('user.home');
    }

    public function getCart()
    {
        try {
            $cart = Cart::with('book')
                ->where('user_id', Auth::id())
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'user_id' => $item->user_id,
                        'book_id' => $item->book_id,
                        'isbn' => $item->book->isbn,
                        'title' => $item->book->title,
                        'author' => $item->book->author,
                        'price' => $item->book->price,
                        'quantity' => $item->quantity,
                        'selected' => $item->selected,
                        'stock' => $item->book->stock,
                        'image' => $item->book->image,
                    ];
                });

            return response()->json($cart);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,id', // Changed from isbn to book_id
            'quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid request',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $book = Book::findOrFail($request->book_id);

            if ($book->stock < $request->quantity) {
                return response()->json(['message' => 'Not enough stock available'], 422);
            }
            $existingCart = Cart::where('user_id', Auth::id())
                ->where('book_id', $book->id)
                ->first();

            if ($existingCart) {
                $newQuantity = $existingCart->quantity + $request->quantity;
                if ($newQuantity > $book->stock) {
                    return response()->json(['message' => 'Cannot add more than available stock'], 422);
                }
                $existingCart->quantity = $newQuantity;
                $existingCart->save();
                $cartItem = $existingCart;
            } else {
                $cartItem = Cart::create([
                    'user_id' => Auth::id(),
                    'book_id' => $book->id,
                    'quantity' => $request->quantity,
                    'selected' => true
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Item added to cart',
                'cart' => $cartItem->load('book')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error adding to cart: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $cartItem = Cart::where('user_id', Auth::id())
                ->where('id', $id)
                ->firstOrFail();

            $validator = Validator::make($request->all(), [
                'quantity' => 'required|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Invalid quantity'], 422);
            }

            if ($cartItem->book->stock < $request->quantity) {
                return response()->json(['message' => 'Not enough stock available'], 422);
            }

            $cartItem->quantity = $request->quantity;
            $cartItem->save();

            return response()->json([
                'success' => true,
                'message' => 'Cart updated',
                'cart' => $cartItem->load('book')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating cart: ' . $e->getMessage()
            ], 500);
        }
    }

    public function remove($id)
    {
        try {
            $cartItem = Cart::where('user_id', Auth::id())
                ->where('id', $id)
                ->firstOrFail();
            
            $cartItem->delete();

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error removing item: ' . $e->getMessage()
            ], 500);
        }
    }

    public function clear()
    {
        try {
            Cart::where('user_id', Auth::id())->delete();

            return response()->json([
                'success' => true,
                'message' => 'Cart cleared'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error clearing cart: ' . $e->getMessage()
            ], 500);
        }
    }
}