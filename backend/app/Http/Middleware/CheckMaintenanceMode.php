<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Abaikan pengecekan jika rute adalah pengecualian internal atau membawa kunci bypass
        if ($request->is('up') || $request->is('maintenance-bypass')) {
            return $next($request);
        }

        $bypassKey = env('MAINTENANCE_BYPASS_KEY', '');
        if (!empty($bypassKey)) {
            $bypass = (string)($request->query('bypass') ?? $request->cookie('gusdim_bypass') ?? $request->header('X-Maintenance-Bypass', ''));
            if (hash_equals($bypassKey, $bypass)) {
                return $next($request);
            }
        }

        $isDown = file_exists(storage_path('framework/down'));
        $isLocked = file_exists(base_path('maintenance.lock')) || file_exists(base_path('../maintenance.lock'));
        $isEnv = env('APP_MAINTENANCE', false) === true || env('APP_MAINTENANCE', false) === 'true';

        if ($isDown || $isLocked || $isEnv) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'status' => false,
                    'maintenance' => true,
                    'message' => 'Sistem sedang dalam mode pemeliharaan (maintenance mode). Seluruh akses data ditutup sementara untuk perawatan sistem.'
                ], 503);
            }
            return response()->view('errors.503', [], 503);
        }

        return $next($request);
    }
}
