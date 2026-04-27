<?php
$src = __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'sda logo.png';
$out = __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'sda-logo-transparent.png';

if (!extension_loaded('gd')) {
    fwrite(STDERR, "GD extension is not enabled.\n");
    exit(1);
}

$image = @imagecreatefrompng($src);
if (!$image) {
    fwrite(STDERR, "Failed to open source image: {$src}\n");
    exit(1);
}

$width = imagesx($image);
$height = imagesy($image);

imagealphablending($image, false);
imagesavealpha($image, true);

for ($y = 0; $y < $height; $y++) {
    for ($x = 0; $x < $width; $x++) {
        $index = imagecolorat($image, $x, $y);
        $rgba = imagecolorsforindex($image, $index);

        $r = $rgba['red'];
        $g = $rgba['green'];
        $b = $rgba['blue'];

        // Make near-white pixels fully transparent
        if ($r > 240 && $g > 240 && $b > 240) {
            $transparent = imagecolorallocatealpha($image, $r, $g, $b, 127);
            imagesetpixel($image, $x, $y, $transparent);
        }
    }
}

if (!imagepng($image, $out)) {
    imagedestroy($image);
    fwrite(STDERR, "Failed to save output image: {$out}\n");
    exit(1);
}

imagedestroy($image);

echo $out . PHP_EOL;
