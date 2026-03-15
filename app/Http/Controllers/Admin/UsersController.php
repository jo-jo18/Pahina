<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UsersController extends Controller
{

    public function index()
    {
        return view('admin.dashboard');
    }

    public function getUsers(Request $request)
    {

        return response()->json([]);
    }

    public function store(Request $request)
    {

        return response()->json(['message' => 'User created']);
    }


    public function update(Request $request, $id)
    {

        return response()->json(['message' => 'User updated']);
    }


    public function destroy($id)
    {

        return response()->json(['message' => 'User deleted']);
    }


    public function show($id)
    {

        return response()->json([]);
    }
}