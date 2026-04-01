<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\InventoryController as AdminInventoryController;
use App\Http\Controllers\Admin\OrdersController as AdminOrdersController;
use App\Http\Controllers\Admin\ReportsController as AdminReportsController;
use App\Http\Controllers\Admin\UsersController as AdminUsersController;

use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\ShopController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\WishlistController;
use App\Http\Controllers\User\OrdersController as UserOrdersController;
use App\Http\Controllers\User\ProfileController;

Route::get('/test-api', function() {
    return view('test-api');
});
Route::get('/user', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
Route::get('/orders', [UserOrdersController::class, 'index'])->name('user.orders');
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/inventory', [AdminInventoryController::class, 'index'])->name('inventory');
    Route::get('/orders', [AdminOrdersController::class, 'index'])->name('orders');
    Route::get('/reports', [AdminReportsController::class, 'index'])->name('reports');
    Route::get('/users', [AdminUsersController::class, 'index'])->name('users');
});