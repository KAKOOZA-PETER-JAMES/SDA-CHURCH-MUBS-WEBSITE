<?php
$files = [
    __DIR__ . '/public/1.png',
    __DIR__ . '/public/tp.png',
    __DIR__ . '/public/tp-clean.png',
];

foreach ($files as $file) {
    $im = @imagecreatefrompng($file);
    if (!$im) {
        echo basename($file) . " fail" . PHP_EOL;
        continue;
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

    echo basename($file) . ' ' . $w . 'x' . $h . ' alpha=' . ($hasAlpha ? 'yes' : 'no') . PHP_EOL;
    imagedestroy($im);
}
