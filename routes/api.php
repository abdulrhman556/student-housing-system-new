<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\PropertyImageController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\AmenityController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\AdminBookingController;
use App\Http\Controllers\Api\AdminDashboardController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\PaymentSettingController;
use App\Http\Controllers\Api\SiteSettingController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\AdminUserController;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::middleware('throttle:5,1')->group(function () {
    Route::post('/auth/register',    [AuthController::class, 'register']);
    Route::post('/auth/login',       [AuthController::class, 'login']);
    Route::post('/auth/admin-login', [AuthController::class, 'adminLogin']);
    Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/auth/reset-password',  [AuthController::class, 'resetPassword']);
});
Route::get('/auth/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify');

Route::get('/properties',        [PropertyController::class, 'index']);
Route::get('/properties/{id}',   [PropertyController::class, 'show']);

Route::get('/properties/{propertyId}/units', [UnitController::class, 'index']);
Route::get('/units/{id}',                    [UnitController::class, 'show']);

Route::get('/governorates',               [LocationController::class, 'governorates']);
Route::get('/governorates/{id}/cities',   [LocationController::class, 'cities']);
Route::get('/cities/{id}/universities',   [LocationController::class, 'universities']);
Route::get('/amenities',                  [AmenityController::class, 'index']);
Route::get('/site-settings', [SiteSettingController::class, 'show']);

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
    Route::post('/auth/email/resend', [AuthController::class, 'resendVerification']);

    // ── Profile ──
    Route::put('/profile',                   [ProfileController::class, 'update']);
    Route::patch('/profile/change-password', [ProfileController::class, 'changePassword']);
    Route::get('/profile/national-id-image', [ProfileController::class, 'nationalIdImage']);

    // ── Bookings ──
    Route::get('/bookings',                      [BookingController::class, 'index']);
    Route::post('/bookings',                     [BookingController::class, 'store']);
    Route::get('/bookings/{booking}',            [BookingController::class, 'show']);
    Route::patch('/bookings/{booking}/cancel',   [BookingController::class, 'cancel']);
    Route::get('/bookings/{booking}/history',    [BookingController::class, 'history']);

    // ── Favorites ──
    Route::apiResource('favorites', FavoriteController::class)
        ->only(['index', 'store', 'destroy']);

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
    Route::get('/payments/{payment}/proof', [PaymentController::class, 'showProof']);

    // ── Reviews ──
    Route::post('/reviews',            [ReviewController::class, 'store']);
    Route::patch('/reviews/{review}',  [ReviewController::class, 'update']);
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']);

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

    Route::get('/admin/owners/pending',        [AdminUserController::class, 'pendingOwners']);
    Route::get('/admin/users/{id}/national-id-image', [AdminUserController::class, 'nationalIdImage']);
    Route::get('/admin/students',              [AdminUserController::class, 'students']);
    Route::get('/admin/owners',                [AdminUserController::class, 'owners']);
    Route::patch('/admin/owners/{id}/approve', [AdminUserController::class, 'approveOwner']);
    Route::patch('/admin/owners/{id}/block',   [AdminUserController::class, 'blockOwner']);
    Route::patch('/admin/students/{id}/block',   [AdminUserController::class, 'blockStudent']);
    Route::patch('/admin/students/{id}/unblock', [AdminUserController::class, 'unblockStudent']);
    Route::post('/admin/payment-settings', [PaymentSettingController::class, 'store']);
    Route::patch('/admin/payment-settings/{paymentSetting}', [PaymentSettingController::class, 'update']);
    Route::patch('/admin/site-settings', [SiteSettingController::class, 'update']);

        Route::get('/admin/dashboard',                          [AdminDashboardController::class, 'index']);

        Route::patch('/payments/{payment}/verify',         [PaymentController::class, 'verify']);
        Route::patch('/payments/{payment}/reject',         [PaymentController::class, 'reject']);

        Route::patch('/admin/properties/{id}/approve',         [PropertyController::class, 'approve']);
        Route::patch('/admin/properties/{id}/reject',          [PropertyController::class, 'reject']);
        Route::get('/admin/properties',                         [PropertyController::class, 'adminIndex']);

        Route::get('/admin/bookings',                          [AdminBookingController::class, 'index']);
        Route::get('/admin/bookings/{booking}',                [AdminBookingController::class, 'show']);
        Route::patch('/admin/bookings/{booking}/confirm',      [AdminBookingController::class, 'confirmAvailability']);
        Route::patch('/admin/bookings/{booking}/reject',       [AdminBookingController::class, 'reject']);
        Route::patch('/admin/bookings/{booking}/cancel',       [AdminBookingController::class, 'cancel']);
    });

});
