<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.index');
});

Route::view('/splashscreen', 'auth.splashscreen')->name('splashscreen');
Route::view('/set', 'auth.set')->name('set');

// Auth Routes
Route::view('/student-registration', 'auth.register.Student_Registration')
    ->name('student.registration');
Route::view('/owner-registration', 'auth.register.Owner_Registration')
    ->name('owner.registration');
Route::view('/login', 'auth.login.login')->name('Login.enter');

// Home Routes

Route::view('/home', 'home.home')->name('home');
Route::view('/favorites', 'home.favorites')->name('favorites');
Route::view('/details', 'home.details')->name('details');
Route::view('/about', 'home.about')->name('about');
Route::view('/profile-student', 'home.profile-student')->name('profile-student');

// Owner Routes
Route::view('/add-property', 'owner.add-property')->name('add-property');
Route::view('/owner-buld', 'owner.owner-buld')->name('owner-buld');
Route::view('/owner-many', 'owner.owner-many')->name('owner-many');
Route::view('/owner-profile', 'owner.owner-profile')->name('owner-profile');
Route::view('/owner-support', 'owner.owner-support')->name('owner-support');

// Admin Routes
Route::view('/mangment-all', 'admin.mangment-all')->name('mangment-all');
Route::view('/mangment-build', 'admin.mangment-build')->name('mangment-build');
Route::view('/mangment-home', 'admin.mangment-home')->name('mangment-home');
Route::view('/mangment-support', 'admin.mangment-support')->name('mangment-support');
Route::view('/mangment-xx', 'admin.mangment-xx')->name('mangment-xx');


