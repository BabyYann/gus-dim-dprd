<?php
/**
 * =======================================================================
 * PEMBUATAN FILE KOMBINASI SSL (PEM) UNTUK LARAVEL REVERB WSS
 * =======================================================================
 * Menggabungkan Sertifikat Asli, Private Key, dan CA Bundle ke 1 file .pem
 * sehingga ReactPHP / Reverb tidak mengalami kegagalan handshake TLS.
 */

$isCli = (php_sapi_name() === 'cli' || defined('STDIN'));
if (!$isCli) {
    header('Content-Type: text/plain; charset=utf-8');
}

echo "=== MEMPERSIAPKAN SERTIFIKAT SSL RESMI UNTUK REVERB ===\n\n";

$certsDir = '/home/gusdimco/ssl/certs';
$keysDir = '/home/gusdimco/ssl/keys';

$certFiles = glob($certsDir . '/*.crt');
$keyFiles = glob($keysDir . '/*.key');

$bestCert = null;
$bestKey = null;
$bestCertInfo = null;

// Cari sertifikat resmi (bukan self-signed, atau yang dikeluarkan oleh CA YR1/Sectigo/cPanel)
foreach ($certFiles as $c) {
    $content = file_get_contents($c);
    $data = openssl_x509_parse($content);
    if (!$data) continue;

    $subject = $data['name'] ?? '';
    $issuer = $data['issuer']['CN'] ?? '';
    $validTo = $data['validTo_time_t'] ?? 0;

    echo "Memeriksa: " . basename($c) . "\n";
    echo "  - Subject : {$subject}\n";
    echo "  - Issuer  : {$issuer}\n";
    echo "  - Expire  : " . date('Y-m-d', $validTo) . "\n";

    // Cari key yang cocok
    $matchedKey = null;
    foreach ($keyFiles as $k) {
        $kContent = file_get_contents($k);
        if (openssl_x509_check_private_key($content, $kContent)) {
            $matchedKey = $k;
            break;
        }
    }

    if ($matchedKey) {
        echo "  - Key     : [COCOK] " . basename($matchedKey) . "\n";
        // Prioritaskan yang bukan self-signed (Issuer != Subject)
        if ($issuer !== $subject || str_contains($subject, 'gusdim.com')) {
            $bestCert = $c;
            $bestKey = $matchedKey;
            $bestCertInfo = $data;
        }
    } else {
        echo "  - Key     : [TIDAK ADA KEY COCOK]\n";
    }
    echo "\n";
}

if (!$bestCert || !$bestKey) {
    echo "Gagal: Tidak ditemukan pasangan CRT dan KEY yang valid!\n";
    exit(1);
}

echo "PASANGAN TERBAIK TERPILIH:\n";
echo "  Cert: " . basename($bestCert) . "\n";
echo "  Key : " . basename($bestKey) . "\n\n";

// Baca CA Bundle jika ada
$caBundle = '';
$possibleCa = [
    '/etc/ssl/certs/ca-bundle.crt',
    '/home/gusdimco/ssl/certs/ca-bundle.crt',
    $bestCert . '.cache'
];
foreach ($possibleCa as $caPath) {
    if (file_exists($caPath)) {
        $caBundle = file_get_contents($caPath);
        echo "Menambahkan Rantai CA dari: " . basename($caPath) . "\n";
        break;
    }
}

// Gabungkan menjadi reverb_combo.pem
$comboContent = trim(file_get_contents($bestCert)) . "\n\n" .
                trim(file_get_contents($bestKey)) . "\n";

if ($caBundle && !str_contains($comboContent, trim($caBundle))) {
    $comboContent .= "\n" . trim($caBundle) . "\n";
}

$comboFile = '/home/gusdimco/ssl/certs/reverb_combo.pem';
file_put_contents($comboFile, $comboContent);
chmod($comboFile, 0600);
echo "Berhasil membuat berkas kombinasi SSL: {$comboFile}\n";

// Perbarui .env
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $env = file_get_contents($envFile);
    $env = preg_replace('/REVERB_TLS_CERT=.*/', 'REVERB_TLS_CERT=' . $comboFile, $env);
    $env = preg_replace('/REVERB_TLS_KEY=.*/', 'REVERB_TLS_KEY=' . $comboFile, $env);
    file_put_contents($envFile, $env);
    echo "Berhasil memperbarui .env dengan REVERB_TLS_CERT={$comboFile}\n";
}

echo "\nUji verifikasi SSL mandiri pada socket lokal...\n";
$ctx = stream_context_create([
    'ssl' => [
        'local_cert' => $comboFile,
        'local_pk' => $comboFile,
        'verify_peer' => false,
    ]
]);

$testServer = @stream_socket_server('tls://127.0.0.1:6005', $errno, $errstr, STREAM_SERVER_BIND|STREAM_SERVER_LISTEN, $ctx);
if ($testServer) {
    echo "SUKSES: Server TLS mandiri berhasil di-bind ke 127.0.0.1:6005 dengan berkas combo!\n";
    fclose($testServer);
} else {
    echo "Catatan Uji Local: {$errstr} ({$errno})\n";
}
echo "=======================================================================\n";
