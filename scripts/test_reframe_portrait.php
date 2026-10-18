<?php

$srcFile = 'C:/Users/User/.gemini/antigravity/brain/dd13c00a-7496-4a71-9da1-4099a9679ae6/.user_uploaded/media_1792298903016_80ccd374.png';
$src = imagecreatefrompng($srcFile);
$sw = imagesx($src);
$sh = imagesy($src);

// Let's create a 1024x1024 square canvas with transparent background
$canvasSize = 1024;
$target = imagecreatetruecolor($canvasSize, $canvasSize);
imagealphablending($target, false);
imagesavealpha($target, true);
$transparent = imagecolorallocatealpha($target, 0, 0, 0, 127);
imagefill($target, 0, 0, $transparent);
imagealphablending($target, true);

// We want the content (which is 678x946) to fit nicely in 1024x1024
// Let's scale the source content.
// If target height of Gus Dim is say 920px (out of 1024):
// Scale factor = 920 / 946 = ~0.972
// Or if target width is say 750px (so shoulders have room):
// Scale factor = 750 / 682 = 1.10
// Target height = 1024 * 1.10 = 1126
// If target width is 720px:
$scale = 0.95; // slightly scale down so shoulders and head both fit comfortably
$dw = (int)round($sw * $scale);
$dh = (int)round($sh * $scale);

// Position horizontally center:
$dx = (int)round(($canvasSize - $dw) / 2);

// Position vertically:
// In the source, songkok is at y=77.
// In the scaled image, songkok is at 77 * scale = 73px.
// If we place the image with top offset $dy:
// Songkok will be at $dy + 73.
// Inside a 1024 circle, radius is 512, center is (512, 512).
// The top of the circle is at y = 0.
// If songkok is at y = 100, the distance from center is 512 - 100 = 412.
// Since 412 is well within 512 radius, the songkok will have 100px of breathing room!
// At y = 100, the width of the circle is 2 * sqrt(512^2 - (512-100)^2) = 2 * sqrt(262144 - 169744) = 608px!
// Songkok width is around 240px, so 608px easily accommodates the songkok without touching any edges!

$dy = 40; // so songkok starts at 40 + 73 = 113px from top of 1024 (11% from top)

imagecopyresampled($target, $src, $dx, $dy, 0, 0, $dw, $dh, $sw, $sh);

$outFile = 'assets/img/gus-dim-square.png';
imagepng($target, $outFile, 9);
imagedestroy($target);
imagedestroy($src);

echo "Generated $outFile successfully.\n";
