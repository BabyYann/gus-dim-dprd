<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function (Request $request) {
    $ua = $request->header('User-Agent', '');
    $isMobile = preg_match('/(android|iphone|ipad|ipod|mobile|blackberry|iemobile|opera mini)/i', $ua);
    if ($isMobile) {
        return redirect('/mobile');
    }
    return view('dashboard');
});

Route::get('/mobile', function () {
    return view('mobile');
});

Route::get('/m', function () {
    return view('mobile');
});