<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function getUsers(Request $request)
    {
        $users = User::orderBy('created_at', 'desc')->get();
        
        return response()->json($users->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_admin' => $user->is_admin,
                'profile_pic' => $user->profile_pic,
                'birthday' => $user->birthday,
                'phone' => $user->phone,
                'address' => $user->address,
                'created_at' => $user->created_at,
            ];
        }));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'is_admin' => 'boolean',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => $request->is_admin ?? false,
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'is_admin' => 'boolean',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'is_admin' => $request->is_admin ?? $user->is_admin,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->is_admin) {
            $adminCount = User::where('is_admin', true)->count();
            if ($adminCount <= 1) {
                return response()->json([
                    'message' => 'Cannot delete the last admin account'
                ], 400);
            }
        }

        $user->delete();
        
        return response()->json(['message' => 'User deleted successfully']);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        
        $orders = Order::where('user_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'user' => $user,
            'orders' => $orders
        ]);
    }
}