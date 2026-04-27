<?php
require __DIR__ . '/vendor/autoload.php';

use Smalot\PdfParser\Parser;

$pdfPath = __DIR__ . '/public/SDA 28 Beliefs 2020.pdf';
$parser = new Parser();
$pdf = $parser->parseFile($pdfPath);
$text = $pdf->getText();

file_put_contents(__DIR__ . '/storage/app/beliefs_extract.txt', $text);

echo 'Saved to storage/app/beliefs_extract.txt' . PHP_EOL;
