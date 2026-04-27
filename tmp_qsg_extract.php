<?php
require 'vendor/autoload.php';
$parser = new Smalot\PdfParser\Parser();
$pdf = $parser->parseFile('Church.-Board-QSG.pdf');
$text = $pdf->getText();
echo 'LEN=' . strlen($text) . PHP_EOL;
echo substr($text, 0, 1200) . PHP_EOL;
