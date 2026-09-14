<?php
/**
 * =======================================================================
 * KONFIGURASI SSL WILDCARD RESMI SECTIGO (YR1) UNTUK REVERB
 * =======================================================================
 * Memilih sertifikat resmi *.gusdim.com (bukan self-signed)
 * dan menggabungkan hanya sertifikat + kunci privat tanpa ca-bundle 240KB.
 */

$certPath = '/home/gusdimco/ssl/certs/_wildcard__gusdim_com_c5a4c_4c471_1797140188_5e93ba01110fdd98d509d2613d42944d.crt';
$keyPath = '/home/gusdimco/ssl/keys/c5a4c_4c471_b7c177b6978b8bbc6d3682aed1d065f7.key';
$comboFile = '/home/gusdimco/ssl/certs/reverb_combo.pem';

echo "=== MEMASANG SERTIFIKAT RESMI WILDCARD GUS DIM ===\n\n";

if (!file_exists($certPath) || !file_exists($keyPath)) {
    echo "ERROR: Berkas sertifikat wildcard tidak ditemukan!\n";
    exit(1);
}

$certContent = trim(file_get_contents($certPath));
$keyContent = trim(file_get_contents($keyPath));

$certData = openssl_x509_parse($certContent);
echo "Sertifikat Terpilih:\n";
echo "  - Subject : " . ($certData['name'] ?? '') . "\n";
echo "  - Issuer  : " . ($certData['issuer']['CN'] ?? '') . "\n";
echo "  - Expire  : " . date('Y-m-d', $certData['validTo_time_t'] ?? 0) . "\n";

$check = openssl_x509_check_private_key($certContent, $keyContent);
echo "  - Kecocokan Kunci: " . ($check ? "[VALID 100%]" : "[TIDAK VALID]") . "\n\n";

if (!$check) {
    echo "ERROR: Kunci privat tidak cocok dengan sertifikat!\n";
    exit(1);
}

// Cek jika ada file intermediate cache khusus wildcard
$cacheFile = $certPath . '.cache';
$intermediate = '';
if (file_exists($cacheFile) && filesize($cacheFile) < 10000) {
    $intermediate = "\n" . trim(file_get_contents($cacheFile));
    echo "Menyertakan intermediate CA cache (" . filesize($cacheFile) . " bytes).\n";
}

// Gabungkan sertifikat dan private key
$comboContent = $certContent . "\n\n" . $keyContent . $intermediate . "\n";
file_put_contents($comboFile, $comboContent);
chmod($comboFile, 0600);

echo "Berhasil membuat file combo bersih: {$comboFile} (" . strlen($comboContent) . " bytes)\n";

// Perbarui .env
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $env = file_get_contents($envFile);
    $env = preg_replace('/REVERB_TLS_CERT=.*/', 'REVERB_TLS_CERT=' . $comboFile, $env);
    $env = preg_replace('/REVERB_TLS_KEY=.*/', 'REVERB_TLS_KEY=' . $comboFile, $env);
    file_put_contents($envFile, $env);
    echo "Berhasil memperbarui .env dengan sertifikat resmi Sectigo/YR1!\n";
}
echo "=======================================================================\n";
