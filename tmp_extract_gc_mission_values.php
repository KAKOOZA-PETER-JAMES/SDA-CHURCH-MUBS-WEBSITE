<?php
require __DIR__ . '/vendor/autoload.php';

use Smalot\PdfParser\Parser;

$parser = new Parser();
$pdf = $parser->parseFile(__DIR__ . '/public/(08)GC Mission and Values Statements.pdf');
$text = $pdf->getText();

$outDir = __DIR__ . '/storage/app/pdf_extract';
if (!is_dir($outDir)) {
    mkdir($outDir, 0777, true);
}

$outPath = $outDir . '/gc_mission_values.txt';
file_put_contents($outPath, $text);

echo 'chars=' . strlen($text) . PHP_EOL;
echo 'out=' . $outPath . PHP_EOL;
