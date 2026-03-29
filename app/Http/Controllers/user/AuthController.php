<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('user.home');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && !$user->is_admin && Hash::check($request->password, $user->password)) {
            $token = $user->createToken('user-token', ['user'])->plainTextToken;
            return response()->json([
                'token' => $token, 
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'profile_pic' => $user->profile_pic,
                    'birthday' => $user->birthday,
                    'phone' => $user->phone,
                    'address' => $user->address,
                ]
            ]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'birthday' => 'required|date',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $birthday = Carbon::parse($request->birthday);
        if ($birthday->age < 15) {
            return response()->json([
                'message' => 'You must be at least 15 years old.'
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'birthday' => $request->birthday,
            'phone' => $request->phone,
            'address' => $request->address,
            'is_admin' => false,
        ]);

        $token = $user->createToken('user-token', ['user'])->plainTextToken;
        
        return response()->json([
            'token' => $token, 
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'profile_pic' => $user->profile_pic,
                'birthday' => $user->birthday,
                'phone' => $user->phone,
                'address' => $user->address,
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}