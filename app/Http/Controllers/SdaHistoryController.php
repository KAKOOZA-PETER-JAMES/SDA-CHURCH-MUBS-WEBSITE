<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Smalot\PdfParser\Parser;

class SdaHistoryController extends Controller
{
    public function index(Request $request): View
    {
        $documents = [
            ['title' => 'Article CESM', 'file' => 'article-CE5M.pdf'],
            ['title' => 'Church Heritage', 'file' => 'Church-Heritage.pdf'],
            ['title' => 'Discover', 'file' => 'DISCOVER.pdf'],
            ['title' => 'Uganda', 'file' => 'UGANDA.pdf'],
        ];

        $parser = new Parser();

        $enriched = array_map(function (array $document) use ($parser) {
            $publicPath = public_path($document['file']);
            $summary = 'Summary is currently unavailable for this document.';

            if (file_exists($publicPath)) {
                try {
                    $text = (string) $parser->parseFile($publicPath)->getText();
                    $cleaned = trim((string) preg_replace('/\s+/u', ' ', $text));

                    if ($cleaned !== '') {
                        $words = preg_split('/\s+/u', $cleaned) ?: [];
                        $snippet = implode(' ', array_slice($words, 0, 70));
                        $summary = rtrim($snippet, '.,;: ') . '...';
                    }
                } catch (\Throwable $exception) {
                    $summary = 'Unable to extract summary text right now. You can still open or download this PDF.';
                }
            }

            $filePath = str_replace(' ', '%20', $document['file']);

            return [
                'title' => $document['title'],
                'file' => $document['file'],
                'summary' => $summary,
                'viewUrl' => asset($filePath),
                'downloadUrl' => asset($filePath),
            ];
        }, $documents);

        $selectedFile = (string) $request->query('read', '');
        $activeDocument = null;

        if ($selectedFile !== '') {
            foreach ($enriched as $document) {
                if ($document['file'] === $selectedFile) {
                    $activeDocument = $document;
                    break;
                }
            }
        }

        return view('sda-history', [
            'documents' => $enriched,
            'activeDocument' => $activeDocument,
        ]);
    }
}
