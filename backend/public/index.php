<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. Prioritas Utama: Pemeriksaan Maintenance Mode Tanpa Beban Database/Framework
$bypass = $_GET['bypass'] ?? $_COOKIE['gusdim_bypass'] ?? ($_SERVER['HTTP_X_MAINTENANCE_BYPASS'] ?? '');
if ($bypass !== 'gusdim2025') {
    $lockCandidates = [
        __DIR__ . '/maintenance.lock',
        __DIR__ . '/backend/maintenance.lock',
        __DIR__ . '/../maintenance.lock',
        __DIR__ . '/../backend/maintenance.lock',
        __DIR__ . '/gusdim_project/backend/maintenance.lock',
        __DIR__ . '/../gusdim_project/backend/maintenance.lock',
        dirname(__DIR__) . '/maintenance.lock',
    ];
    $isMaintenance = false;
    foreach ($lockCandidates as $lock) {
        if (file_exists($lock)) {
            $isMaintenance = true;
            break;
        }
    }
    if ($isMaintenance) {
        http_response_code(503);
        $htmlCandidates = [
            __DIR__ . '/503.html',
            __DIR__ . '/backend/public/503.html',
            __DIR__ . '/../503.html',
            __DIR__ . '/gusdim_project/backend/public/503.html',
            __DIR__ . '/../gusdim_project/backend/public/503.html',
        ];
        foreach ($htmlCandidates as $html) {
            if (file_exists($html)) {
                require $html;
                exit;
            }
        }
    }
}

// 2. Auto-detect direktori inti Laravel secara cerdas dan aman
$coreDir = null;
$candidates = [
    __DIR__ . '/backend',
    __DIR__ . '/../backend',
    __DIR__ . '/gusdim_project/backend',
    __DIR__ . '/../gusdim_project/backend',
    __DIR__ . '/../gusdim_core',
    dirname(__DIR__) . '/backend',
    __DIR__ . '/..',
    __DIR__,
];

foreach ($candidates as $cand) {
    if (file_exists($cand . '/bootstrap/app.php') && file_exists($cand . '/vendor/autoload.php')) {
        $coreDir = realpath($cand) ?: $cand;
        break;
    }
}

if (!$coreDir || !file_exists($coreDir . '/vendor/autoload.php')) {
    http_response_code(503);
    if (file_exists(__DIR__ . '/503.html')) {
        require __DIR__ . '/503.html';
        exit;
    }
    die('Sistem sedang dalam proses pemeliharaan. Silakan muat ulang halaman beberapa saat lagi.');
}

if (file_exists($maintenance = $coreDir . '/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $coreDir . '/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once $coreDir . '/bootstrap/app.php';

$app->usePublicPath(__DIR__);

$app->handleRequest(Request::capture());
