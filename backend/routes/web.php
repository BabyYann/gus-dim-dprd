<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/mobile', function () {
    return view('mobile');
});

Route::get('/m', function () {
    return view('mobile');
});

