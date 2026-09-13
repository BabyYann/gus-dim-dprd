<?php
/**
 * Konfigurasi Utama Sistem Informasi GUS DIM
 * Kompatibel dengan cPanel & Localhost Development
 */

// 1. PENGATURAN DATABASE MYSQL (SESUAIKAN DENGAN CPANEL ANDA)
define('DB_HOST', 'localhost');
define('DB_NAME', 'gusdim_db');         // Contoh di cPanel: u123456_gusdim
define('DB_USER', 'root');              // Contoh di cPanel: u123456_user
define('DB_PASS', '');                  // Password database MySQL Anda
define('DB_CHARSET', 'utf8mb4');

// 2. PENGATURAN APLIKASI & URL
define('APP_NAME', 'GUS DIM - Data Pendukung DPRD');
define('APP_DAPIL', 'Dapil Kraksaan, Besuk, Gading');
define('APP_URL', '');                  // Biarkan kosong untuk auto-detect domain cPanel

// 3. FOLDER UPLOAD
define('UPLOAD_DIR', dirname(__DIR__) . '/uploads');
define('UPLOAD_KTP_DIR', UPLOAD_DIR . '/ktp');
define('UPLOAD_FOTO_DIR', UPLOAD_DIR . '/foto');
define('UPLOAD_AVATAR_DIR', UPLOAD_DIR . '/avatars');

// 4. MASA AKTIF TOKEN SESI LOGIN (30 HARI)
define('TOKEN_EXPIRY_DAYS', 30);

// 5. PENANGANAN ERROR (Nonaktifkan display_errors saat live production)
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Pastikan folder upload ada
foreach ([UPLOAD_DIR, UPLOAD_KTP_DIR, UPLOAD_FOTO_DIR, UPLOAD_AVATAR_DIR] as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}
