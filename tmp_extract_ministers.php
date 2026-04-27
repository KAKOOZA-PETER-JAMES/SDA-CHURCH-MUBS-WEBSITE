<?php
require __DIR__ . '/vendor/autoload.php';

$parser = new Smalot\PdfParser\Parser();
$pdf = $parser->parseFile(__DIR__ . '/public/seventh-day-adventist-ministers-handbook.pdf');
$text = $pdf->getText();

$outDir = __DIR__ . '/storage/app/pdf_extract';
if (!is_dir($outDir)) {
    mkdir($outDir, 0777, true);
}

$outPath = $outDir . '/ministers_handbook_text.txt';
file_put_contents($outPath, $text);

echo 'chars=' . strlen($text) . PHP_EOL;
echo 'out=' . $outPath . PHP_EOL;
