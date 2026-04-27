<?php
$srcPath = __DIR__ . '/public/tp.png';
$outPath = __DIR__ . '/public/tp-clean.png';

if (!extension_loaded('gd')) {
    fwrite(STDERR, "GD extension is not enabled.\n");
    exit(1);
}

$src = @imagecreatefrompng($srcPath);
if (!$src) {
    fwrite(STDERR, "Failed to open source image: {$srcPath}\n");
    exit(1);
}

$width = imagesx($src);
$height = imagesy($src);

$corner = imagecolorsforindex($src, imagecolorat($src, 0, 0));
$bgR = $corner['red'];
$bgG = $corner['green'];
$bgB = $corner['blue'];

$dst = imagecreatetruecolor($width, $height);
imagealphablending($dst, false);
imagesavealpha($dst, true);

$transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
imagefilledrectangle($dst, 0, 0, $width, $height, $transparent);

$tolerance = 12;

for ($y = 0; $y < $height; $y++) {
    for ($x = 0; $x < $width; $x++) {
        $index = imagecolorat($src, $x, $y);
        $rgba = imagecolorsforindex($src, $index);

        $r = $rgba['red'];
        $g = $rgba['green'];
        $b = $rgba['blue'];

        $distance = abs($r - $bgR) + abs($g - $bgG) + abs($b - $bgB);

        if ($distance <= $tolerance) {
            imagesetpixel($dst, $x, $y, $transparent);
            continue;
        }

        $color = imagecolorallocatealpha($dst, $r, $g, $b, 0);
        imagesetpixel($dst, $x, $y, $color);
    }
}

if (!imagepng($dst, $outPath)) {
    imagedestroy($src);
    imagedestroy($dst);
    fwrite(STDERR, "Failed to save output image: {$outPath}\n");
    exit(1);
}

imagedestroy($src);
imagedestroy($dst);

echo "Created {$outPath}" . PHP_EOL;
