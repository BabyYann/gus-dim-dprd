<?php
/**
 * =======================================================================
 * DIAGNOSTIK KELAYAKAN WEBSOCKET & LARAVEL REVERB (cPanel / PHP 8.4)
 * =======================================================================
 * Skrip pengujian mandiri tanpa merusak atau mengubah konfigurasi produksi.
 * Cara Menjalankan via Terminal cPanel:
 *   cd ~/public_html/gusdim_project/backend
 *   php diagnostic_websocket.php
 * Atau via Web Browser:
 *   https://gusdim.com/diagnostic_websocket.php?token=gusdim2026
 */

$isCli = (php_sapi_name() === 'cli' || defined('STDIN'));
$webToken = $_GET['token'] ?? '';

if (!$isCli && $webToken !== 'gusdim2026') {
    http_response_code(403);
    header('Content-Type: text/plain');
    echo "Akses Ditolak. Gunakan Terminal cPanel (CLI) atau sertakan token otorisasi valid.\n";
    exit;
}

if (!$isCli) {
    header('Content-Type: text/plain; charset=utf-8');
}

echo "=======================================================================\n";
echo "  HASIL DIAGNOSTIK KELAYAKAN WEBSOCKET & LARAVEL REVERB\n";
echo "  Sistem Informasi Tim Pemenangan Gus Dim - Dapil Kraksaan Raya\n";
echo "=======================================================================\n\n";

$results = [];
$warnings = [];
$blockers = [];

// 1. CEK VERSI PHP & LINGKUNGAN
echo "[1/5] Memeriksa Versi PHP & Konfigurasi Dasar...\n";
$phpVersion = phpversion();
$isPhp82Plus = version_compare($phpVersion, '8.2.0', '>=');
echo "  - Versi PHP: " . $phpVersion . ($isPhp82Plus ? " [MEMENUHI SYARAT (>= 8.2)]" : " [TIDAK MEMENUHI SYARAT]") . "\n";
echo "  - SAPI: " . php_sapi_name() . "\n";
echo "  - Memory Limit: " . ini_get('memory_limit') . "\n";
echo "  - Max Execution Time: " . ini_get('max_execution_time') . " detik\n";

if (!$isPhp82Plus) {
    $blockers[] = "Versi PHP minimal 8.2 dibutuhkan untuk Laravel Reverb (terdeteksi: {$phpVersion}).";
}

// 2. CEK EKSTENSI PHP WAJIB & PENDUKUNG
echo "\n[2/5] Memeriksa Ekstensi Jaringan PHP...\n";
$extSockets = extension_loaded('sockets');
$extPcntl = extension_loaded('pcntl');
$extPosix = extension_loaded('posix');
$extOpenssl = extension_loaded('openssl');
$extCurl = extension_loaded('curl');

echo "  - ext-sockets (Wajib Reverb)   : " . ($extSockets ? "[AKTIF]" : "[TIDAK AKTIF]") . "\n";
echo "  - ext-pcntl   (Worker Forking) : " . ($extPcntl ? "[AKTIF]" : "[TIDAK AKTIF / OPTIONAL]") . "\n";
echo "  - ext-posix   (Process Signal) : " . ($extPosix ? "[AKTIF]" : "[TIDAK AKTIF / OPTIONAL]") . "\n";
echo "  - ext-openssl (WSS Encryption) : " . ($extOpenssl ? "[AKTIF]" : "[TIDAK AKTIF]") . "\n";
echo "  - ext-curl    (Event Broadcast): " . ($extCurl ? "[AKTIF]" : "[TIDAK AKTIF]") . "\n";

if (!$extSockets) {
    $blockers[] = "Ekstensi PHP 'sockets' tidak aktif. Reverb membutuhkan ext-sockets untuk event loop TCP.";
}
if (!$extOpenssl) {
    $warnings[] = "Ekstensi 'openssl' tidak terdeteksi. Enkripsi WSS langsung mungkin memerlukan reverse proxy SSL.";
}

// 3. CEK FUNGSI SISTEM YANG DINONAKTIFKAN (disable_functions)
echo "\n[3/5] Memeriksa Pembatasan Fungsi Sistem (disable_functions)...\n";
$disabledRaw = ini_get('disable_functions');
$disabled = array_map('trim', explode(',', strtolower($disabledRaw)));
$criticalFunctions = ['proc_open', 'proc_close', 'exec', 'shell_exec', 'passthru', 'system'];

foreach ($criticalFunctions as $fn) {
    $isOff = in_array($fn, $disabled);
    echo "  - Fungsi '{$fn}': " . ($isOff ? "[DINONAKTIFKAN HOSTING]" : "[TERSEDIA]") . "\n";
    if ($isOff && in_array($fn, ['proc_open', 'exec'])) {
        $warnings[] = "Fungsi '{$fn}' dinonaktifkan oleh administrator hosting. Artisan background spawn mungkin terhambat.";
    }
}

// 4. PENGUJIAN BINDING SOCKET KE PORT LOKAL
echo "\n[4/5] Menguji Izin Binding Socket ke Port Lokal (127.0.0.1)...\n";
$testPorts = [8080, 6001, 9090, 8085];
$bindSuccess = false;
$boundPort = null;

if ($extSockets) {
    foreach ($testPorts as $port) {
        $sock = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($sock === false) {
            continue;
        }
        socket_set_option($sock, SOL_SOCKET, SO_REUSEADDR, 1);
        $bound = @socket_bind($sock, '127.0.0.1', $port);
        if ($bound) {
            $bindSuccess = true;
            $boundPort = $port;
            @socket_close($sock);
            echo "  - Uji port {$port}: [BERHASIL BIND KE 127.0.0.1:{$port}]\n";
            break;
        } else {
            $errCode = socket_last_error($sock);
            $errMsg = socket_strerror($errCode);
            echo "  - Uji port {$port}: [GAGAL BIND ({$errMsg})]\n";
            @socket_close($sock);
        }
    }
} else {
    // Fallback uji dengan stream_socket_server
    foreach ($testPorts as $port) {
        $errno = 0;
        $errstr = '';
        $server = @stream_socket_server("tcp://127.0.0.1:{$port}", $errno, $errstr);
        if ($server) {
            $bindSuccess = true;
            $boundPort = $port;
            fclose($server);
            echo "  - Uji port {$port} (stream): [BERHASIL BIND KE 127.0.0.1:{$port}]\n";
            break;
        } else {
            echo "  - Uji port {$port} (stream): [GAGAL BIND ({$errstr})]\n";
        }
    }
}

if (!$bindSuccess) {
    $blockers[] = "Sistem operasi server melarang binding socket lokal pada port non-privilege. Reverb tidak dapat mendengarkan koneksi.";
}

// 5. KESIMPULAN & REKOMENDASI ARSITEKTUR
echo "\n=======================================================================\n";
echo "  KESIMPULAN KELAYAKAN TEKNOLOGI REAL-TIME\n";
echo "=======================================================================\n";

if (empty($blockers)) {
    echo "STATUS: SERVER ANDA MENDUKUNG WEBSOCKET (LARAVEL REVERB)\n\n";
    echo "Rincian Teknis:\n";
    echo "- PHP 8.4 dan socket binding pada port {$boundPort} bekerja dengan baik.\n";
    echo "- Langkah berikutnya: Jalankan 'php artisan reverb:start --port={$boundPort}' di background.\n";
    echo "- Rekomendasi Port: Gunakan reverse proxy Apache port 443 (WSS) atau port {$boundPort} jika firewall terbuka.\n";
} else {
    echo "STATUS: SHARED HOSTING MEMILIKI BATASAN LINGKUNGAN\n\n";
    echo "Faktor Pembatas:\n";
    foreach ($blockers as $b) {
        echo "  * " . $b . "\n";
    }
    echo "\nRekomendasi Strategis:\n";
    echo "1. Gunakan Driver PUSHER bawaan Laravel:\n";
    echo "   - Kode Laravel Event Broadcasting Anda tetap sama persis.\n";
    echo "   - Gratis 200.000 pesan/hari, 100% stabil, tidak membebani RAM hosting cPanel.\n";
    echo "2. Atau gunakan SERVER-SENT EVENTS (SSE):\n";
    echo "   - Streaming HTTP native tanpa perlu server daemon atau layanan luar.\n";
}

if (!empty($warnings)) {
    echo "\nCatatan Tambahan:\n";
    foreach ($warnings as $w) {
        echo "  ! " . $w . "\n";
    }
}
echo "=======================================================================\n";
