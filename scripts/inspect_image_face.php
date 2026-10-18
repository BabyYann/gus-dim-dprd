<?php

$imgPath = 'assets/img/gus-dim.png';
$img = imagecreatefrompng($imgPath);
$w = imagesx($img);
$h = imagesy($img);

$minX = $w; $maxX = 0;
$minY = $h; $maxY = 0;

for ($y = 0; $y < $h; $y++) {
    for ($x = 0; $x < $w; $x++) {
        $rgb = imagecolorat($img, $x, $y);
        $colors = imagecolorsforindex($img, $rgb);
        // non-transparent and not white background
        if ($colors['alpha'] < 100 && ($colors['red'] < 250 || $colors['green'] < 250 || $colors['blue'] < 250)) {
            if ($x < $minX) $minX = $x;
            if ($x > $maxX) $maxX = $x;
            if ($y < $minY) $minY = $y;
            if ($y > $maxY) $maxY = $y;
        }
    }
}

echo "Content bounds: X = [$minX .. $maxX] (width: " . ($maxX - $minX) . ")\n";
echo "Content bounds: Y = [$minY .. $maxY] (height: " . ($maxY - $minY) . ")\n";
echo "Total canvas: {$w}x{$h}\n";
echo "Top space above songkok: $minY px (" . round(($minY / $h) * 100, 1) . "%)\n";
echo "Bottom space: " . ($h - $maxY) . " px\n";
