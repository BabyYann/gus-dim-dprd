<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Auto-detect core directory across various hosting layouts
if (file_exists(__DIR__.'/gusdim_project/backend/bootstrap/app.php')) {
    $coreDir = __DIR__.'/gusdim_project/backend';
} elseif (file_exists(__DIR__.'/../gusdim_project/backend/bootstrap/app.php')) {
    $coreDir = __DIR__.'/../gusdim_project/backend';
} elseif (file_exists(__DIR__.'/../gusdim_core/bootstrap/app.php')) {
    $coreDir = __DIR__.'/../gusdim_core';
} else {
    $coreDir = __DIR__.'/..';
}

// Determine if the application is in maintenance mode...
$bypass = $_GET['bypass'] ?? $_COOKIE['gusdim_bypass'] ?? ($_SERVER['HTTP_X_MAINTENANCE_BYPASS'] ?? '');
if ($bypass !== 'gusdim2025') {
    if (file_exists($coreDir.'/maintenance.lock') || file_exists(__DIR__.'/maintenance.lock')) {
        http_response_code(503);
        if (file_exists(__DIR__.'/503.html')) {
            require __DIR__.'/503.html';
            exit;
        }
    }
}

if (file_exists($maintenance = $coreDir.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $coreDir.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once $coreDir.'/bootstrap/app.php';

$app->usePublicPath(__DIR__);

$app->handleRequest(Request::capture());
