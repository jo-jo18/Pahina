<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        return view('user.home');
    }

    public function getProfile()
    {
        $user = auth()->user();
        return response()->json($user);
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'birthday' => 'nullable|date',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        if ($request->birthday) {
            $birthday = \Carbon\Carbon::parse($request->birthday);
            if ($birthday->age < 15) {
                return response()->json([
                    'message' => 'You must be at least 15 years old.'
                ], 422);
            }
        }

        $user->update($request->only(['name', 'email', 'birthday', 'phone', 'address']));

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 400);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password changed successfully']);
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:2048'
        ]);

        $user = auth()->user();

        if ($request->hasFile('avatar')) {
            if ($user->profile_pic) {
                Storage::disk('public')->delete($user->profile_pic);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->profile_pic = $path;
            $user->save();

            return response()->json([
                'message' => 'Avatar uploaded successfully',
                'path' => $path
            ]);
        }

        return response()->json(['message' => 'No file uploaded'], 400);
    }
}