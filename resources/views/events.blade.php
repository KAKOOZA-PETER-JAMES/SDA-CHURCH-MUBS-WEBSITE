@extends('layouts.site')

@section('title', 'SDA CHURCH MUBS | Events')

@section('content')
    @php
        $adminContent = \App\Support\AdminContentStore::get();
        $events = collect($adminContent['events'] ?? []);
        $eventMedia = collect($adminContent['event_media'] ?? []);
        $selectedSection = strtolower((string) request('section', ''));
        $selectedCategory = strtolower((string) request('category', ''));
        $selectedCategory = trim(str_replace(['_', ' '], '-', $selectedCategory));
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December',
        ];

        $groupedEvents = collect($months)->mapWithKeys(function ($month) use ($events) {
            return [
                $month => $events->filter(function ($event) use ($month) {
                    return strcasecmp((string) ($event['month'] ?? ''), $month) === 0;
                })->values(),
            ];
        });

        $resolvedSection = in_array($selectedSection, ['story', 'gallery', 'videos'], true)
            ? $selectedSection
            : '';

        $resolveMediaUrl = function (?string $path): string {
            $value = trim((string) $path);
            if ($value === '') {
                return '';
            }

            if (preg_match('/^https?:\/\//i', $value)) {
                return $value;
            }

            return file_exists(public_path($value)) ? asset($value) : '';
        };

        $mediaTypeFromUrl = function (string $url): string {
            $value = strtolower(trim($url));
            if ($value === '') {
                return '';
            }

            if (preg_match('/\.(mp4|webm|mov|m4v)(\?.*)?$/', $value)) {
                return 'video';
            }

            if (preg_match('/\.(jpg|jpeg|png|webp|gif)(\?.*)?$/', $value)) {
                return 'image';
            }

            return '';
        };

        $normalizedEventMedia = $eventMedia
            ->map(function ($item) use ($resolveMediaUrl) {
                $category = trim(str_replace(['_', ' '], '-', strtolower((string) ($item['category'] ?? ''))));
                $section = strtolower(trim((string) ($item['section'] ?? '')));
                $title = trim((string) ($item['title'] ?? ''));
                $description = trim((string) ($item['description'] ?? ''));
                $mediaUrl = $resolveMediaUrl((string) ($item['media_url'] ?? ''));
                $thumbnailUrl = $resolveMediaUrl((string) ($item['thumbnail'] ?? ''));

                return [
                    'category' => $category,
                    'section' => $section,
                    'title' => $title,
                    'description' => $description,
                    'media_url' => $mediaUrl,
                    'thumbnail_url' => $thumbnailUrl,
                ];
            })
            ->filter(function ($item) {
                $hasContent = $item['title'] !== ''
                    || $item['description'] !== ''
                    || $item['media_url'] !== ''
                    || $item['thumbnail_url'] !== '';

                return $item['category'] !== ''
                    && in_array($item['section'], ['story', 'gallery', 'videos'], true)
                    && $hasContent;
            })
            ->values();

        $sectionItems = $normalizedEventMedia
            ->filter(function ($item) use ($selectedCategory, $resolvedSection) {
                $categoryMatches = $selectedCategory === '' || $item['category'] === $selectedCategory;
                $sectionMatches = $resolvedSection === '' || $item['section'] === $resolvedSection;

                return $categoryMatches && $sectionMatches;
            })
            ->values();

        $storyItems = $sectionItems
            ->filter(function ($item) {
                return $item['section'] === 'story';
            })
            ->map(function ($item) use ($mediaTypeFromUrl) {
                return [
                    'title' => $item['title'] !== '' ? $item['title'] : 'Story',
                    'department' => ucwords(str_replace('-', ' ', $item['category'])),
                    'date_range' => 'Recent Event',
                    'details' => $item['description'] !== '' ? $item['description'] : 'Story details will be shared soon.',
                    'media_url' => $item['media_url'],
                    'thumbnail_url' => $item['thumbnail_url'],
                    'media_type' => $mediaTypeFromUrl($item['media_url']),
                ];
            })
            ->values();

        $galleryItems = $sectionItems
            ->filter(function ($item) {
                return $item['section'] === 'gallery';
            })
            ->map(function ($item) use ($mediaTypeFromUrl) {
                $image = $item['thumbnail_url'];
                $mediaType = $mediaTypeFromUrl($item['media_url']);

                if ($image === '' && $mediaType === 'image') {
                    $image = $item['media_url'];
                }

                return [
                    'image' => $image,
                    'title' => $item['title'] !== '' ? $item['title'] : 'Gallery Item',
                    'meta' => $item['description'] !== '' ? $item['description'] : 'Event gallery item',
                ];
            })
            ->filter(function ($item) {
                return trim((string) ($item['image'] ?? '')) !== '';
            })
            ->values()
            ->all();

        $videoSourceItems = $sectionItems
            ->filter(function ($item) {
                return $item['section'] === 'videos';
            })
            ->values();

        $videoItems = $videoSourceItems->map(function ($item) use ($mediaTypeFromUrl) {
            $url = trim((string) ($item['media_url'] ?? ''));
            $embedUrl = '';
            $localVideoUrl = '';

            if ($url !== '') {
                if (preg_match('~(?:youtube\.com/watch\?v=|youtu\.be/)([A-Za-z0-9_-]{6,})~', $url, $matches)) {
                    $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
                } elseif (str_contains($url, 'youtube.com/embed/')) {
                    $embedUrl = $url;
                } elseif ($mediaTypeFromUrl($url) === 'video') {
                    $localVideoUrl = $url;
                }
            }

            return [
                'title' => $item['title'] !== '' ? $item['title'] : 'Video',
                'description' => $item['description'] !== '' ? $item['description'] : 'Video details will be shared soon.',
                'url' => $url,
                'embed_url' => $embedUrl,
                'local_video_url' => $localVideoUrl,
                'thumbnail_url' => $item['thumbnail_url'],
                'category' => $item['category'],
                'category_label' => ucwords(str_replace('-', ' ', $item['category'])),
            ];
        })->values();

        $videoCategoryList = $normalizedEventMedia
            ->filter(function ($item) {
                return $item['section'] === 'videos';
            })
            ->map(function ($item) {
                return [
                    'slug' => $item['category'],
                    'label' => ucwords(str_replace('-', ' ', $item['category'])),
                ];
            })
            ->unique('slug')
            ->values();

        $selectedCategoryLabel = $selectedCategory === ''
            ? 'All Categories'
            : (optional($videoCategoryList->firstWhere('slug', $selectedCategory))['label'] ?? ucwords(str_replace('-', ' ', $selectedCategory)));

        $selectedVideoCategoryLabel = $selectedCategoryLabel;
    @endphp

    <style>
        .events-breadcrumb {
            margin: 0 0 1rem;
            color: #5c6b83;
            font-size: 0.95rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.35rem;
            align-items: center;
        }

        .events-breadcrumb a {
            color: #2f4f82;
            text-decoration: none;
            font-weight: 700;
        }

        .events-breadcrumb a:hover {
            text-decoration: underline;
        }

        .events-page h1 {
            margin: 0 0 0.8rem;
            font-size: clamp(2rem, 4.8vw, 3rem);
            line-height: 1.08;
            color: #4f5360;
        }

        .events-subtitle {
            margin: 0 0 1.5rem;
            font-size: clamp(1rem, 2vw, 1.5rem);
            color: var(--text-muted);
            font-weight: 700;
        }

        .events-month-title {
            margin: 0.85rem 0 0.75rem;
            color: #3e4d63;
            font-size: clamp(1.8rem, 4.4vw, 2.6rem);
            line-height: 1.1;
        }

        .events-list {
            background: #ffffff;
            border: 1px solid #dce4ef;
            border-radius: 10px;
            padding: 0.45rem 0;
            margin-bottom: 0.95rem;
        }

        .event-row {
            padding: 0.8rem 1rem;
            border-top: 1px solid #e9eef6;
        }

        .event-row:first-child {
            border-top: 0;
        }

        .event-meta {
            color: #56c9d0;
            font-size: 0.95rem;
            font-weight: 700;
            margin-bottom: 0.1rem;
        }

        .event-title {
            margin: 0;
            color: var(--text-muted);
            font-size: 1.24rem;
            line-height: 1.3;
        }

        .event-details {
            margin: 0.5rem 0 0;
            color: var(--text-muted);
            font-size: 0.98rem;
            line-height: 1.55;
        }

        .events-empty {
            margin: 0;
            padding: 0.8rem 1rem;
            color: var(--text-soft);
            font-size: 0.98rem;
        }

        .events-story-kicker {
            margin: 0 0 0.5rem;
            color: #5777a8;
            font-weight: 700;
            font-size: 0.88rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .events-story-hero-title {
            margin: 0;
            color: #243855;
            font-size: clamp(1.45rem, 3vw, 2rem);
            line-height: 1.2;
        }

        .events-story-hero-text {
            margin: 0.8rem 0 0;
            color: #3b4e6d;
            line-height: 1.65;
            font-size: 1rem;
        }

        .events-story-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
        }

        .events-story-card {
            border: 1px solid #dce4ef;
            border-radius: 10px;
            background: #fff;
            overflow: hidden;
            display: grid;
            grid-template-rows: auto 1fr;
        }

        .events-story-card-media {
            background: linear-gradient(135deg, #1a3f71, #2f6eb0);
            min-height: 190px;
            border-bottom: 1px solid #dce4ef;
        }

        .events-story-card-media img,
        .events-story-card-media video {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
            background: #102a52;
        }

        .events-story-card-body {
            padding: 0.9rem 1rem;
        }

        .events-story-card h3 {
            margin: 0.2rem 0 0;
            font-size: 1.1rem;
            color: #2c3f5b;
            line-height: 1.3;
        }

        .events-story-card p {
            margin: 0.6rem 0 0;
            color: var(--text-muted);
            font-size: 0.96rem;
            line-height: 1.55;
        }

        .events-section-empty {
            border: 1px dashed #c9d8eb;
            border-radius: 12px;
            background: linear-gradient(135deg, #f8fbff 0%, #ffffff 100%);
            padding: 1.2rem 1rem;
            text-align: center;
            color: #4a6285;
        }

        .events-category-section {
            margin-top: 1.15rem;
        }

        .events-category-section h2 {
            margin: 0 0 0.7rem;
            color: #2f4565;
            font-size: clamp(1.3rem, 2.8vw, 1.8rem);
        }

        .events-gallery-head {
            margin-bottom: 1rem;
        }

        .events-gallery-title {
            margin: 0;
            font-size: clamp(2rem, 4.6vw, 3rem);
            color: #314766;
            line-height: 1.08;
        }

        .events-gallery-subtitle {
            margin: 0.6rem 0 0;
            color: var(--text-soft);
            max-width: 70ch;
            line-height: 1.65;
        }

        .events-gallery-grid {
            columns: 3 220px;
            column-gap: 1rem;
        }

        .events-gallery-slider-wrap {
            border: 1px solid #dce4ef;
            border-radius: 10px;
            background: #ffffff;
            padding: 0.6rem;
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .events-gallery-track {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            width: max-content;
            animation: eventsGallerySlide 36s linear infinite;
        }

        .events-gallery-track:hover {
            animation-play-state: paused;
        }

        .events-gallery-slide {
            flex: 0 0 230px;
            width: 230px;
            height: 150px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #dce4ef;
            background: #f6f9ff;
        }

        .events-gallery-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        @keyframes eventsGallerySlide {
            from {
                transform: translateX(0);
            }
            to {
                transform: translateX(calc(-50% - 0.38rem));
            }
        }

        .events-gallery-card {
            break-inside: avoid;
            margin-bottom: 1rem;
            background: #fff;
            border: 1px solid #dce4ef;
            border-radius: 10px;
            overflow: hidden;
            cursor: zoom-in;
        }

        .events-gallery-card img {
            width: 100%;
            display: block;
            object-fit: cover;
            transition: transform 0.45s ease;
            transform-origin: center;
        }

        .events-gallery-card:hover img {
            transform: scale(1.09);
        }

        .events-gallery-caption {
            padding: 0.72rem 0.9rem 0.85rem;
        }

        .events-gallery-caption h3 {
            margin: 0;
            font-size: 1.02rem;
            color: #2f4565;
        }

        .events-gallery-caption p {
            margin: 0.38rem 0 0;
            color: var(--text-soft);
            font-size: 0.92rem;
        }

        .events-gallery-modal {
            position: fixed;
            inset: 0;
            z-index: 260;
            background: rgba(7, 20, 40, 0.84);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .events-gallery-modal.show {
            display: flex;
        }

        .events-gallery-dialog {
            width: min(1040px, 98vw);
            height: min(88vh, 860px);
            background: #ffffff;
            border-radius: 10px;
            border: 1px solid #d6e0ef;
            display: grid;
            grid-template-rows: auto 1fr auto;
            overflow: hidden;
        }

        .events-gallery-dialog-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.6rem;
            padding: 0.55rem 0.65rem;
            border-bottom: 1px solid #dde5f2;
            background: #f5f8fe;
        }

        .events-gallery-dialog-title {
            margin: 0;
            color: #1f4a8a;
            font-size: 0.95rem;
            font-weight: 700;
        }

        .events-gallery-controls {
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        .events-gallery-btn {
            border: 1px solid #cddaf0;
            background: #ffffff;
            color: #1f4a8a;
            border-radius: 7px;
            padding: 0.33rem 0.52rem;
            font-weight: 700;
            cursor: pointer;
            font-size: 0.8rem;
        }

        .events-gallery-body {
            overflow: auto;
            background: #edf3fb;
            display: grid;
            place-items: center;
            padding: 0.8rem;
        }

        .events-gallery-stage {
            width: 100%;
            text-align: center;
        }

        .events-gallery-zoom-image {
            max-width: 100%;
            max-height: calc(88vh - 160px);
            transform-origin: center;
            transition: transform 0.18s ease;
        }

        .events-gallery-meta {
            border-top: 1px solid #dde5f2;
            padding: 0.58rem 0.7rem 0.7rem;
            background: #ffffff;
        }

        .events-gallery-meta h3 {
            margin: 0;
            color: #234265;
            font-size: 1rem;
        }

        .events-gallery-meta p {
            margin: 0.35rem 0 0;
            color: #4f6484;
            font-size: 0.9rem;
        }

        .events-videos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1rem;
        }

        .events-video-shell {
            display: grid;
            gap: 1rem;
        }

        .events-video-hero {
            border: 1px solid #dce4ef;
            border-radius: 14px;
            background: linear-gradient(135deg, #f6f9ff 0%, #ffffff 48%, #f8fbff 100%);
            padding: 1rem 1.1rem;
            display: grid;
            gap: 0.55rem;
        }

        .events-video-meta-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .events-video-active-category {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            border: 1px solid #bcd2f2;
            background: #ffffff;
            color: #1f4a8a;
            padding: 0.3rem 0.62rem;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .events-video-count {
            color: #5b6f8e;
            font-size: 0.9rem;
            font-weight: 700;
        }

        .events-video-head {
            display: grid;
            gap: 0.65rem;
        }

        .events-video-filter-bar {
            border: 1px solid #dce4ef;
            border-radius: 12px;
            background: #ffffff;
            padding: 0.72rem 0.75rem;
        }

        .events-video-categories {
            display: flex;
            flex-wrap: wrap;
            gap: 0.45rem;
        }

        .events-video-chip {
            display: inline-flex;
            align-items: center;
            padding: 0.42rem 0.75rem;
            border-radius: 999px;
            border: 1px solid #cddaf0;
            background: #f2f7ff;
            color: #1f4a8a;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.85rem;
        }

        .events-video-chip.active,
        .events-video-chip:hover {
            background: #1f4a8a;
            border-color: #1f4a8a;
            color: #ffffff;
        }

        .events-video-card {
            background: #fff;
            border: 1px solid #dce4ef;
            border-radius: 12px;
            overflow: hidden;
            display: grid;
            grid-template-rows: auto 1fr;
        }

        .events-video-media {
            background: linear-gradient(135deg, #193a68, #2f6eb0);
            aspect-ratio: 16/9;
            border-bottom: 1px solid #dce4ef;
        }

        .events-video-media iframe,
        .events-video-media video {
            width: 100%;
            height: 100%;
            border: 0;
            display: block;
            object-fit: cover;
            background: #102a52;
        }

        .events-video-placeholder {
            height: 100%;
            display: grid;
            place-items: center;
            color: #ffffff;
            font-weight: 700;
            letter-spacing: 0.03em;
        }

        .events-video-empty {
            grid-column: 1 / -1;
            border: 1px dashed #c9d8eb;
            border-radius: 14px;
            background: linear-gradient(135deg, #f8fbff 0%, #ffffff 100%);
            padding: 1.35rem 1.1rem;
            text-align: center;
            color: #385176;
        }

        .events-video-empty h3 {
            margin: 0 0 0.45rem;
            color: #153761;
            font-size: 1.08rem;
        }

        .events-video-empty p {
            margin: 0;
            color: #556b8d;
            line-height: 1.55;
        }

        .events-video-body {
            padding: 0.9rem 0.95rem 1rem;
            display: grid;
            grid-template-rows: auto auto 1fr auto;
            gap: 0.25rem;
        }

        .events-video-category {
            margin: 0 0 0.4rem;
            color: #5878a8;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .events-video-card h3 {
            margin: 0 0 0.45rem;
            color: #2f4565;
            font-size: 1.08rem;
        }

        .events-video-card p {
            margin: 0 0 0.8rem;
            color: #4c5f7c;
            line-height: 1.5;
        }

        .events-video-link {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            color: #1f4a8a;
            text-decoration: none;
            font-weight: 700;
        }

        @media (max-width: 820px) {
            .events-gallery-grid {
                columns: 2 190px;
            }

            .events-gallery-slide {
                flex-basis: 190px;
                width: 190px;
                height: 128px;
            }

            .events-videos-grid {
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            }
        }

        @media (max-width: 640px) {
            .events-breadcrumb {
                margin-bottom: 0.8rem;
                font-size: 0.87rem;
                gap: 0.28rem;
            }

            .events-page h1,
            .events-gallery-title {
                font-size: clamp(1.55rem, 7vw, 2.1rem);
            }

            .events-subtitle,
            .events-gallery-subtitle {
                margin-bottom: 1rem;
                font-size: 0.95rem;
                line-height: 1.5;
            }

            .events-gallery-grid {
                columns: 1 100%;
            }

            .events-gallery-track {
                animation-duration: 24s;
            }

            .events-gallery-slide {
                flex-basis: 150px;
                width: 150px;
                height: 106px;
            }

            .events-video-head {
                gap: 0.5rem;
            }

            .events-video-hero {
                padding: 0.85rem 0.85rem;
            }

            .events-video-categories {
                flex-wrap: nowrap;
                overflow-x: auto;
                padding-bottom: 0.35rem;
                -webkit-overflow-scrolling: touch;
            }

            .events-video-chip {
                white-space: nowrap;
                font-size: 0.8rem;
                padding: 0.42rem 0.68rem;
            }

            .events-videos-grid {
                grid-template-columns: 1fr;
                gap: 0.8rem;
            }

            .events-video-media {
                aspect-ratio: 16/10;
            }

            .events-video-body {
                padding: 0.78rem 0.8rem 0.88rem;
            }

            .events-video-card h3 {
                font-size: 1rem;
            }

            .events-video-card p {
                font-size: 0.92rem;
                margin-bottom: 0.65rem;
            }
        }
    </style>

    @if($selectedSection === 'story')
        <section class="events-page" aria-label="Event stories">
            <nav class="events-breadcrumb" aria-label="Story breadcrumb">
                <a href="{{ route('events') }}">Events</a>
                <span aria-hidden="true">›</span>
                <span>Story</span>
            </nav>

            <h1>Mission Stories &amp; News</h1>
            <p class="events-subtitle">Stories of faith, mission, and service from our church events.</p>

            @if($storyItems->isEmpty())
                <article class="events-section-empty">
                    <h3>No stories found</h3>
                    <p>No story content is available for {{ $selectedCategoryLabel }} yet.</p>
                </article>
            @else
                <section class="events-story-grid" aria-label="Story list">
                    @foreach($storyItems as $story)
                        <article class="events-story-card">
                            <div class="events-story-card-media" role="presentation">
                                @if(($story['media_type'] ?? '') === 'image' && trim((string) ($story['media_url'] ?? '')) !== '')
                                    <img src="{{ $story['media_url'] }}" alt="{{ $story['title'] ?? 'Story media' }}">
                                @elseif(($story['media_type'] ?? '') === 'video' && trim((string) ($story['media_url'] ?? '')) !== '')
                                    <video controls preload="metadata">
                                        <source src="{{ $story['media_url'] }}">
                                    </video>
                                @elseif(trim((string) ($story['thumbnail_url'] ?? '')) !== '')
                                    <img src="{{ $story['thumbnail_url'] }}" alt="{{ $story['title'] ?? 'Story media' }}">
                                @endif
                            </div>
                            <div class="events-story-card-body">
                                <p class="events-story-kicker">{{ $story['department'] ?? 'Department' }} · {{ $story['date_range'] ?? 'Date to be shared' }}</p>
                                <h3>{{ $story['title'] ?? 'Story' }}</h3>
                                <p>{{ $story['details'] ?? 'Story details will be shared soon.' }}</p>
                            </div>
                        </article>
                    @endforeach
                </section>
            @endif
        </section>
    @elseif($selectedSection === 'gallery')
        <section class="events-page" aria-label="Event gallery">
            <nav class="events-breadcrumb" aria-label="Gallery breadcrumb">
                <a href="{{ route('events') }}">Events</a>
                <span aria-hidden="true">›</span>
                <span>Gallery</span>
            </nav>

            <header class="events-gallery-head">
                <h1 class="events-gallery-title">Photo Gallery</h1>
                <p class="events-gallery-subtitle">A visual collection of worship, outreach, youth activities, and ministry moments inspired by the provided gallery design.</p>
            </header>

            <section class="events-gallery-slider-wrap" aria-label="Sliding gallery preview strip">
                @if(empty($galleryItems))
                    <article class="events-section-empty">
                        <h3>No gallery items found</h3>
                        <p>No gallery content is available for {{ $selectedCategoryLabel }} yet.</p>
                    </article>
                @else
                    <div class="events-gallery-track">
                        @for($round = 0; $round < 2; $round++)
                            @foreach($galleryItems as $item)
                                <article class="events-gallery-slide" aria-label="{{ $item['title'] }}">
                                    <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}">
                                </article>
                            @endforeach
                        @endfor
                    </div>
                @endif
            </section>

            @if(!empty($galleryItems))
                <section class="events-gallery-grid" aria-label="Church photo gallery">
                    @foreach($galleryItems as $index => $item)
                        <article class="events-gallery-card" data-gallery-index="{{ $index }}" data-gallery-title="{{ $item['title'] }}" data-gallery-meta="{{ $item['meta'] }}" data-gallery-image="{{ $item['image'] }}">
                            <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}">
                            <div class="events-gallery-caption">
                                <h3>{{ $item['title'] }}</h3>
                                <p>{{ $item['meta'] }}</p>
                            </div>
                        </article>
                    @endforeach
                </section>
            @endif

            <div id="eventsGalleryModal" class="events-gallery-modal" aria-hidden="true">
                <div class="events-gallery-dialog" role="dialog" aria-modal="true" aria-labelledby="eventsGalleryDialogTitle">
                    <div class="events-gallery-dialog-head">
                        <p id="eventsGalleryDialogTitle" class="events-gallery-dialog-title">Gallery Image</p>
                        <div class="events-gallery-controls">
                            <button id="eventsGalleryPrev" class="events-gallery-btn" type="button">Prev</button>
                            <button id="eventsGalleryZoomOut" class="events-gallery-btn" type="button">-</button>
                            <button id="eventsGalleryZoomIn" class="events-gallery-btn" type="button">+</button>
                            <button id="eventsGalleryReset" class="events-gallery-btn" type="button">Reset</button>
                            <button id="eventsGalleryNext" class="events-gallery-btn" type="button">Next</button>
                            <button id="eventsGalleryClose" class="events-gallery-btn" type="button">Close</button>
                        </div>
                    </div>
                    <div class="events-gallery-body">
                        <div class="events-gallery-stage">
                            <img id="eventsGalleryZoomImage" class="events-gallery-zoom-image" src="" alt="Gallery image">
                        </div>
                    </div>
                    <div class="events-gallery-meta">
                        <h3 id="eventsGalleryMetaTitle"></h3>
                        <p id="eventsGalleryMetaText"></p>
                    </div>
                </div>
            </div>

            <script>
                (function () {
                    const cards = Array.from(document.querySelectorAll('.events-gallery-card[data-gallery-index]'));
                    const modal = document.getElementById('eventsGalleryModal');
                    const image = document.getElementById('eventsGalleryZoomImage');
                    const title = document.getElementById('eventsGalleryMetaTitle');
                    const meta = document.getElementById('eventsGalleryMetaText');
                    const dialogTitle = document.getElementById('eventsGalleryDialogTitle');
                    const closeBtn = document.getElementById('eventsGalleryClose');
                    const prevBtn = document.getElementById('eventsGalleryPrev');
                    const nextBtn = document.getElementById('eventsGalleryNext');
                    const zoomInBtn = document.getElementById('eventsGalleryZoomIn');
                    const zoomOutBtn = document.getElementById('eventsGalleryZoomOut');
                    const resetBtn = document.getElementById('eventsGalleryReset');

                    if (!cards.length || !modal || !image || !title || !meta || !dialogTitle || !closeBtn || !prevBtn || !nextBtn || !zoomInBtn || !zoomOutBtn || !resetBtn) {
                        return;
                    }

                    let index = 0;
                    let zoom = 1;

                    const clamp = function (value, min, max) {
                        return Math.max(min, Math.min(max, value));
                    };

                    const updateZoom = function () {
                        image.style.transform = 'scale(' + zoom + ')';
                    };

                    const setCard = function (newIndex) {
                        index = (newIndex + cards.length) % cards.length;
                        const card = cards[index];

                        image.src = card.getAttribute('data-gallery-image') || '';
                        image.alt = card.getAttribute('data-gallery-title') || 'Gallery image';
                        title.textContent = card.getAttribute('data-gallery-title') || 'Gallery Image';
                        meta.textContent = card.getAttribute('data-gallery-meta') || '';
                        dialogTitle.textContent = 'Image ' + (index + 1) + ' of ' + cards.length;
                        zoom = 1;
                        updateZoom();
                    };

                    const open = function (newIndex) {
                        setCard(newIndex);
                        modal.classList.add('show');
                        modal.setAttribute('aria-hidden', 'false');
                        document.body.style.overflow = 'hidden';
                    };

                    const close = function () {
                        modal.classList.remove('show');
                        modal.setAttribute('aria-hidden', 'true');
                        document.body.style.overflow = '';
                    };

                    cards.forEach(function (card, cardIndex) {
                        card.addEventListener('click', function () {
                            open(cardIndex);
                        });
                    });

                    closeBtn.addEventListener('click', close);
                    prevBtn.addEventListener('click', function () { setCard(index - 1); });
                    nextBtn.addEventListener('click', function () { setCard(index + 1); });

                    zoomInBtn.addEventListener('click', function () {
                        zoom = clamp(zoom + 0.2, 1, 3);
                        updateZoom();
                    });

                    zoomOutBtn.addEventListener('click', function () {
                        zoom = clamp(zoom - 0.2, 1, 3);
                        updateZoom();
                    });

                    resetBtn.addEventListener('click', function () {
                        zoom = 1;
                        updateZoom();
                    });

                    modal.addEventListener('click', function (event) {
                        if (event.target === modal) {
                            close();
                        }
                    });

                    document.addEventListener('keydown', function (event) {
                        if (!modal.classList.contains('show')) {
                            return;
                        }

                        if (event.key === 'Escape') {
                            close();
                        } else if (event.key === 'ArrowRight') {
                            setCard(index + 1);
                        } else if (event.key === 'ArrowLeft') {
                            setCard(index - 1);
                        }
                    });
                })();
            </script>
        </section>
    @elseif($selectedSection === 'videos')
        <section class="events-page" aria-label="Event videos">
            <nav class="events-breadcrumb" aria-label="Videos breadcrumb">
                <a href="{{ route('events') }}">Events</a>
                <span aria-hidden="true">›</span>
                <span>Videos</span>
            </nav>

            <div class="events-video-shell">
                <div class="events-video-hero">
                    <div class="events-video-head">
                        <h1>Event Videos</h1>
                        <p class="events-subtitle">A unified video experience across all events categories.</p>
                    </div>
                    <div class="events-video-meta-row">
                        <span class="events-video-active-category">{{ $selectedVideoCategoryLabel }}</span>
                        <span class="events-video-count">{{ $videoItems->count() }} videos</span>
                    </div>
                </div>

                <div class="events-video-filter-bar">
                    <div class="events-video-categories" aria-label="Video categories">
                        <a class="events-video-chip {{ $selectedCategory === '' ? 'active' : '' }}" href="{{ route('events', ['section' => 'videos']) }}">All Categories</a>
                        @foreach($videoCategoryList as $videoCategory)
                            <a
                                class="events-video-chip {{ $selectedCategory === $videoCategory['slug'] ? 'active' : '' }}"
                                href="{{ route('events', ['section' => 'videos', 'category' => $videoCategory['slug']]) }}"
                            >
                                {{ $videoCategory['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <section class="events-videos-grid" aria-label="Event videos list">
                    @if($videoItems->isEmpty())
                        <article class="events-video-empty">
                            <h3>No videos yet for {{ $selectedVideoCategoryLabel }}</h3>
                            <p>Upload a video for this event category from the admin dashboard and it will appear here in the same layout as the other event categories.</p>
                        </article>
                    @else
                        @foreach($videoItems as $video)
                            <article class="events-video-card">
                                <div class="events-video-media">
                                    @if(is_string($video['embed_url']) && trim($video['embed_url']) !== '')
                                        <iframe src="{{ $video['embed_url'] }}" title="{{ $video['title'] }}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen loading="lazy"></iframe>
                                    @elseif(is_string($video['local_video_url']) && trim($video['local_video_url']) !== '')
                                        <video controls preload="metadata" @if(is_string($video['thumbnail_url']) && trim($video['thumbnail_url']) !== '') poster="{{ $video['thumbnail_url'] }}" @endif>
                                            <source src="{{ $video['local_video_url'] }}">
                                        </video>
                                    @else
                                        <div class="events-video-placeholder">Video Preview</div>
                                    @endif
                                </div>
                                <div class="events-video-body">
                                    <p class="events-video-category">{{ $video['category_label'] }}</p>
                                    <h3>{{ $video['title'] }}</h3>
                                    <p>{{ $video['description'] }}</p>
                                </div>
                            </article>
                        @endforeach
                    @endif
                </section>
            </div>
        </section>
    @else
        <section class="events-page" aria-label="Church events">
            <h1>Events</h1>
            <p class="events-subtitle">Know the events at your local church</p>

            @foreach($groupedEvents as $monthLabel => $monthEvents)
                <h2 class="events-month-title">{{ $monthLabel }}</h2>
                <section class="events-list" aria-label="{{ $monthLabel }} events">
                    @forelse($monthEvents as $event)
                        <article class="event-row">
                            <div class="event-meta">{{ $event['date_range'] ?? 'Date to be shared' }} · {{ $event['department'] ?? 'Department' }}</div>
                            <h3 class="event-title">{{ $event['title'] ?? 'Event' }}</h3>
                            <p class="event-details">{{ $event['details'] ?? 'Details will be shared soon.' }}</p>
                        </article>
                    @empty
                        <p class="events-empty">No events available for this month.</p>
                    @endforelse
                </section>
            @endforeach
        </section>
    @endif
@endsection

