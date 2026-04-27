<?php
require 'vendor/autoload.php';
$parser = new Smalot\PdfParser\Parser();
$pdf = $parser->parseFile('Church.-Board-QSG.pdf');
$text = preg_replace('/\s+/', ' ', $pdf->getText());
$terms = ['Pastor','Elder','Head deacon','Head deaconess','Treasurer','Clerk','Personal Ministries','Women\'s Ministries','Children\'s Ministries','Family Ministries','Publishing Ministries','Sabbath School','Adventist Youth','Pathfinder','Adventurer','Education','Stewardship','Religious Liberty','Communication','Community Services','Duties of the Church Board'];
foreach ($terms as $term) {
    echo "\n===== {$term} =====\n";
    $pos = stripos($text, $term);
    if ($pos === false) { echo "NOT FOUND\n"; continue; }
    $start = max(0, $pos - 240);
    $snippet = substr($text, $start, 820);
    echo $snippet . "\n";
}
