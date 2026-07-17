<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\PropertyController;


// Mohamed
use App\Http\Controllers\Api\BookingController;
Route::apiResource('bookings', BookingController::class);
// Mohamed


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
});
