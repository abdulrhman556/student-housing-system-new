<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.index');
});

Route::view('/splashscreen', 'auth.splashscreen')->name('splashscreen');
Route::view('/set', 'auth.set')->name('set');

Route::view('/student-registration', 'auth.register.Student_Registration')
    ->name('student.registration');
Route::view('/owner-registration', 'auth.register.Owner_Registration')
    ->name('owner.registration');
Route::view('/login', 'auth.login.login')->name('Login.enter');


Route::view('/home', 'home.home')->name('home');
Route::view('/favorites', 'home.favorites')->name('favorites');
Route::view('/details', 'home.details')->name('details');
Route::view('/about', 'home.about')->name('about');
Route::view('/profile-student', 'home.profile-student')->name('profile-student');
