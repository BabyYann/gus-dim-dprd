<?php

$src = imagecreatefrompng('assets/img/gus-dim-square.png');
$w = imagesx($src);
$h = imagesy($src);

// Simulate 74x74 circle
$size = 74;
$circle = imagecreatetruecolor($size, $size);
imagealphablending($circle, false);
imagesavealpha($circle, true);
$trans = imagecolorallocatealpha($circle, 0, 0, 0, 127);
imagefill($circle, 0, 0, $trans);
imagealphablending($circle, true);

imagecopyresampled($circle, $src, 0, 0, 0, 0, $size, $size, $w, $h);

// Check pixels at the top center of the 74x74 circle:
// Center is x = 37, y = 0 to 15.
echo "Pixel alpha/color at top of 74x74 circle (x=37):\n";
for ($y = 0; $y < 20; $y++) {
    $rgb = imagecolorat($circle, 37, $y);
    $c = imagecolorsforindex($circle, $rgb);
    $isSongkok = ($c['alpha'] < 50 && $c['red'] < 80 && $c['green'] < 80 && $c['blue'] < 80);
    echo "y=$y: R={$c['red']}, G={$c['green']}, B={$c['blue']}, A={$c['alpha']}" . ($isSongkok ? " [SONGKOK DETECTED!]" : "") . "\n";
}
