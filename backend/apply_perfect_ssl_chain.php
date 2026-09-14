<?php
/**
 * =======================================================================
 * MEMASANG RANTAI SERTIFIKAT LENGKAP (FULL CA CHAIN) DARI PORT 443
 * =======================================================================
 * Mengambil intermediate cert YR1 & Root YR langsung dari port 443 Apache
 * sehingga Chrome dan semua browser otomatis percaya 100% tanpa error.
 */

echo "=== MENGAMBIL RANTAI SERTIFIKAT RESMI DARI PORT 443 ===\n\n";

$c = stream_context_create(['ssl' => ['capture_peer_cert_chain' => true, 'verify_peer' => false]]);
$s = @stream_socket_client('ssl://127.0.0.1:443', $e, $es, 5, STREAM_CLIENT_CONNECT, $c);
if (!$s) {
    $s = @stream_socket_client('ssl://gusdim.com:443', $e, $es, 5, STREAM_CLIENT_CONNECT, $c);
}

if (!$s) {
    echo "ERROR: Tidak dapat terhubung ke port 443: $es ($e)\n";
    exit(1);
}

$p = stream_context_get_params($s);
$chain = $p['options']['ssl']['peer_certificate_chain'] ?? [];
fclose($s);

if (empty($chain)) {
    echo "ERROR: Gagal membaca rantai sertifikat dari port 443!\n";
    exit(1);
}

echo "Ditemukan " . count($chain) . " sertifikat dalam rantai port 443:\n";
$chainPems = [];
foreach ($chain as $i => $cert) {
    $parsed = openssl_x509_parse($cert);
    $name = $parsed['name'] ?? '';
    $issuer = $parsed['issuer']['CN'] ?? '';
    echo "  [{$i}] {$name} (Diterbitkan oleh: {$issuer})\n";
    openssl_x509_export($cert, $pem);
    $chainPems[] = trim($pem);
}

$keyPath = '/home/gusdimco/ssl/keys/c5a4c_4c471_b7c177b6978b8bbc6d3682aed1d065f7.key';
if (!file_exists($keyPath)) {
    echo "ERROR: Kunci privat tidak ditemukan di {$keyPath}!\n";
    exit(1);
}

$keyContent = trim(file_get_contents($keyPath));

// Susun kombinasi sempurna: Leaf Cert + Private Key + Intermediate 1 + Intermediate 2
$comboContent = $chainPems[0] . "\n\n" . $keyContent . "\n\n";
for ($i = 1; $i < count($chainPems); $i++) {
    $comboContent .= $chainPems[$i] . "\n\n";
}

$comboFile = '/home/gusdimco/ssl/certs/reverb_combo.pem';
file_put_contents($comboFile, $comboContent);
chmod($comboFile, 0600);

echo "\nBerhasil membuat berkas rantai lengkap: {$comboFile} (" . strlen($comboContent) . " bytes)\n";

// Perbarui .env
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $env = file_get_contents($envFile);
    $env = preg_replace('/REVERB_TLS_CERT=.*/', 'REVERB_TLS_CERT=' . $comboFile, $env);
    $env = preg_replace('/REVERB_TLS_KEY=.*/', 'REVERB_TLS_KEY=' . $comboFile, $env);
    file_put_contents($envFile, $env);
    echo "Berhasil memperbarui .env dengan berkas rantai SSL lengkap!\n";
}
echo "=======================================================================\n";
