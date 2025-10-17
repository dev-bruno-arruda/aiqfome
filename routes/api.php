<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FavoriteController;
// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile'])->name('user.show');
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Admin only routes
    Route::middleware('admin.role')->group(function () {
        Route::get('/admin-check', [AuthController::class, 'adminCheck']);
    });

     // Customer routes
     Route::apiResource('customers', CustomerController::class);

      // Favorite routes
    Route::prefix('customers/{customerId}')->group(function () {
        Route::get('/favorites', [FavoriteController::class, 'index'])->name('customers.favorites.index');
        Route::post('/favorites', [FavoriteController::class, 'store'])->name('customers.favorites.store');
        Route::get('/favorites/{productId}', [FavoriteController::class, 'show'])->name('customers.favorites.show');
        Route::delete('/favorites/{productId}', [FavoriteController::class, 'destroy'])->name('customers.favorites.destroy');
        Route::get('/favorites/{productId}/check', [FavoriteController::class, 'check'])->name('customers.favorites.check');
    });
});