<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\PropertyImageController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\AdminBookingController;
use App\Http\Controllers\Api\AdminDashboardController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\PaymentSettingController;
use App\Http\Controllers\Api\ReviewController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::post('/auth/register',    [AuthController::class, 'register']);
Route::post('/auth/login',       [AuthController::class, 'login']);
Route::post('/auth/admin-login', [AuthController::class, 'adminLogin']);

Route::get('/properties',        [PropertyController::class, 'index']);
Route::get('/properties/{id}',   [PropertyController::class, 'show']);

Route::get('/properties/{propertyId}/units', [UnitController::class, 'index']);
Route::get('/units/{id}',                    [UnitController::class, 'show']);

Route::get('/governorates',               [LocationController::class, 'governorates']);
Route::get('/governorates/{id}/cities',   [LocationController::class, 'cities']);
Route::get('/cities/{id}/universities',   [LocationController::class, 'universities']);

Route::get('/properties/{property}/reviews', [ReviewController::class, 'index']);
Route::get('/reviews/{review}',              [ReviewController::class, 'show']);

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // ── Auth ──
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me',      [AuthController::class, 'me']);

    // ── Profile ──
    Route::put('/profile',                   [ProfileController::class, 'update']);
    Route::patch('/profile/change-password', [ProfileController::class, 'changePassword']);

    // ── Bookings ──
    Route::get('/bookings',                      [BookingController::class, 'index']);
    Route::post('/bookings',                     [BookingController::class, 'store']);
    Route::get('/bookings/{booking}',            [BookingController::class, 'show']);
    Route::patch('/bookings/{booking}/cancel',   [BookingController::class, 'cancel']);
    Route::get('/bookings/{booking}/history',    [BookingController::class, 'history']);

    // ── Favorites ──
    Route::apiResource('favorites', FavoriteController::class)
        ->only(['index', 'store', 'destroy']);
    // Mohammed: Added routes for bookings and favorites

    // Booking
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/bookings/{booking}', [BookingController::class, 'show']);
    Route::patch('/bookings/{booking}/status', [BookingController::class, 'updateStatus']);
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel']);
    Route::get('/bookings/{booking}/history', [BookingController::class, 'history']);

    // متكرارين
    // Route::patch('admin/bookings/{booking}/confirm',
    // [AdminBookingController::class,'confirmAvailability']);
    // Route::patch('admin/bookings/{booking}/reject',
    //     [AdminBookingController::class,'reject']);
    // Route::apiResource('bookings', BookingController::class);




    // Route::patch(
    // 'admin/bookings/{booking}/confirm',
    // [AdminBookingController::class,'confirmAvailability']);
    // Route::patch(
    //     'admin/bookings/{booking}/reject',
    //     [AdminBookingController::class,'reject']);


    Route::patch('admin/bookings/{booking}/confirm',
    [AdminBookingController::class,'confirmAvailability']);
    Route::patch('admin/bookings/{booking}/reject',
        [AdminBookingController::class,'reject']);
    Route::apiResource('bookings', BookingController::class);

    // Booking

    // Favorites
    Route::apiResource('favorites', FavoriteController::class)
        ->only(['index', 'store', 'destroy']);
    // Route::middleware('auth:sanctum')->group(function () {
    // Route::apiResource('favorites', FavoriteController::class)
    //     ->only(['index', 'store', 'destroy']); });
    // Favorites

    // ── Notifications ──
    Route::prefix('notifications')->group(function () {
        Route::get('/',                          [NotificationController::class, 'index']);
        Route::get('/unread',                    [NotificationController::class, 'unread']);
        Route::patch('/{notification}/read',     [NotificationController::class, 'markAsRead']);
        Route::delete('/{notification}',         [NotificationController::class, 'destroy']);
    });

    // ── Payments ──
    Route::get('/payment-settings',                    [PaymentSettingController::class, 'index']);
    Route::post('/payments',                           [PaymentController::class, 'store']);
    Route::get('/bookings/{booking}/payments',         [PaymentController::class, 'index']);
    Route::patch('/payments/{payment}/verify',         [PaymentController::class, 'verify']);
    Route::patch('/payments/{payment}/reject',         [PaymentController::class, 'reject']);

    // ── Reviews ──
    Route::post('/reviews',            [ReviewController::class, 'store']);
    Route::patch('/reviews/{review}',  [ReviewController::class, 'update']);
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']);

    // ── Owner ──
    // Mohammed: Added routes for bookings and favorites




    // ==========================
    Route::middleware('auth:sanctum')->group(function () {
        Route::put('/profile',                 [ProfileController::class, 'update']);
        Route::patch('/profile/change-password', [ProfileController::class, 'changePassword']);
    });



// ==========================
    // Owner
    // ==========================
    Route::middleware('role:owner')->group(function () {
        Route::get('/my-properties',          [PropertyController::class, 'myProperties']);
        Route::post('/properties',            [PropertyController::class, 'store']);
        Route::put('/properties/{id}',        [PropertyController::class, 'update']);
        Route::delete('/properties/{id}',     [PropertyController::class, 'destroy']);

        Route::post('/properties/{propertyId}/images', [PropertyImageController::class, 'store']);
        Route::delete('/images/{id}',                  [PropertyImageController::class, 'destroy']);
        Route::patch('/images/{id}/set-cover',         [PropertyImageController::class, 'setCover']);

        Route::post('/properties/{propertyId}/units',  [UnitController::class, 'store']);
        Route::put('/units/{id}',                      [UnitController::class, 'update']);
        Route::delete('/units/{id}',                   [UnitController::class, 'destroy']);
    });

    // ── Admin ──
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard',                          [AdminDashboardController::class, 'index']);

        Route::patch('/admin/properties/{id}/approve',         [PropertyController::class, 'approve']);
        Route::patch('/admin/properties/{id}/reject',          [PropertyController::class, 'reject']);

        Route::get('/admin/bookings',                          [AdminBookingController::class, 'index']);
        Route::get('/admin/bookings/{booking}',                [AdminBookingController::class, 'show']);
        Route::patch('/admin/bookings/{booking}/confirm',      [AdminBookingController::class, 'confirmAvailability']);
        Route::patch('/admin/bookings/{booking}/reject',       [AdminBookingController::class, 'reject']);
        Route::patch('/admin/bookings/{booking}/cancel',       [AdminBookingController::class, 'cancel']);
    });

});
