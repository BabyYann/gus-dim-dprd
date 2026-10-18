<?php

$dirUploaded = 'C:/Users/User/.gemini/antigravity/brain/dd13c00a-7496-4a71-9da1-4099a9679ae6/.user_uploaded/';
$logoSrc = $dirUploaded . 'media_1792298768688_f51bc19f.jpg';
$gusDimSrc = $dirUploaded . 'media_1792298903016_80ccd374.png';

// 1. Process Logo (JPG -> PNG)
$logoImg = imagecreatefromjpeg($logoSrc);
if (!$logoImg) {
    die("Gagal memuat logo JPEG\n");
}

$logoPngTmp = sys_get_temp_dir() . '/app-logo-tmp.png';
imagepng($logoImg, $logoPngTmp, 9);
imagedestroy($logoImg);

$logoDestinations = [
    'assets/icons/app-logo.png',
    'assets/icons/logo.png',
    'mobile/assets/icons/app-logo.png',
    'mobile/assets/icons/logo.png',
    'backend/public/assets/icons/app-logo.png',
    'backend/public/assets/icons/logo.png',
    'backend/public/mobile/assets/icons/app-logo.png',
    'backend/public/mobile/assets/icons/logo.png'
];

foreach ($logoDestinations as $dest) {
    $parent = dirname($dest);
    if (!is_dir($parent)) {
        mkdir($parent, 0777, true);
    }
    copy($logoPngTmp, $dest);
    echo "Disalin: $dest (" . filesize($dest) . " bytes)\n";
}
unlink($logoPngTmp);

// 2. Process Gus Dim Formal Portrait
$gusDimDestinations = [
    'assets/img/gus-dim.png',
    'assets/icons/gus-dim.png',
    'mobile/assets/img/gus-dim.png',
    'mobile/assets/icons/gus-dim.png',
    'backend/public/assets/img/gus-dim.png',
    'backend/public/assets/icons/gus-dim.png',
    'backend/public/mobile/assets/img/gus-dim.png',
    'backend/public/mobile/assets/icons/gus-dim.png'
];

foreach ($gusDimDestinations as $dest) {
    $parent = dirname($dest);
    if (!is_dir($parent)) {
        mkdir($parent, 0777, true);
    }
    copy($gusDimSrc, $dest);
    echo "Disalin: $dest (" . filesize($dest) . " bytes)\n";
}

echo "\nSemua aset gambar berhasil disalin dan diperbarui!\n";
