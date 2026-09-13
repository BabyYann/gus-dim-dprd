<?php
/**
 * Konfigurasi Utama Sistem Informasi GUS DIM
 * Kompatibel dengan cPanel & Localhost Development (Auto-load .env)
 */

// Fungsi Helper Pembaca .env Tanpa Dependensi Pihak Ketiga
if (!function_exists('gusdim_load_env')) {
    function gusdim_load_env(): array {
        $envData = [];
        $candidates = [
            dirname(__DIR__, 2) . '/.env',                     // backend/.env
            dirname(__DIR__) . '/.env',                        // public/.env
            dirname(__DIR__, 3) . '/backend/.env',             // parent/backend/.env
            ($_SERVER['DOCUMENT_ROOT'] ?? '') . '/gusdim_project/backend/.env',
            ($_SERVER['DOCUMENT_ROOT'] ?? '') . '/../backend/.env',
            ($_SERVER['DOCUMENT_ROOT'] ?? '') . '/.env'
        ];

        foreach ($candidates as $file) {
            if ($file && file_exists($file) && is_readable($file)) {
                $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    $line = trim($line);
                    if (!$line || $line[0] === '#') continue;
                    if (strpos($line, '=') !== false) {
                        list($key, $val) = explode('=', $line, 2);
                        $key = trim($key);
                        $val = trim($val);
                        if (strlen($val) >= 2 && (($val[0] === '"' && substr($val, -1) === '"') || ($val[0] === "'" && substr($val, -1) === "'"))) {
                            $val = substr($val, 1, -1);
                        }
                        $envData[$key] = $val;
                    }
                }
                break; // Gunakan berkas pertama yang ditemukan
            }
        }
        return $envData;
    }
}

$loadedEnv = gusdim_load_env();

// 1. PENGATURAN DATABASE MYSQL (Otomatis dari .env jika ada, atau fallback lokal)
if (!defined('DB_HOST')) define('DB_HOST', $loadedEnv['DB_HOST'] ?? getenv('DB_HOST') ?: '127.0.0.1');
if (!defined('DB_PORT')) define('DB_PORT', $loadedEnv['DB_PORT'] ?? getenv('DB_PORT') ?: '3306');
if (!defined('DB_NAME')) define('DB_NAME', $loadedEnv['DB_DATABASE'] ?? getenv('DB_DATABASE') ?: 'gusdim_db');
if (!defined('DB_USER')) define('DB_USER', $loadedEnv['DB_USERNAME'] ?? getenv('DB_USERNAME') ?: 'root');
if (!defined('DB_PASS')) define('DB_PASS', $loadedEnv['DB_PASSWORD'] ?? getenv('DB_PASSWORD') ?: '');
if (!defined('DB_CHARSET')) define('DB_CHARSET', 'utf8mb4');

// 2. PENGATURAN APLIKASI & URL
if (!defined('APP_NAME')) define('APP_NAME', $loadedEnv['APP_NAME'] ?? 'GUS DIM - Data Pendukung DPRD');
if (!defined('APP_DAPIL')) define('APP_DAPIL', 'Dapil Kraksaan, Besuk, Gading');
if (!defined('APP_URL')) define('APP_URL', $loadedEnv['APP_URL'] ?? '');

// 3. FOLDER UPLOAD
if (!defined('UPLOAD_DIR')) define('UPLOAD_DIR', dirname(__DIR__) . '/uploads');
if (!defined('UPLOAD_KTP_DIR')) define('UPLOAD_KTP_DIR', UPLOAD_DIR . '/ktp');
if (!defined('UPLOAD_FOTO_DIR')) define('UPLOAD_FOTO_DIR', UPLOAD_DIR . '/foto');
if (!defined('UPLOAD_AVATAR_DIR')) define('UPLOAD_AVATAR_DIR', UPLOAD_DIR . '/avatars');

// 4. MASA AKTIF TOKEN SESI LOGIN (30 HARI)
if (!defined('TOKEN_EXPIRY_DAYS')) define('TOKEN_EXPIRY_DAYS', 30);

// 5. PENANGANAN ERROR
$appDebug = strtolower($loadedEnv['APP_DEBUG'] ?? 'false') === 'true';
ini_set('display_errors', $appDebug ? 1 : 0);
error_reporting(E_ALL);

// Pastikan folder upload ada
foreach ([UPLOAD_DIR, UPLOAD_KTP_DIR, UPLOAD_FOTO_DIR, UPLOAD_AVATAR_DIR] as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}
