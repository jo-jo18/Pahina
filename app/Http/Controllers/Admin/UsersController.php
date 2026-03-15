<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Show users section.
     */
    public function index()
    {
        return view('admin.dashboard');
    }

    /**
     * Get all users (API).
     */
    public function getUsers(Request $request)
    {
        // TODO: return paginated users (customers and admins)
        return response()->json([]);
    }

    /**
     * Add a new user (API).
     */
    public function store(Request $request)
    {
        // TODO: validate and create user
        return response()->json(['message' => 'User created']);
    }

    /**
     * Update a user (API).
     */
    public function update(Request $request, $id)
    {
        // TODO: update user info
        return response()->json(['message' => 'User updated']);
    }

    /**
     * Delete a user (API).
     */
    public function destroy($id)
    {
        // TODO: delete user
        return response()->json(['message' => 'User deleted']);
    }

    /**
     * Get single user details (API).
     */
    public function show($id)
    {
        // TODO: return user details
        return response()->json([]);
    }
}