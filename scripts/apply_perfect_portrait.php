<?php

$srcFile = 'C:/Users/User/.gemini/antigravity/brain/dd13c00a-7496-4a71-9da1-4099a9679ae6/.user_uploaded/media_1792298903016_80ccd374.png';
$src = imagecreatefrompng($srcFile);
$sw = imagesx($src);
$sh = imagesy($src);

// 1024x1024 square canvas with transparent background
$canvasSize = 1024;
$target = imagecreatetruecolor($canvasSize, $canvasSize);
imagealphablending($target, false);
imagesavealpha($target, true);
$transparent = imagecolorallocatealpha($target, 0, 0, 0, 127);
imagefill($target, 0, 0, $transparent);
imagealphablending($target, true);

// Gus Dim's content is 678x946 (from y=77 to 1023).
// We scale by 0.90 so shoulders have breathing room on sides (width = 682 * 0.90 = 614px, canvas is 1024)
// Wait! If shoulders are 614px out of 1024, at bottom y=1024, the circle width is around 700px, so 614 fits with nice margin!
// Height of scaled image = 1024 * 0.90 = 922px.
// In the scaled image, songkok is at 77 * 0.90 = 69px.
// If we place with dy = 65px:
// Songkok will start at 65 + 69 = 134px (13% from top of canvas).
// Face will be at ~220px to 450px (upper-center of canvas, center is 512).
// Suit and tie will be at 450px to 990px.
$scale = 0.90;
$dw = (int)round($sw * $scale);
$dh = (int)round($sh * $scale);
$dx = (int)round(($canvasSize - $dw) / 2);
$dy = 65;

imagecopyresampled($target, $src, $dx, $dy, 0, 0, $dw, $dh, $sw, $sh);

$destFiles = [
    'assets/img/gus-dim.png',
    'assets/icons/gus-dim.png',
    'mobile/assets/img/gus-dim.png',
    'mobile/assets/icons/gus-dim.png',
    'backend/public/assets/img/gus-dim.png',
    'backend/public/assets/icons/gus-dim.png',
    'backend/public/mobile/assets/img/gus-dim.png',
    'backend/public/mobile/assets/icons/gus-dim.png'
];

foreach ($destFiles as $dest) {
    imagepng($target, $dest, 9);
    echo "Saved: $dest (" . filesize($dest) . " bytes)\n";
}

imagedestroy($target);
imagedestroy($src);
echo "Semua aset foto Gus Dim berhasil disempurnakan!\n";
