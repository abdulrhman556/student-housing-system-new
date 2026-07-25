<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.index');
});
Route::view('/splashscreen', 'auth.splashscreen')->name('splashscreen');
Route::view('/set', 'auth.set')->name('set');

Route::view('/login-owner', 'auth.register.login-owner')->name('login-owner');
