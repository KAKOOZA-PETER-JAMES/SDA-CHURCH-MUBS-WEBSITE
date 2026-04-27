<?php
$path = __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . '1.png';

if (!extension_loaded('gd')) {
    fwrite(STDERR, "GD extension is not enabled.\n");
    exit(1);
}

$image = @imagecreatefrompng($path);
if (!$image) {
    fwrite(STDERR, "Failed to open image: {$path}\n");
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

        // Convert near-white background to full transparency.
        if ($r >= 235 && $g >= 235 && $b >= 235) {
            $transparent = imagecolorallocatealpha($image, $r, $g, $b, 127);
            imagesetpixel($image, $x, $y, $transparent);
        }
    }
}

if (!imagepng($image, $path)) {
    imagedestroy($image);
    fwrite(STDERR, "Failed to save image: {$path}\n");
    exit(1);
}

imagedestroy($image);

echo "Updated {$path} with transparent background pixels." . PHP_EOL;
