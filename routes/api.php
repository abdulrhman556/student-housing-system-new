<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;



Route::post('/auth/register',    [AuthController::class, 'register']);
Route::post('/auth/login',       [AuthController::class, 'login']);
Route::post('/auth/admin-login', [AuthController::class, 'adminLogin']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me',      [AuthController::class, 'me']);

    Route::middleware('role:student')->group(function () {
        Route::post('/bookings',     [BookingController::class, 'store']);
        Route::get('/bookings/my',   [BookingController::class, 'myBookings']);
    });

    Route::middleware('role:owner')->group(function () {
        Route::post('/properties',        [PropertyController::class, 'store']);
        Route::put('/properties/{id}',    [PropertyController::class, 'update']);
        Route::delete('/properties/{id}', [PropertyController::class, 'destroy']);
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/users',                     [AdminController::class, 'users']);
        Route::patch('/admin/properties/{id}/approve', [AdminController::class, 'approve']);
    });
});

Route::get('/properties',      [PropertyController::class, 'index']);
Route::get('/properties/{id}', [PropertyController::class, 'show']);
