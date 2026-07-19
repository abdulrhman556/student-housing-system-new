<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\NotificationController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
// ==========================================
// 🔓 Public Routes (متاحة للجميع بدون تسجيل دخول)
// ==========================================

Route::post('/auth/register',    [AuthController::class, 'register']);
Route::post('/auth/login',       [AuthController::class, 'login']);
Route::post('/auth/admin-login', [AuthController::class, 'adminLogin']);

Route::get('/properties',      [PropertyController::class, 'index']);
Route::get('/properties/{id}', [PropertyController::class, 'show']);


/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // ==========================
    // Auth
    // ==========================
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me',      [AuthController::class, 'me']);

    // ==========================
    // Student
    // ==========================


    // Mohammed: Added routes for bookings and favorites

    // Booking
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/bookings/{booking}', [BookingController::class, 'show']);
    Route::patch('/bookings/{booking}', [BookingController::class, 'update']);
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy']);
    // Booking

    // Favorites
    Route::apiResource('favorites', FavoriteController::class)
        ->only(['index', 'store', 'destroy']);
    Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('favorites', FavoriteController::class)
        ->only(['index', 'store', 'destroy']); });
    // Favorites

    // Notifications
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::get('/unread', [NotificationController::class, 'unread']);
        Route::patch(
            '/{notification}/read',
            [NotificationController::class, 'markAsRead']
        );
        Route::delete(
            '/{notification}',
            [NotificationController::class, 'destroy']
        );
    });
    // Notifications

    // Mohammed: Added routes for bookings and favorites

    // ==========================
    // Owner
    // ==========================
    Route::middleware('role:owner')->group(function () {

        Route::get('/my-properties',      [PropertyController::class, 'myProperties']);
        Route::post('/properties',        [PropertyController::class, 'store']);
        Route::put('/properties/{id}',    [PropertyController::class, 'update']);
        Route::delete('/properties/{id}', [PropertyController::class, 'destroy']);

    });

    // ==========================
    // Admin
    // ==========================
    Route::middleware('role:admin')->group(function () {
        // التحكم في قبول/رفض العقارات من الـ PropertyController

        Route::patch('/admin/properties/{id}/approve', [PropertyController::class, 'approve']);
        Route::patch('/admin/properties/{id}/reject',  [PropertyController::class, 'reject']);

    });

});
