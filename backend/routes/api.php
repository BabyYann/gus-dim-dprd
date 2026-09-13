<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\PendukungController;
use App\Http\Controllers\Api\VerifikasiController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuditLogController;
use App\Http\Controllers\Api\ExportController;

/*
|--------------------------------------------------------------------------
| API Routes - Sistem Informasi Manajemen "GUS DIM"
|--------------------------------------------------------------------------
*/

// Public Auth Endpoints
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/auth/check', [AuthController::class, 'check'])->middleware('auth:sanctum');
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/auth/profile', [AuthController::class, 'updateProfile'])->middleware('auth:sanctum');
Route::post('/auth/avatar', [AuthController::class, 'updateAvatar'])->middleware('auth:sanctum');

// Adapter untuk format auth.php?action=...
Route::any('/auth.php', function (Request $request) {
    $action = $request->query('action', 'check');
    $ctrl = app(AuthController::class);
    return match ($action) {
        'login' => $ctrl->login($request),
        'check' => $ctrl->check($request),
        'logout' => $ctrl->logout($request),
        'update-profile' => $ctrl->updateProfile($request),
        'update-avatar' => $ctrl->updateAvatar($request),
        default => response()->json(['error' => 'Aksi tidak valid'], 400),
    };
});

// Protected Endpoints
Route::middleware('auth:sanctum')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard.php', [DashboardController::class, 'index']);

    // Pendukung
    Route::post('/pendukung', [PendukungController::class, 'store']);
    Route::get('/pendukung/check-nik', [PendukungController::class, 'checkNik']);
    Route::any('/pendukung.php', function (Request $request) {
        $action = $request->query('action', 'submit');
        $ctrl = app(PendukungController::class);
        return match ($action) {
            'submit' => $ctrl->store($request),
            'check-nik' => $ctrl->checkNik($request),
            default => response()->json(['error' => 'Aksi tidak valid'], 400),
        };
    });

    // Verifikasi & Riwayat
    Route::get('/verifikasi/riwayat', [VerifikasiController::class, 'riwayat']);
    Route::post('/verifikasi/status', [VerifikasiController::class, 'updateStatus']);
    Route::any('/verifikasi.php', function (Request $request) {
        $action = $request->query('action', 'riwayat');
        $ctrl = app(VerifikasiController::class);
        return match ($action) {
            'riwayat' => $ctrl->riwayat($request),
            'update-status' => $ctrl->updateStatus($request),
            default => response()->json(['error' => 'Aksi tidak valid'], 400),
        };
    });

    // Manajemen Pengguna (Superadmin)
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::post('/users/toggle-status', [UserController::class, 'toggleStatus']);
    Route::post('/users/reset-password', [UserController::class, 'resetPassword']);
    Route::any('/users.php', function (Request $request) {
        $action = $request->query('action', 'list');
        $ctrl = app(UserController::class);
        return match ($action) {
            'list' => $ctrl->index($request),
            'add' => $ctrl->store($request),
            'toggle-status' => $ctrl->toggleStatus($request),
            'reset-password' => $ctrl->resetPassword($request),
            default => response()->json(['error' => 'Aksi tidak valid'], 400),
        };
    });

    // Log Aktivitas
    Route::get('/logs', [AuditLogController::class, 'index']);
    Route::get('/logs.php', [AuditLogController::class, 'index']);

    // Export Data (bisa lewat query token)
    Route::get('/export', [ExportController::class, 'export']);
    Route::get('/export.php', [ExportController::class, 'export']);
});

// Fallback untuk Export jika token dikirim via query string
Route::get('/export-public', [ExportController::class, 'export']);
