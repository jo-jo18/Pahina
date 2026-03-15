<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Show profile section.
     */
    public function index()
    {
        return view('user.home');
    }

    /**
     * Get user profile data (API).
     */
    public function getProfile()
    {
        // TODO: return authenticated user's profile
        return response()->json([]);
    }

    /**
     * Update profile (API).
     */
    public function update(Request $request)
    {
        // TODO: validate and update user info
        return response()->json(['message' => 'Profile updated']);
    }

    /**
     * Change password (API).
     */
    public function changePassword(Request $request)
    {
        // TODO: validate and change password
        return response()->json(['message' => 'Password changed']);
    }

    /**
     * Upload profile picture (API).
     */
    public function uploadAvatar(Request $request)
    {
        // TODO: handle avatar upload
        return response()->json(['message' => 'Avatar uploaded']);
    }
}