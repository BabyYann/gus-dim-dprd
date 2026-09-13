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
