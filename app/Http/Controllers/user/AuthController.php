<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show login modal – not needed, but method exists.
     */
    public function showLoginForm()
    {
        return view('user.home');
    }

    /**
     * Handle user login (API).
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            // Ensure user is not admin (optional)
            if ($user->is_admin) {
                Auth::logout();
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            $token = $user->createToken('user-token')->plainTextToken;
            return response()->json(['token' => $token, 'user' => $user]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    /**
     * Handle user registration (API).
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'birthday' => 'required|date',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        // Validate age >= 15
        $birthday = \Carbon\Carbon::parse($data['birthday']);
        if ($birthday->age < 15) {
            return response()->json(['message' => 'You must be at least 15 years old.'], 422);
        }

        $data['password'] = bcrypt($data['password']);
        $data['is_admin'] = false;
        $user = User::create($data);

        $token = $user->createToken('user-token')->plainTextToken;
        return response()->json(['token' => $token, 'user' => $user]);
    }

    /**
     * Handle user logout (API).
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}