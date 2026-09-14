<?php
/**
 * =======================================================================
 * PENGUJIAN LIVE SERVER LARAVEL REVERB & BROADCAST EVENT
 * =======================================================================
 * Skrip ini menguji:
 * 1. Apakah server Reverb sedang aktif mendengarkan di port 6001.
 * 2. Menguji pengiriman broadcast event real-time ke Reverb.
 *
 * Cara Menjalankan di Terminal cPanel:
 *   cd ~/public_html/gusdim_project/backend
 *   php test_reverb_live.php
 */

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$isCli = (php_sapi_name() === 'cli' || defined('STDIN'));
if (!$isCli) {
    header('Content-Type: text/plain; charset=utf-8');
}

echo "=======================================================================\n";
echo "  PENGUJIAN WEBSOCKET LARAVEL REVERB & EVENT BROADCASTING\n";
echo "=======================================================================\n\n";

$host = env('REVERB_HOST', 'gusdim.com');
$port = env('REVERB_PORT', 6001);
$scheme = env('REVERB_SCHEME', 'https');
$appKey = env('REVERB_APP_KEY', 'ifst0r0e5f3stkfy1k6g');

echo "[1] Memeriksa Parameter Konfigurasi .env:\n";
echo "  - BROADCAST_CONNECTION : " . env('BROADCAST_CONNECTION') . "\n";
echo "  - REVERB_HOST          : " . $host . "\n";
echo "  - REVERB_PORT          : " . $port . "\n";
echo "  - REVERB_SCHEME        : " . $scheme . "\n";
echo "  - REVERB_APP_KEY       : " . $appKey . "\n";
echo "  - REVERB_TLS_CERT      : " . (env('REVERB_TLS_CERT') ?: '(tidak disetel)') . "\n";
echo "  - REVERB_TLS_KEY       : " . (env('REVERB_TLS_KEY') ?: '(tidak disetel)') . "\n\n";

// 2. Cek apakah port 6001 sedang aktif mendengarkan
echo "[2] Memeriksa Status Layanan Socket di Port {$port}...\n";
$conn = @fsockopen('127.0.0.1', $port, $errno, $errstr, 2);
if ($conn) {
    fclose($conn);
    echo "  - Server Reverb: [AKTIF MENDENGARKAN DI 127.0.0.1:{$port}]\n\n";
} else {
    echo "  - Server Reverb: [BELUM AKTIF / TIDAK TERDETEKSI]\n";
    echo "    Jalankan perintah ini di jendela terminal lain atau via background:\n";
    echo "    php artisan reverb:start --host=0.0.0.0 --port=6001\n\n";
}

// 3. Uji Broadcast Event Dummy
echo "[3] Menguji Dispatch Event Real-Time (PendukungCreated)...\n";
try {
    $dummy = new \App\Models\Pendukung([
        'id' => 9999,
        'nama' => 'Uji WebSocket Real-Time',
        'jalur' => 'RELAWAN',
        'kecamatan' => 'Kraksaan',
        'desa' => 'Sidopekso',
        'input_by_user_name' => 'Sistem Verifikasi',
    ]);

    event(new \App\Events\PendukungCreated($dummy));
    echo "  - Status Broadcast: [BERHASIL DISPATCH KE CHANNEL 'gusdim-updates']\n";
} catch (\Throwable $e) {
    echo "  - Status Broadcast: [CATATAN/GAGAL] " . $e->getMessage() . "\n";
}

echo "\n=======================================================================\n";
echo "  PENGUJIAN KONEKSI DARI BROWSER (CLIENT WSS)\n";
echo "=======================================================================\n";
$wsProtocol = ($scheme === 'https') ? 'wss' : 'ws';
$clientUrl = "{$wsProtocol}://{$host}:{$port}/app/{$appKey}";
echo "URL WebSocket Client:\n";
echo "  {$clientUrl}\n\n";
echo "Kode JavaScript Pengujian Langsung di Browser Console (F12):\n";
echo "-----------------------------------------------------------------------\n";
echo "const ws = new WebSocket('{$clientUrl}');\n";
echo "ws.onopen = () => console.log('SUKSES: Terhubung ke Laravel Reverb!');\n";
echo "ws.onmessage = (e) => console.log('EVENT DITERIMA:', e.data);\n";
echo "ws.onerror = (e) => console.error('ERROR WEBSOCKET:', e);\n";
echo "-----------------------------------------------------------------------\n";
