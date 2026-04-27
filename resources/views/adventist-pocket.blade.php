@extends('layouts.site')

@section('title', 'SDA CHURCH MUBS | Adventist Pocket')

@section('content')
    @php
        $pocketSections = [
            [
                'title' => 'SDA Adventist Books',
                'kicker' => 'Library',
                'image' => 'books.jpg',
                'links' => [
                    ['label' => 'SDA Adventist Books', 'url' => 'https://www.sabbath.school/LessonBook'],
                    ['label' => 'Sabbath School Lesson Book Quarterly', 'url' => 'https://www.sabbath.school/LessonBook'],
                    ['label' => 'Adventist Digital Library (books & journals)', 'url' => 'https://adventistdigitallibrary.org/'],
                    ['label' => 'Adventist Book Center', 'url' => 'https://www.adventistbookcenter.com/'],
                    ['label' => 'Encyclopedia of Seventh-day Adventists', 'url' => 'https://encyclopedia.adventist.org/'],
                ],
            ],
            [
                'title' => 'SDA Hymns',
                'kicker' => 'Worship',
                'image' => 'hymnals.jpg',
                'links' => [
                    ['label' => 'SDA Hymnal (Hymnary)', 'url' => 'https://hymnary.org/hymnal/SDAH1985'],
                    ['label' => 'SDA Hymns Audio & Video', 'url' => 'https://www.youtube.com/results?search_query=Seventh-day+Adventist+Hymns'],
                    ['label' => 'SDA Hymnal Apps', 'url' => 'https://play.google.com/store/search?q=sda%20hymnal&c=apps'],
                ],
            ],
            [
                'title' => 'All Bible Versions',
                'kicker' => 'Bible Tools',
                'image' => 'all versions.jpg',
                'links' => [
                    ['label' => 'YouVersion Bible (many versions & languages)', 'url' => 'https://www.bible.com/'],
                    ['label' => 'BibleGateway (search many Bible versions)', 'url' => 'https://www.biblegateway.com/'],
                    ['label' => 'Blue Letter Bible (study and versions)', 'url' => 'https://www.blueletterbible.org/'],
                ],
            ],
            [
                'title' => 'Ellen G. White Books',
                'kicker' => 'Prophetic Writings',
                'image' => 'white.jpg',
                'links' => [
                    ['label' => 'EGW Writings (official website)', 'url' => 'https://egwwritings.org/'],
                    ['label' => 'EGW Writings Mobile', 'url' => 'https://m.egwwritings.org/'],
                    ['label' => 'EGW Writings Mobile Apps', 'url' => 'https://play.google.com/store/search?q=egw%20writings&c=apps'],
                ],
            ],
            [
                'title' => 'Adventist Book Departments',
                'kicker' => 'Departments',
                'image' => 'adve books.jpg',
                'links' => [
                    ['label' => 'Bibles & Study Bibles', 'url' => 'https://www.adventistbookcenter.com/bibles.html'],
                    ['label' => 'Devotionals', 'url' => 'https://www.adventistbookcenter.com/devotionals.html'],
                    ['label' => 'Children & Youth Books', 'url' => 'https://www.adventistbookcenter.com/children-s-books.html'],
                    ['label' => 'Church Leadership Resources', 'url' => 'https://www.adventistbookcenter.com/church-resources.html'],
                    ['label' => 'Health & Wellness Books', 'url' => 'https://www.adventistbookcenter.com/health-and-wellness.html'],
                    ['label' => 'Family & Marriage Books', 'url' => 'https://www.adventistbookcenter.com/family-living.html'],
                ],
            ],
            [
                'title' => 'Church Manual',
                'kicker' => 'Governance',
                'image' => 'manual.jpg',
                'links' => [
                    ['label' => 'Seventh-day Adventist Church Manual', 'url' => 'https://www.adventist.org/church-manual/'],
                    ['label' => 'Official Statements & Guidelines', 'url' => 'https://www.adventist.org/official-statements/'],
                    ['label' => 'Adventist Archives (historical manuals)', 'url' => 'https://www.adventistarchives.org/'],
                ],
                'note' => 'Use these official sources to access available editions and authorized materials.',
            ],
        ];
    @endphp
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Manrope:wght@500;700;800&display=swap');

        .pocket-page {
            display: grid;
            gap: 1.1rem;
            position: relative;
        }

        .pocket-page::before {
            content: "";
            position: absolute;
            inset: -18px -16px auto;
            height: 220px;
            border-radius: 22px;
            background:
                radial-gradient(circle at 12% 20%, rgba(255, 208, 112, 0.24), rgba(255, 208, 112, 0) 43%),
                radial-gradient(circle at 88% 4%, rgba(131, 186, 255, 0.22), rgba(131, 186, 255, 0) 46%),
                linear-gradient(132deg, #0c2748 0%, #123f6a 49%, #1a6ba1 100%);
            box-shadow: 0 22px 38px rgba(9, 31, 60, 0.24);
            z-index: 0;
            pointer-events: none;
        }

        .pocket-hero {
            position: relative;
            overflow: hidden;
            border-radius: 18px;
            padding: clamp(1.1rem, 3.2vw, 2rem);
            background: linear-gradient(128deg, rgba(9, 34, 62, 0.94) 0%, rgba(18, 72, 118, 0.9) 52%, rgba(40, 118, 173, 0.86) 100%);
            color: #ffffff;
            box-shadow: 0 15px 30px rgba(9, 30, 59, 0.24);
            z-index: 1;
        }

        .pocket-hero::before {
            content: "";
            position: absolute;
            inset: -28% auto auto -8%;
            width: 250px;
            height: 250px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.22), rgba(255, 255, 255, 0));
            pointer-events: none;
        }

        .pocket-hero-title {
            margin: 0;
            font-size: clamp(1.6rem, 4.2vw, 2.45rem);
            line-height: 1.06;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            position: relative;
            z-index: 1;
            font-family: 'Cinzel', 'Times New Roman', serif;
        }

        .pocket-intro {
            margin: 0.7rem 0 0;
            max-width: 780px;
            color: rgba(237, 245, 255, 0.95);
            line-height: 1.6;
            position: relative;
            z-index: 1;
            font-family: 'Manrope', 'Segoe UI', sans-serif;
            font-weight: 500;
        }

        .pocket-quick-links {
            margin-top: 0.9rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.45rem;
            position: relative;
            z-index: 1;
        }

        .pocket-chip {
            display: inline-flex;
            align-items: center;
            padding: 0.26rem 0.62rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.16);
            border: 1px solid rgba(255, 255, 255, 0.24);
            color: #f8fbff;
            text-decoration: none;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            font-family: 'Manrope', 'Segoe UI', sans-serif;
        }

        .pocket-chip:hover {
            background: rgba(255, 255, 255, 0.24);
        }

        .pocket-list {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1.05rem;
            z-index: 1;
        }

        .pocket-section {
            border: 1px solid #d6e1f2;
            border-radius: 16px;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            box-shadow: 0 12px 22px rgba(15, 43, 85, 0.09);
            overflow: hidden;
            display: grid;
            align-content: start;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        .pocket-section:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 28px rgba(15, 43, 85, 0.14);
        }

        .pocket-media {
            display: block;
            width: 100%;
            height: 194px;
            object-fit: cover;
            object-position: center;
            border-bottom: 1px solid #dbe7f7;
            background: #ffffff;
        }

        .pocket-section-body {
            padding: 0.95rem 1rem 1rem;
            display: grid;
            gap: 0.56rem;
        }

        .pocket-kicker {
            display: inline-flex;
            width: fit-content;
            padding: 0.2rem 0.54rem;
            border-radius: 999px;
            background: #e8f2ff;
            color: #174c86;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            font-family: 'Manrope', 'Segoe UI', sans-serif;
        }

        .pocket-section h2 {
            margin: 0;
            color: #0f2b55;
            font-size: 1.14rem;
            line-height: 1.28;
            font-family: 'Cinzel', 'Times New Roman', serif;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .pocket-links {
            margin: 0;
            padding-left: 1rem;
            display: grid;
            gap: 0.42rem;
        }

        .pocket-links li {
            color: #1f4a8a;
        }

        .pocket-links a {
            color: #1f4a8a;
            font-weight: 700;
            text-decoration: none;
            border-bottom: 1px dashed transparent;
            font-family: 'Manrope', 'Segoe UI', sans-serif;
            font-size: 0.95rem;
        }

        .pocket-links a:hover {
            border-bottom-color: #1f4a8a;
        }

        .pocket-note {
            margin: 0.2rem 0 0;
            color: #4e6383;
            font-size: 0.9rem;
            line-height: 1.45;
            font-family: 'Manrope', 'Segoe UI', sans-serif;
        }

        @media (max-width: 900px) {
            .pocket-list {
                grid-template-columns: 1fr;
            }

            .pocket-media {
                height: 176px;
            }
        }

        @media (max-width: 620px) {
            .pocket-page::before {
                inset: -12px -10px auto;
                height: 180px;
            }

            .pocket-hero {
                padding: 1rem;
            }

            .pocket-quick-links {
                gap: 0.35rem;
            }
        }
    </style>

    <section class="pocket-page" aria-label="Adventist Pocket resources">
        <section class="pocket-hero" aria-label="Adventist Pocket heading">
            <h1 class="pocket-hero-title">Adventist Pocket</h1>
            <p class="pocket-intro">Access official Adventist libraries and trusted tools for books, hymnals, Bible versions, Ellen G. White writings, and Church Manual resources in one well-arranged hub.</p>
            <div class="pocket-quick-links" aria-label="Quick section links">
                @foreach($pocketSections as $section)
                    <a class="pocket-chip" href="#pocket-{{ $loop->index + 1 }}">{{ $section['kicker'] }}</a>
                @endforeach
            </div>
        </section>

        <section class="pocket-list" aria-label="Adventist resource categories">
            @foreach($pocketSections as $section)
                <article class="pocket-section" id="pocket-{{ $loop->iteration }}">
                    @if(!empty($section['image']))
                        <img class="pocket-media" src="{{ asset(str_replace(' ', '%20', $section['image'])) }}" alt="{{ $section['title'] }} image" loading="lazy">
                    @endif
                    <div class="pocket-section-body">
                        <span class="pocket-kicker">{{ $section['kicker'] }}</span>
                        <h2>{{ $section['title'] }}</h2>
                        <ul class="pocket-links">
                            @foreach($section['links'] as $link)
                                <li>
                                    <a href="{{ $link['url'] }}" target="_blank" rel="noopener">{{ $link['label'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                        @if(!empty($section['note']))
                            <p class="pocket-note">{{ $section['note'] }}</p>
                        @endif
                    </div>
                </article>
            @endforeach
        </section>
    </section>
@endsection

