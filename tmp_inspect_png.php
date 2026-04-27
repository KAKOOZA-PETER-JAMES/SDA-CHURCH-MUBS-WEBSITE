<?php
$f = __DIR__ . '/public/1.png';
$im = @imagecreatefrompng($f);
if (!$im) {
    fwrite(STDERR, "failed to open image\n");
    exit(1);
}
$w = imagesx($im);
$h = imagesy($im);
$hasAlpha = false;
for ($y = 0; $y < $h && !$hasAlpha; $y++) {
    for ($x = 0; $x < $w; $x++) {
        $c = imagecolorat($im, $x, $y);
        $a = ($c >> 24) & 0x7F;
        if ($a > 0) {
            $hasAlpha = true;
            break;
        }
    }
}
echo "w={$w} h={$h} alpha=" . ($hasAlpha ? 'yes' : 'no') . PHP_EOL;
imagedestroy($im);
