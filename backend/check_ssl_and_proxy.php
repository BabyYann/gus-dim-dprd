<?php
/**
 * =======================================================================
 * CEK SERTIFIKAT SSL & KEMAMPUAN PROXY UNTUK WSS (WEBSOCKET SECURE)
 * =======================================================================
 * Menemukan lokasi SSL cPanel untuk mengaktifkan WSS di port 6001.
 */

$isCli = (php_sapi_name() === 'cli' || defined('STDIN'));
if (!$isCli) {
    header('Content-Type: text/plain; charset=utf-8');
}

echo "=======================================================================\n";
echo "  PEMERIKSAAN SSL & PROXY UNTUK WEBSOCKET SECURE (WSS)\n";
echo "=======================================================================\n\n";

// 1. CARI SERTIFIKAT SSL CPANEL
echo "[1] Mencari File Sertifikat SSL di Direktori Akun cPanel...\n";

$home = getenv('HOME') ?: (isset($_SERVER['HOME']) ? $_SERVER['HOME'] : '');
if (!$home && function_exists('posix_getpwuid') && function_exists('posix_geteuid')) {
    $info = posix_getpwuid(posix_geteuid());
    $home = $info['dir'] ?? '';
}
if (!$home) {
    $home = '/home/gusdimco';
}

$searchPaths = [
    $home . '/ssl/certs',
    $home . '/ssl/keys',
    $home . '/var/cpanel/ssl/apache_tls',
    '/var/cpanel/ssl/apache_tls/gusdim.com',
    '/var/cpanel/ssl/apache_tls/gusdim.com/combined',
    '/etc/ssl/certs',
];

$foundCerts = [];
$foundKeys = [];

foreach ($searchPaths as $path) {
    if (file_exists($path)) {
        echo "  - Direktori/File ada: {$path}\n";
        if (is_dir($path)) {
            $files = @scandir($path);
            if ($files) {
                foreach ($files as $f) {
                    if ($f === '.' || $f === '..') continue;
                    $fullPath = $path . '/' . $f;
                    if (is_readable($fullPath)) {
                        echo "    * Ditemukan: {$f} (" . filesize($fullPath) . " bytes)\n";
                        if (str_ends_with($f, '.crt') || str_ends_with($f, '.pem') || str_contains($f, 'cert')) {
                            $foundCerts[] = $fullPath;
                        }
                        if (str_ends_with($f, '.key') || str_contains($f, 'key')) {
                            $foundKeys[] = $fullPath;
                        }
                    }
                }
            }
        } elseif (is_readable($path)) {
            echo "    * File readable: {$path} (" . filesize($path) . " bytes)\n";
            $foundCerts[] = $path;
        }
    } else {
        echo "  - Tidak dapat diakses / belum ada: {$path}\n";
    }
}

// 2. CEK APACHE MODULES
echo "\n[2] Memeriksa Modul Apache (jika terdeteksi)...\n";
if (function_exists('apache_get_modules')) {
    $mods = apache_get_modules();
    $checkMods = ['mod_rewrite', 'mod_proxy', 'mod_proxy_wstunnel', 'mod_proxy_http', 'mod_ssl'];
    foreach ($checkMods as $m) {
        $status = in_array($m, $mods) ? "[TERPASANG]" : "[TIDAK TERPASANG]";
        echo "  - {$m}: {$status}\n";
    }
} else {
    echo "  - apache_get_modules() tidak tersedia pada PHP CLI / SAPI ini (Normal pada CloudLinux).\n";
}

// 3. REKOMENDASI HASIL
echo "\n=======================================================================\n";
echo "  HASIL ANALISIS KONEKSI WEBSOCKET BROWSER\n";
echo "=======================================================================\n";

if (!empty($foundCerts) && !empty($foundKeys)) {
    echo "STATUS: SERTIFIKAT SSL DITEMUKAN UNTUK REVERB WSS LANGSUNG!\n";
    echo "Cert: " . $foundCerts[0] . "\n";
    echo "Key:  " . $foundKeys[0] . "\n";
    echo "Reverb dapat berjalan langsung di wss://gusdim.com:6001\n";
} else {
    echo "STATUS LOKASI SSL:\n";
    echo "Pencarian awal di folder umum selesai.\n";
}
echo "=======================================================================\n";
