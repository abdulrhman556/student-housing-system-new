<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\PropertyImageController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProfileController;

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
    // user profile
    // ==========================


Route::middleware('auth:sanctum')->group(function () {
    Route::put('/profile',                 [ProfileController::class, 'update']);
    Route::patch('/profile/change-password', [ProfileController::class, 'changePassword']);
});



// Mohamed
Route::apiResource('bookings', BookingController::class);
// Mohamed





// ==========================
    // Owner
    // ==========================
    Route::middleware('role:owner')->group(function () {

        Route::get('/my-properties',      [PropertyController::class, 'myProperties']);
        Route::post('/properties',        [PropertyController::class, 'store']);
        Route::put('/properties/{id}',    [PropertyController::class, 'update']);
        Route::delete('/properties/{id}', [PropertyController::class, 'destroy']);


    });


Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('role:owner')->group(function () {
        Route::post('/properties/{propertyId}/images',  [PropertyImageController::class, 'store']);
        Route::delete('/images/{id}',                   [PropertyImageController::class, 'destroy']);
        Route::patch('/images/{id}/set-cover',          [PropertyImageController::class, 'setCover']);
    });
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

// Public
Route::get('/properties/{propertyId}/units',  [UnitController::class, 'index']);
Route::get('/units/{id}',                     [UnitController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {

    // Owner
    Route::middleware('role:owner')->group(function () {
        Route::post('/properties/{propertyId}/units', [UnitController::class, 'store']);
        Route::put('/units/{id}',                     [UnitController::class, 'update']);
        Route::delete('/units/{id}',                  [UnitController::class, 'destroy']);
    });
});




// routes/api.php — public مش محتاجة token

Route::get('/governorates',                    [LocationController::class, 'governorates']);
Route::get('/governorates/{id}/cities',        [LocationController::class, 'cities']);
Route::get('/cities/{id}/universities',        [LocationController::class, 'universities']);
