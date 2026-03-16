<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\InventoryController as AdminInventoryController;
use App\Http\Controllers\Admin\OrdersController as AdminOrdersController;
use App\Http\Controllers\Admin\ReportsController as AdminReportsController;
use App\Http\Controllers\Admin\UsersController as AdminUsersController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;

use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\ShopController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\WishlistController;
use App\Http\Controllers\User\OrdersController as UserOrdersController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\AuthController as UserAuthController;

Route::prefix('user')->group(function () {
    Route::get('/books', [ShopController::class, 'getBooks']);
    Route::get('/books/featured', [HomeController::class, 'getFeatured']);
    Route::get('/books/{id}', [ShopController::class, 'show']);

    Route::post('/login', [UserAuthController::class, 'login']);
    Route::post('/register', [UserAuthController::class, 'register']);
});

Route::middleware('auth:sanctum')->prefix('user')->group(function () {

    Route::get('/cart', [CartController::class, 'getCart']);
    Route::post('/cart/add', [CartController::class, 'add']);
    Route::put('/cart/{id}', [CartController::class, 'update']);
    Route::delete('/cart/{id}', [CartController::class, 'remove']);
    Route::delete('/cart', [CartController::class, 'clear']);

    Route::get('/wishlist', [WishlistController::class, 'getWishlist']);
    Route::post('/wishlist/add', [WishlistController::class, 'add']);
    Route::delete('/wishlist/{id}', [WishlistController::class, 'remove']);


    Route::get('/orders', [UserOrdersController::class, 'getOrders']);
    Route::get('/orders/{id}', [UserOrdersController::class, 'show']);
    Route::post('/orders', [UserOrdersController::class, 'store']);


    Route::get('/profile', [ProfileController::class, 'getProfile']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword']);
    Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar']);

    Route::post('/logout', [UserAuthController::class, 'logout']);
});

Route::prefix('admin')->group(function () {
    Route::post('/login', [AdminAuthController::class, 'login']);
});


Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {

    Route::get('/stats', [AdminDashboardController::class, 'getStats']);
    Route::get('/recent-orders', [AdminDashboardController::class, 'getRecentOrders']);
    Route::get('/low-stock', [AdminDashboardController::class, 'getLowStock']);

    Route::get('/books', [AdminInventoryController::class, 'getBooks']);
    Route::post('/books', [AdminInventoryController::class, 'store']);
    Route::get('/books/{id}', [AdminInventoryController::class, 'show']);
    Route::put('/books/{id}', [AdminInventoryController::class, 'update']);
    Route::delete('/books/{id}', [AdminInventoryController::class, 'destroy']);


    Route::get('/orders', [AdminOrdersController::class, 'getOrders']);
    Route::get('/orders/{id}', [AdminOrdersController::class, 'show']);
    Route::put('/orders/{id}/status', [AdminOrdersController::class, 'updateStatus']);
    Route::post('/orders/{id}/approve-payment', [AdminOrdersController::class, 'approvePayment']);

    Route::get('/reports/sales', [AdminReportsController::class, 'getSalesReports']);
    Route::get('/reports/top-books', [AdminReportsController::class, 'getTopBooks']);
    Route::get('/reports/export', [AdminReportsController::class, 'export']);


    Route::get('/users', [AdminUsersController::class, 'getUsers']);
    Route::post('/users', [AdminUsersController::class, 'store']);
    Route::get('/users/{id}', [AdminUsersController::class, 'show']);
    Route::put('/users/{id}', [AdminUsersController::class, 'update']);
    Route::delete('/users/{id}', [AdminUsersController::class, 'destroy']);


    Route::post('/logout', [AdminAuthController::class, 'logout']);
});