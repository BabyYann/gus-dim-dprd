<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

if (!function_exists('isGusdimMaintenance')) {
    function isGusdimMaintenance(Request $request): bool {
        // Bypass khusus jika memiliki token atau query parameter
        if ($request->query('bypass') === 'gusdim2025' || $request->cookie('gusdim_bypass') === 'gusdim2025' || $request->header('X-Maintenance-Bypass') === 'gusdim2025') {
            return false;
        }

        // Cek file lock maintenance repository
        if (file_exists(base_path('maintenance.lock')) || file_exists(base_path('../maintenance.lock'))) {
            return true;
        }

        // Cek file native laravel down
        if (file_exists(storage_path('framework/down'))) {
            return true;
        }

        // Cek konfigurasi env
        if (env('APP_MAINTENANCE', false) === true || env('APP_MAINTENANCE', false) === 'true') {
            return true;
        }

        return false;
    }
}

// Endpoint bypass untuk admin/developer
Route::get('/maintenance-bypass', function (Request $request) {
    if ($request->query('key') === 'gusdim2025') {
        return redirect('/')->withCookie(cookie('gusdim_bypass', 'gusdim2025', 60 * 24 * 7));
    }
    return response('Kunci otorisasi tidak valid.', 403);
});

// Endpoint untuk mengunci kembali dari sisi browser
Route::get('/maintenance-lock', function (Request $request) {
    return redirect('/')->withoutCookie('gusdim_bypass');
});

Route::get('/', function (Request $request) {
    if (isGusdimMaintenance($request)) {
        return response()->view('errors.503', [], 503);
    }
    if ($request->query('view') === 'desktop') {
        return view('dashboard');
    }
    $ua = $request->header('User-Agent', '');
    $isMobile = preg_match('/(android|iphone|ipad|ipod|mobile|blackberry|iemobile|opera mini)/i', $ua);
    if ($isMobile) {
        return redirect('/mobile');
    }
    return view('dashboard');
});

Route::get('/mobile', function (Request $request) {
    if (isGusdimMaintenance($request)) {
        return response()->view('errors.503', [], 503);
    }
    if ($request->query('view') === 'desktop') {
        return redirect('/?view=desktop');
    }
    return view('mobile');
});

Route::get('/m', function (Request $request) {
    if (isGusdimMaintenance($request)) {
        return response()->view('errors.503', [], 503);
    }
    if ($request->query('view') === 'desktop') {
        return redirect('/?view=desktop');
    }
    return view('mobile');
});