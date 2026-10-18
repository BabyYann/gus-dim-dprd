<?php
$bypass = $_GET['bypass'] ?? $_COOKIE['gusdim_bypass'] ?? ($_SERVER['HTTP_X_MAINTENANCE_BYPASS'] ?? '');
if ($bypass === 'gusdim2025') {
    setcookie('gusdim_bypass', 'gusdim2025', time() + (3600 * 24 * 7), '/');
} else {
    $lock1 = __DIR__ . '/../maintenance.lock';
    $lock2 = __DIR__ . '/../../maintenance.lock';
    $down1 = __DIR__ . '/../storage/framework/down';
    $down2 = __DIR__ . '/../../storage/framework/down';

    if (file_exists($lock1) || file_exists($lock2) || file_exists($down1) || file_exists($down2)) {
        http_response_code(503);
        if (file_exists(__DIR__ . '/503.html')) {
            require_once __DIR__ . '/503.html';
            exit;
        } elseif (file_exists(__DIR__ . '/../503.html')) {
            require_once __DIR__ . '/../503.html';
            exit;
        }
    }
}

require_once __DIR__ . '/index.html';
