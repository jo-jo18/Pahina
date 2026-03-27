<?php

use Illuminate\Support\Facades\Route;

// This is your current home page
Route::get('/', function () {
    return view('pahina'); 
});

// ADD THIS BELOW: This handles 127.0.0.1:8000/user
Route::get('/user', function () {
    return view('user'); 
});