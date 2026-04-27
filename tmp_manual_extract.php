<?php
require 'vendor/autoload.php';
$parser = new Smalot\PdfParser\Parser();
$pdf = $parser->parseFile('public/Seventh-day-Adventist-Church-Manual-2025.pdf');
$text = preg_replace('/\s+/', ' ', $pdf->getText());
$terms = ['pastor','elder','head deacon','head deaconess','treasurer','clerk','personal ministries','women\'s ministries','children\'s ministries','family ministries','publishing ministries','sabbath school','adventist youth','pathfinder','adventurer','education','stewardship','religious liberty','communication','community services'];
foreach ($terms as $term) {
    echo "\n===== {$term} =====\n";
    $pos = stripos($text, $term);
    if ($pos === false) {
        echo "NOT FOUND\n";
        continue;
    }
    $start = max(0, $pos - 260);
    $snippet = substr($text, $start, 680);
    echo $snippet . "\n";
}
