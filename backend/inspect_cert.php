<?php
/**
 * Inspeksi isi sertifikat SSL dan rantai CA untuk WebSocket TLS
 */
$certPath = '/home/gusdimco/ssl/certs/gusdim_com_942b3_5f7f1_1816581457_a7e27ef5e2b5042da5a2fb45fa9ecb5f.crt';
$cachePath = '/home/gusdimco/ssl/certs/gusdim_com_942b3_5f7f1_1816581457_a7e27ef5e2b5042da5a2fb45fa9ecb5f.crt.cache';
$keyPath = '/home/gusdimco/ssl/keys/942b3_5f7f1_f62bad886891b23764b7250cb82287ae.key';

echo "=== INSPEKSI SERTIFIKAT SSL CPANEL ===\n";

if (file_exists($certPath)) {
    $certContent = file_get_contents($certPath);
    $certData = openssl_x509_parse($certContent);
    echo "1. File CRT:\n";
    echo "   Subject: " . ($certData['name'] ?? 'N/A') . "\n";
    echo "   Issuer:  " . ($certData['issuer']['CN'] ?? 'N/A') . "\n";
    echo "   Valid To: " . date('Y-m-d H:i:s', $certData['validTo_time_t'] ?? 0) . "\n";
}

if (file_exists($cachePath)) {
    $cacheContent = file_get_contents($cachePath);
    echo "2. File CRT.CACHE:\n";
    echo "   Ukuran: " . strlen($cacheContent) . " bytes\n";
    preg_match_all('/-----BEGIN CERTIFICATE-----/', $cacheContent, $matches);
    echo "   Jumlah Sertifikat di dalam cache: " . count($matches[0]) . "\n";
}

if (file_exists($keyPath) && file_exists($certPath)) {
    $keyContent = file_get_contents($keyPath);
    $check = openssl_x509_check_private_key($certContent, $keyContent);
    echo "3. Kecocokan CRT dengan Kunci Privat: " . ($check ? "[COCOK 100%]" : "[TIDAK COCOK]") . "\n";
}

// Buat bundle lengkap otomatis jika cache berisi rantai lengkap
if (file_exists($cachePath) && file_exists($keyPath)) {
    $bundlePath = '/home/gusdimco/ssl/certs/gusdim_fullchain.crt';
    if (file_put_contents($bundlePath, $cacheContent)) {
        echo "4. Berhasil membuat file fullchain: {$bundlePath}\n";
    }
}
