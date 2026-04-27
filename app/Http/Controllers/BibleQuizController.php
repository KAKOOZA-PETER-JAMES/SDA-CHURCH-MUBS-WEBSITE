<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Smalot\PdfParser\Parser;

class BibleQuizController extends Controller
{
    private const QUESTIONS_PER_PAGE = 10;
    private const SESSION_ATTEMPTS_KEY = 'bible_quiz_attempts';
    private const DEFAULT_BOOK = 'Bible Quiz';
    private const BIBLE_BOOK_ORDER = [
        'Genesis', 'Exodus', 'Leviticus', 'Numbers', 'Deuteronomy',
        'Joshua', 'Judges', 'Ruth', '1 Samuel', '2 Samuel',
        '1 Kings', '2 Kings', '1 Chronicles', '2 Chronicles', 'Ezra',
        'Nehemiah', 'Esther', 'Job', 'Psalms', 'Proverbs',
        'Ecclesiastes', 'Song of Solomon', 'Isaiah', 'Jeremiah', 'Lamentations',
        'Ezekiel', 'Daniel', 'Hosea', 'Joel', 'Amos',
        'Obadiah', 'Jonah', 'Micah', 'Nahum', 'Habakkuk',
        'Zephaniah', 'Haggai', 'Zechariah', 'Malachi', 'Matthew',
        'Mark', 'Luke', 'John', 'Acts', 'Romans',
        '1 Corinthians', '2 Corinthians', 'Galatians', 'Ephesians', 'Philippians',
        'Colossians', '1 Thessalonians', '2 Thessalonians', '1 Timothy', '2 Timothy',
        'Titus', 'Philemon', 'Hebrews', 'James', '1 Peter',
        '2 Peter', '1 John', '2 John', '3 John', 'Jude',
        'Revelation',
    ];

    public function index(): View
    {
        $books = $this->availableBooks();

        return view('bible-quiz', [
            'questions' => $this->questionsForBook($books[0] ?? self::DEFAULT_BOOK),
            'books' => $books,
            'currentBook' => $books[0] ?? self::DEFAULT_BOOK,
        ]);
    }

    public function start(Request $request): View
    {
        $bookContext = $this->resolveBookContext($request, $this->availableBooks());
        $questions = $this->questionsForBook($bookContext['currentBook']);

        if ($request->boolean('reset')) {
            $this->clearBookState($request, $bookContext['currentBook']);
        }

        $bookState = $this->bookState($request, $bookContext['currentBook']);
        $pageData = $this->buildPagedQuizData($questions, $request, $bookState['answers'], $bookContext);

        return view('bible-quiz', array_merge($pageData, [
            'userAnswers' => $bookState['answers'],
            'showCover' => false,
        ]));
    }

    public function submit(Request $request): View
    {
        $bookContext = $this->resolveBookContext($request, $this->availableBooks());
        $questions = $this->questionsForBook($bookContext['currentBook']);
        $answerKeys = array_keys($questions);
        $bookState = $this->bookState($request, $bookContext['currentBook']);

        $questionPages = array_values(array_chunk($questions, self::QUESTIONS_PER_PAGE, true));
        $totalPages = max(1, count($questionPages));

        $currentPage = max(1, (int) $request->input('page', 1));
        $currentPage = min($currentPage, $totalPages);

        $currentPageQuestions = $questionPages[$currentPage - 1] ?? [];
        $currentPageKeys = array_keys($currentPageQuestions);
        $targetPage = (int) $request->input('target_page', 0);

        $submittedAnswers = $request->input('answers', []);
        $allAnswersPayload = $request->input('all_answers_json');
        $persistedAnswers = is_array($bookState['answers']) ? $bookState['answers'] : [];
        $persistedChangeCounts = is_array($bookState['change_counts']) ? $bookState['change_counts'] : [];

        if (is_string($allAnswersPayload) && trim($allAnswersPayload) !== '') {
            $decoded = json_decode($allAnswersPayload, true);

            if (is_array($decoded)) {
                $persistedAnswers = $decoded;
            }
        }

        $submittedAnswers = is_array($submittedAnswers) ? $submittedAnswers : [];
        $incomingAnswers = array_merge(
            is_array($persistedAnswers) ? $persistedAnswers : [],
            is_array($submittedAnswers) ? $submittedAnswers : []
        );

        foreach ($currentPageKeys as $questionKey) {
            $newValue = $this->normalizeAnswerValue($incomingAnswers[$questionKey] ?? null);

            if ($newValue === null) {
                continue;
            }

            $previousValue = $this->normalizeAnswerValue($persistedAnswers[$questionKey] ?? null);
            $currentChanges = (int) ($persistedChangeCounts[$questionKey] ?? 0);

            if ($previousValue !== null && $previousValue !== $newValue) {
                $currentChanges++;
            }

            if ($currentChanges > 2) {
                $pageData = $this->buildPagedQuizData($questions, $request, $persistedAnswers, $bookContext);

                return view('bible-quiz', array_merge($pageData, [
                    'userAnswers' => $persistedAnswers,
                    'quizError' => 'You changed an answer too many times. Maximum allowed is 2 changes per question.',
                    'showCover' => false,
                ]));
            }

            $persistedAnswers[$questionKey] = $newValue;
            $persistedChangeCounts[$questionKey] = $currentChanges;
        }

        $this->storeBookState($request, $bookContext['currentBook'], [
            'answers' => $persistedAnswers,
            'change_counts' => $persistedChangeCounts,
        ]);

        if (!$this->isPageFullyAnswered($currentPageKeys, $persistedAnswers)) {
            $pageData = $this->buildPagedQuizData($questions, $request, $persistedAnswers, $bookContext);

            return view('bible-quiz', array_merge($pageData, [
                'userAnswers' => $persistedAnswers,
                'quizError' => 'Please answer all questions on this page before proceeding.',
                'showCover' => false,
            ]));
        }

        if ($targetPage > 0 && $targetPage !== $currentPage) {
            $nextPageRequest = Request::create('/bible-quiz/start', 'GET', [
                'book' => $bookContext['currentBook'],
                'page' => min(max(1, $targetPage), $totalPages),
            ]);
            $nextPageRequest->setLaravelSession($request->session());

            return $this->start($nextPageRequest);
        }

        if ($currentPage < $totalPages) {
            $nextPageRequest = Request::create('/bible-quiz/start', 'GET', [
                'book' => $bookContext['currentBook'],
                'page' => $currentPage + 1,
            ]);
            $nextPageRequest->setLaravelSession($request->session());

            return $this->start($nextPageRequest);
        }

        $userAnswers = $persistedAnswers;

        if (!is_array($userAnswers) || empty($userAnswers)) {
            $pageData = $this->buildPagedQuizData($questions, $request, $persistedAnswers, $bookContext);

            return view('bible-quiz', array_merge($pageData, [
                'userAnswers' => [],
                'quizError' => 'Please answer all questions before submitting the quiz.',
                'showCover' => false,
            ]));
        }

        foreach ($answerKeys as $questionKey) {
            $value = $userAnswers[$questionKey] ?? null;

            if (!$this->hasAnswerValue($value)) {
                $pageData = $this->buildPagedQuizData($questions, $request, $persistedAnswers, $bookContext);

                return view('bible-quiz', array_merge($pageData, [
                    'userAnswers' => $userAnswers,
                    'quizError' => 'Please answer all questions before submitting the quiz.',
                    'showCover' => false,
                ]));
            }
        }

        $score = 0;
        $failedQuestions = [];

        foreach ($questions as $questionKey => $question) {
            $selectedAnswer = $userAnswers[$questionKey] ?? null;

            if ($selectedAnswer === $question['correct']) {
                $score++;
                continue;
            }

            $failedQuestions[] = [
                'prompt' => $question['prompt'],
                'selected' => $selectedAnswer,
                'correct' => $question['correct'],
            ];
        }

        $totalQuestions = count($questions);
        $failedCount = count($failedQuestions);
        $percentage = (int) round(($score / $totalQuestions) * 100);
        $rank = $this->rankLabel($percentage);
        $isPerfectScore = $failedCount === 0;
        $perfectScoreMessage = $isPerfectScore
            ? '🎉 Amazing! You answered every question correctly. Excellent Bible knowledge!'
            : null;

        $pageData = $this->buildPagedQuizData($questions, $request, $persistedAnswers, $bookContext);

        return view('bible-quiz', array_merge($pageData, [
            'userAnswers' => $userAnswers,
            'score' => $score,
            'total' => $totalQuestions,
            'failedCount' => $failedCount,
            'percentage' => $percentage,
            'rank' => $rank,
            'isPerfectScore' => $isPerfectScore,
            'perfectScoreMessage' => $perfectScoreMessage,
            'failedQuestions' => $failedQuestions,
            'submitted' => true,
            'showCover' => false,
        ]));
    }

    private function buildPagedQuizData(array $questions, Request $request, array $userAnswers = [], ?array $bookContext = null): array
    {
        $bookContext = $bookContext ?? $this->resolveBookContext($request, $this->availableBooks());
        $books = $bookContext['books'];
        $currentBook = $bookContext['currentBook'];

        $perPage = self::QUESTIONS_PER_PAGE;
        $totalBookQuestions = count($questions);
        $totalPages = max(1, (int) ceil($totalBookQuestions / $perPage));
        $requestedPage = max(1, (int) $request->input('page', 1));

        $questionPages = array_values(array_chunk($questions, $perPage, true));
        $maxUnlockedPage = $this->maxUnlockedPage($questionPages, $userAnswers);
        $currentPage = min($requestedPage, $maxUnlockedPage, $totalPages);
        $pageLockError = null;

        if ($requestedPage > $maxUnlockedPage) {
            $pageLockError = 'Please complete all questions on page ' . $maxUnlockedPage . ' before moving forward.';
        }

        $pageQuestions = $questionPages[$currentPage - 1] ?? [];

        return [
            'questions' => $pageQuestions,
            'books' => $books,
            'currentBook' => $currentBook,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalBookQuestions' => $totalBookQuestions,
            'bookQuestionKeys' => array_keys($questions),
            'pageLockError' => $pageLockError,
        ];
    }

    private function resolveBookContext(Request $request, array $books): array
    {
        if (empty($books)) {
            $books = [self::DEFAULT_BOOK];
        }

        $currentBook = (string) $request->input('book', $books[0]);

        if (!in_array($currentBook, $books, true)) {
            $currentBook = $books[0];
        }

        return [
            'books' => $books,
            'currentBook' => $currentBook,
        ];
    }

    private function availableBooks(): array
    {
        $grouped = $this->questionsByBook();
        $books = [];

        foreach ($grouped as $book => $questions) {
            if (is_array($questions) && !empty($questions)) {
                $books[] = $book;
            }
        }

        if (count($books) > 1) {
            $books = array_values(array_filter($books, fn ($book) => $book !== self::DEFAULT_BOOK));
        }

        $books = $this->orderBooks($books);

        return !empty($books) ? $books : [self::DEFAULT_BOOK];
    }

    private function orderBooks(array $books): array
    {
        $orderLookup = array_flip(self::BIBLE_BOOK_ORDER);

        usort($books, function (string $left, string $right) use ($orderLookup): int {
            $leftOrder = $orderLookup[$left] ?? 999;
            $rightOrder = $orderLookup[$right] ?? 999;

            if ($leftOrder === $rightOrder) {
                return strcmp($left, $right);
            }

            return $leftOrder <=> $rightOrder;
        });

        return array_values(array_unique($books));
    }

    private function questionsForBook(string $book): array
    {
        $grouped = $this->questionsByBook();

        if (isset($grouped[$book]) && !empty($grouped[$book])) {
            return $grouped[$book];
        }

        $firstBook = array_key_first($grouped);

        if ($firstBook !== null && isset($grouped[$firstBook])) {
            return $grouped[$firstBook];
        }

        return [];
    }

    private function bookState(Request $request, string $book): array
    {
        $allState = $request->session()->get(self::SESSION_ATTEMPTS_KEY, []);
        $bookKey = $this->bookStateKey($book);
        $bookState = $allState[$bookKey] ?? [];

        $answers = is_array($bookState['answers'] ?? null) ? $bookState['answers'] : [];
        $changeCounts = is_array($bookState['change_counts'] ?? null) ? $bookState['change_counts'] : [];

        return [
            'answers' => $answers,
            'change_counts' => $changeCounts,
        ];
    }

    private function storeBookState(Request $request, string $book, array $state): void
    {
        $allState = $request->session()->get(self::SESSION_ATTEMPTS_KEY, []);
        $bookKey = $this->bookStateKey($book);

        $allState[$bookKey] = [
            'answers' => is_array($state['answers'] ?? null) ? $state['answers'] : [],
            'change_counts' => is_array($state['change_counts'] ?? null) ? $state['change_counts'] : [],
        ];

        $request->session()->put(self::SESSION_ATTEMPTS_KEY, $allState);
    }

    private function clearBookState(Request $request, string $book): void
    {
        $allState = $request->session()->get(self::SESSION_ATTEMPTS_KEY, []);
        $bookKey = $this->bookStateKey($book);

        if (array_key_exists($bookKey, $allState)) {
            unset($allState[$bookKey]);
            $request->session()->put(self::SESSION_ATTEMPTS_KEY, $allState);
        }
    }

    private function bookStateKey(string $book): string
    {
        $slug = Str::slug($book);

        return $slug !== '' ? $slug : 'default';
    }

    private function maxUnlockedPage(array $questionPages, array $userAnswers): int
    {
        $maxUnlocked = 1;

        foreach ($questionPages as $index => $questionsForPage) {
            $pageNumber = $index + 1;
            $keys = array_keys($questionsForPage);

            if (!$this->isPageFullyAnswered($keys, $userAnswers)) {
                break;
            }

            $maxUnlocked = $pageNumber + 1;
        }

        return min(max(1, $maxUnlocked), max(1, count($questionPages)));
    }

    private function isPageFullyAnswered(array $questionKeys, array $answers): bool
    {
        foreach ($questionKeys as $key) {
            if (!$this->hasAnswerValue($answers[$key] ?? null)) {
                return false;
            }
        }

        return true;
    }

    private function hasAnswerValue($value): bool
    {
        return is_string($value) && trim($value) !== '';
    }

    private function normalizeAnswerValue($value): ?string
    {
        if (!is_string($value)) {
            return null;
        }

        $normalized = trim($value);

        return $normalized === '' ? null : $normalized;
    }

    private function rankLabel(int $percentage): string
    {
        if ($percentage >= 90) {
            return 'Excellent';
        }

        if ($percentage >= 75) {
            return 'Very Good';
        }

        if ($percentage >= 50) {
            return 'Good';
        }

        return 'Keep Learning';
    }

    private function questionsByBook(): array
    {
        $legacyGroupedQuestions = $this->loadLegacyGroupedQuestions();

        if (!empty($legacyGroupedQuestions)) {
            return $legacyGroupedQuestions;
        }

        $dynamicQuestions = $this->loadQuestionsFromPdf();

        if (!empty($dynamicQuestions)) {
            return $dynamicQuestions;
        }

        return [
            self::DEFAULT_BOOK => $this->fallbackQuestions(),
        ];
    }

    private function loadLegacyGroupedQuestions(): array
    {
        $legacyPath = storage_path('app/pdf_extract/quiz_grouped_questions.json');

        if (!file_exists($legacyPath)) {
            return [];
        }

        $decoded = json_decode((string) file_get_contents($legacyPath), true);

        if (!is_array($decoded) || empty($decoded)) {
            return [];
        }

        return $this->normalizeGroupedQuestions($decoded);
    }

    private function normalizeGroupedQuestions(array $grouped): array
    {
        $normalized = [];

        foreach ($grouped as $book => $questions) {
            if (!is_string($book) || !is_array($questions) || empty($questions)) {
                continue;
            }

            $bookName = trim($book);

            if ($bookName === '') {
                continue;
            }

            $bookQuestions = [];
            $index = 0;

            foreach ($questions as $key => $question) {
                if (!is_array($question)) {
                    continue;
                }

                $prompt = trim((string) ($question['prompt'] ?? ''));
                $correct = trim((string) ($question['correct'] ?? ''));
                $options = is_array($question['options'] ?? null) ? $question['options'] : [];
                $options = array_values(array_filter(array_map(fn ($item) => trim((string) $item), $options), fn ($item) => $item !== ''));

                if ($prompt === '' || $correct === '') {
                    continue;
                }

                if (!in_array($correct, $options, true)) {
                    $options[] = $correct;
                }

                $options = array_values(array_unique($options));

                if (count($options) < 4) {
                    $allBookAnswers = array_values(array_unique(array_filter(array_map(
                        fn ($item) => trim((string) ($item['correct'] ?? '')),
                        array_filter($questions, 'is_array')
                    ), fn ($item) => $item !== '' && $item !== $correct)));

                    foreach ($allBookAnswers as $candidate) {
                        if (!in_array($candidate, $options, true)) {
                            $options[] = $candidate;
                        }

                        if (count($options) >= 4) {
                            break;
                        }
                    }
                }

                $options = array_values(array_slice($options, 0, 4));

                if (count($options) < 2) {
                    continue;
                }

                $index++;
                $questionKey = is_string($key) && trim($key) !== ''
                    ? trim($key)
                    : Str::slug($bookName) . '_' . $index;

                $bookQuestions[$questionKey] = [
                    'prompt' => $this->squishAndLimit($prompt, 170),
                    'options' => $options,
                    'correct' => $correct,
                ];
            }

            if (!empty($bookQuestions)) {
                $normalized[$bookName] = $bookQuestions;
            }
        }

        return $normalized;
    }

    private function loadQuestionsFromPdf(): array
    {
        $pdfPath = public_path('questions and answers.pdf');
        // Versioned cache file ensures older truncated question caches are ignored.
        $cachePath = storage_path('app/pdf_extract/quiz_questions_by_book_v2.json');

        if (!file_exists($pdfPath)) {
            return [];
        }

        if (file_exists($cachePath) && filemtime($cachePath) >= filemtime($pdfPath)) {
            $cached = json_decode((string) file_get_contents($cachePath), true);

            if (is_array($cached) && !empty($cached)) {
                return $cached;
            }
        }

        try {
            $parser = new Parser();
            $pdf = $parser->parseFile($pdfPath);
            $text = $pdf->getText();
            $parsed = $this->parseQuestionsFromText($text);

            if (!empty($parsed)) {
                $cacheDir = dirname($cachePath);

                if (!is_dir($cacheDir)) {
                    mkdir($cacheDir, 0777, true);
                }

                file_put_contents($cachePath, json_encode($parsed, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            }

            return $parsed;
        } catch (\Throwable $exception) {
            return [];
        }
    }

    private function parseQuestionsFromText(string $text): array
    {
        $normalized = preg_replace('/\s+/u', ' ', $text) ?? '';

        if ($normalized === '') {
            return [];
        }

        preg_match_all(
            '/\b\d+\.\s+([^?]{8,220}\?)\s*(.{2,260}?)(?=\s+\d+\.\s+[^?]{8,220}\?\s*|$)/u',
            $normalized,
            $matches,
            PREG_SET_ORDER
        );

        if (empty($matches)) {
            return [];
        }

        $pairs = [];

        foreach ($matches as $match) {
            $question = trim($match[1] ?? '');
            $rawAnswer = trim($match[2] ?? '');

            if ($question === '' || $rawAnswer === '') {
                continue;
            }

            $answer = $this->extractAnswerText($rawAnswer);

            if ($answer === '' || mb_strlen($answer) < 2 || mb_strlen($answer) > 90) {
                continue;
            }

            $pairs[] = [
                'prompt' => $this->squishAndLimit($question, 170),
                'correct' => $this->squishAndLimit($answer, 85),
                'book' => $this->detectBookFromQuestionText($question . ' ' . $rawAnswer),
            ];

        }

        if (count($pairs) < 4) {
            return [];
        }

        $pairsByBook = [];

        foreach ($pairs as $pair) {
            $book = $pair['book'] ?? self::DEFAULT_BOOK;
            $pairsByBook[$book][] = $pair;
        }

        $questionsByBook = [];

        foreach ($pairsByBook as $book => $bookPairs) {
            if (count($bookPairs) < 1) {
                continue;
            }

            $allAnswers = array_values(array_unique(array_map(fn ($item) => $item['correct'], $bookPairs)));
            $questions = [];

            foreach ($bookPairs as $index => $pair) {
                $key = 'q' . ($index + 1);
                $options = $this->buildOptions($pair['correct'], $allAnswers, $pair['prompt']);

                $questions[$key] = [
                    'prompt' => $pair['prompt'],
                    'options' => $options,
                    'correct' => $pair['correct'],
                ];
            }

            $questionsByBook[$book] = $questions;
        }

        if (!empty($questionsByBook)) {
            return $questionsByBook;
        }

        return [];
    }

    private function detectBookFromQuestionText(string $text): string
    {
        $normalized = strtolower((string) preg_replace('/\s+/u', ' ', $text));

        if ($normalized === '') {
            return self::DEFAULT_BOOK;
        }

        $bookPatterns = [
            'Genesis' => '/\b(?:genesis|gen)\b/u',
            'Exodus' => '/\b(?:exodus|exod|exo)\b/u',
            'Leviticus' => '/\b(?:leviticus|lev)\b/u',
            'Numbers' => '/\b(?:numbers|num)\b/u',
            'Deuteronomy' => '/\b(?:deuteronomy|deut)\b/u',
            'Joshua' => '/\b(?:joshua|josh)\b/u',
            'Judges' => '/\b(?:judges|judg)\b/u',
            'Ruth' => '/\bruth\b/u',
            '1 Samuel' => '/\b1\s*samuel\b|\b1\s*sam\b/u',
            '2 Samuel' => '/\b2\s*samuel\b|\b2\s*sam\b/u',
            '1 Kings' => '/\b1\s*kings\b|\b1\s*kgs\b/u',
            '2 Kings' => '/\b2\s*kings\b|\b2\s*kgs\b/u',
            '1 Chronicles' => '/\b1\s*chronicles\b|\b1\s*chron\b|\b1\s*chr\b/u',
            '2 Chronicles' => '/\b2\s*chronicles\b|\b2\s*chron\b|\b2\s*chr\b/u',
            'Ezra' => '/\bezra\b/u',
            'Nehemiah' => '/\bnehemiah\b|\bneh\b/u',
            'Esther' => '/\besther\b|\best\b/u',
            'Job' => '/\bjob\b/u',
            'Psalms' => '/\b(?:psalms|psalm|ps)\b/u',
            'Proverbs' => '/\b(?:proverbs|prov|prv)\b/u',
            'Ecclesiastes' => '/\b(?:ecclesiastes|ecc)\b/u',
            'Song of Solomon' => '/\b(?:song\s+of\s+solomon|song\s+of\s+songs|song)\b/u',
            'Isaiah' => '/\b(?:isaiah|isa)\b/u',
            'Jeremiah' => '/\b(?:jeremiah|jer)\b/u',
            'Lamentations' => '/\b(?:lamentations|lam)\b/u',
            'Ezekiel' => '/\b(?:ezekiel|ezek|ezk)\b/u',
            'Daniel' => '/\b(?:daniel|dan)\b/u',
            'Hosea' => '/\b(?:hosea|hos)\b/u',
            'Joel' => '/\bjoel\b/u',
            'Amos' => '/\bamos\b/u',
            'Obadiah' => '/\bobadiah\b|\bobad\b/u',
            'Jonah' => '/\bjonah\b|\bjon\b/u',
            'Micah' => '/\bmicah\b|\bmic\b/u',
            'Nahum' => '/\bnahum\b|\bnah\b/u',
            'Habakkuk' => '/\bhabakkuk\b|\bhab\b/u',
            'Zephaniah' => '/\bzephaniah\b|\bzeph\b/u',
            'Haggai' => '/\bhaggai\b|\bhag\b/u',
            'Zechariah' => '/\bzechariah\b|\bzech\b/u',
            'Malachi' => '/\bmalachi\b|\bmal\b/u',
            'Matthew' => '/\b(?:matthew|matt|mat)\b/u',
            'Mark' => '/\b(?:mark|mrk|mk)\b/u',
            'Luke' => '/\b(?:luke|luk|lk)\b/u',
            'John' => '/\b(?:john|jhn|jn)\b/u',
            'Acts' => '/\bacts\b/u',
            'Romans' => '/\b(?:romans|rom)\b/u',
            '1 Corinthians' => '/\b1\s*corinthians\b|\b1\s*cor\b/u',
            '2 Corinthians' => '/\b2\s*corinthians\b|\b2\s*cor\b/u',
            'Galatians' => '/\bgalatians\b|\bgal\b/u',
            'Ephesians' => '/\bephesians\b|\beph\b/u',
            'Philippians' => '/\bphilippians\b|\bphil\b/u',
            'Colossians' => '/\bcolossians\b|\bcol\b/u',
            '1 Thessalonians' => '/\b1\s*thessalonians\b|\b1\s*thess\b/u',
            '2 Thessalonians' => '/\b2\s*thessalonians\b|\b2\s*thess\b/u',
            '1 Timothy' => '/\b1\s*timothy\b|\b1\s*tim\b/u',
            '2 Timothy' => '/\b2\s*timothy\b|\b2\s*tim\b/u',
            'Titus' => '/\btitus\b|\btit\b/u',
            'Philemon' => '/\bphilemon\b|\bphilem\b|\bphm\b/u',
            'Hebrews' => '/\bhebrews\b|\bheb\b/u',
            'James' => '/\bjames\b|\bjas\b/u',
            '1 Peter' => '/\b1\s*peter\b|\b1\s*pet\b/u',
            '2 Peter' => '/\b2\s*peter\b|\b2\s*pet\b/u',
            '1 John' => '/\b1\s*john\b|\b1\s*jhn\b|\b1\s*jn\b/u',
            '2 John' => '/\b2\s*john\b|\b2\s*jhn\b|\b2\s*jn\b/u',
            '3 John' => '/\b3\s*john\b|\b3\s*jhn\b|\b3\s*jn\b/u',
            'Jude' => '/\bjude\b|\bjud\b/u',
            'Revelation' => '/\b(?:revelation|rev)\b/u',
        ];

        foreach ($bookPatterns as $book => $pattern) {
            if (preg_match($pattern, $normalized) === 1) {
                return $book;
            }
        }

        return self::DEFAULT_BOOK;
    }

    private function extractAnswerText(string $rawAnswer): string
    {
        $cleaned = preg_replace('/\s+/u', ' ', $rawAnswer) ?? '';
        $cleaned = trim($cleaned, " \t\n\r\0\x0B\"'");

        if ($cleaned === '') {
            return '';
        }

        $sentence = preg_split('/(?<=[\.!;])\s+/u', $cleaned, 2)[0] ?? $cleaned;
        $sentence = preg_replace('/\b(Gen|Exod|Lev|Num|Deut|Josh|Judg|Ruth|Ps|Prov|Isa|Jer|Ezek|Dan|Matt|Mark|Luke|John|Acts|Rom|Heb|Rev)\.?\s*\d+[^ ]*/iu', '', $sentence) ?? $sentence;

        return trim($sentence, " \t\n\r\0\x0B\"'");
    }

    private function buildOptions(string $correct, array $allAnswers, string $seed): array
    {
        $distractors = array_values(array_filter($allAnswers, fn ($item) => $item !== $correct));

        if (count($distractors) < 3) {
            $distractors = array_values(array_unique(array_merge($distractors, [
                'Not sure',
                'None of these',
                'Unknown',
            ])));
        }

        usort($distractors, fn ($left, $right) => strcmp(md5($seed . $left), md5($seed . $right)));
        $picked = array_slice($distractors, 0, 3);
        $options = $picked;

        $slot = crc32($seed) % 4;
        array_splice($options, $slot, 0, [$correct]);

        return array_values(array_slice($options, 0, 4));
    }

    private function squishAndLimit(string $text, int $limit): string
    {
        $squished = preg_replace('/\s+/u', ' ', trim($text)) ?? trim($text);

        return Str::limit($squished, $limit, '...');
    }

    private function fallbackQuestions(): array
    {
        return [
            'q1' => [
                'prompt' => 'What is the name of the first book of the Bible?',
                'options' => ['Genesis', 'Exodus', 'Matthew', 'Psalms'],
                'correct' => 'Genesis',
            ],
            'q2' => [
                'prompt' => 'By what power did God create the world?',
                'options' => ['By His Word', 'By angels', 'By nature', 'By fire'],
                'correct' => 'By His Word',
            ],
            'q3' => [
                'prompt' => 'Who is called the Word of God?',
                'options' => ['Jesus Christ', 'Moses', 'John the Baptist', 'Elijah'],
                'correct' => 'Jesus Christ',
            ],
            'q4' => [
                'prompt' => 'Where did God place man after creation?',
                'options' => ['Garden of Eden', 'Mount Sinai', 'Jerusalem', 'Babylon'],
                'correct' => 'Garden of Eden',
            ],
        ];
    }
}
