<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('profile', [AuthController::class, 'profile']);
        Route::put('profile', [AuthController::class, 'updateProfile']);
        Route::post('change-password', [AuthController::class, 'changePassword']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

// Public routes
Route::get('products/featured', [ProductController::class, 'featured']);
Route::get('products/{id}/related', [ProductController::class, 'related']);
Route::get('categories/featured', [CategoryController::class, 'featured']);
Route::get('categories/tree', [CategoryController::class, 'tree']);
Route::get('categories/{id}/products', [CategoryController::class, 'products']);

// Cart routes (both public and authenticated)
Route::get('cart', [CartController::class, 'index']);
Route::get('cart/count', [CartController::class, 'count']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Product routes
    Route::apiResource('products', ProductController::class);
    
    // Category routes
    Route::apiResource('categories', CategoryController::class);
    
    // Cart routes
    Route::post('cart', [CartController::class, 'store']);
    Route::put('cart/{id}', [CartController::class, 'update']);
    Route::delete('cart/{id}', [CartController::class, 'destroy']);
    Route::delete('cart', [CartController::class, 'clear']);
    Route::post('cart/merge', [CartController::class, 'merge']);
    
    // Order routes
    Route::apiResource('orders', OrderController::class)->only(['index', 'show', 'store']);
    Route::put('orders/{id}', [OrderController::class, 'update']);
    Route::post('orders/{id}/cancel', [OrderController::class, 'cancel']);
    Route::get('orders/{id}/track', [OrderController::class, 'track']);
    
    // Payment routes
    Route::get('payment-methods', [PaymentController::class, 'getPaymentMethods']);
    Route::post('payments', [PaymentController::class, 'processPayment']);
    Route::get('payments', [PaymentController::class, 'getPaymentHistory']);
    Route::post('payments/{id}/refund', [PaymentController::class, 'refundPayment']);
    
    // Admin routes
    Route::prefix('admin')->group(function () {
        Route::get('dashboard', [AdminController::class, 'dashboard']);
        Route::get('users', [AdminController::class, 'users']);
        Route::put('users/{id}/status', [AdminController::class, 'updateUserStatus']);
        Route::get('orders', [AdminController::class, 'orders']);
        Route::put('orders/{id}/status', [AdminController::class, 'updateOrderStatus']);
        Route::get('products', [AdminController::class, 'products']);
        Route::put('products/{id}/status', [AdminController::class, 'updateProductStatus']);
        Route::get('shops', [AdminController::class, 'shops']);
        Route::put('shops/{id}/status', [AdminController::class, 'updateShopStatus']);
        Route::get('reports', [AdminController::class, 'reports']);
        Route::get('analytics', [AdminController::class, 'analytics']);
    });
    
    // Seller routes
    Route::prefix('seller')->group(function () {
        Route::get('dashboard', [SellerController::class, 'dashboard']);
        Route::get('shop/profile', [SellerController::class, 'shopProfile']);
        Route::put('shop/profile', [SellerController::class, 'updateShopProfile']);
        Route::get('products', [SellerController::class, 'products']);
        Route::post('products', [SellerController::class, 'createProduct']);
        Route::get('orders', [SellerController::class, 'orders']);
        Route::put('orders/{id}/status', [SellerController::class, 'updateOrderStatus']);
        Route::get('earnings', [SellerController::class, 'earnings']);
        Route::get('analytics', [SellerController::class, 'analytics']);
    });
});

// Public product and category browsing
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{id}', [ProductController::class, 'show']);
Route::get('categories', [CategoryController::class, 'index']);
Route::get('categories/{id}', [CategoryController::class, 'show']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
