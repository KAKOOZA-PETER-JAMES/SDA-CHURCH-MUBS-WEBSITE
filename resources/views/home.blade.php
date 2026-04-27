@extends('layouts.site')

@section('title', 'SDA CHURCH MUBS | Home')

@section('content')
    @php
        $adminContent = \App\Support\AdminContentStore::get();
        $normalizeNoticeRows = function (array $rows): array {
            $normalized = [];
            foreach ($rows as $row) {
                if (is_string($row)) {
                    $normalized[] = ['text' => $row, 'media_url' => ''];
                    continue;
                }

                if (is_array($row)) {
                    $normalized[] = [
                        'text' => (string) ($row['text'] ?? ''),
                        'media_url' => (string) ($row['media_url'] ?? ''),
                    ];
                }
            }

            return $normalized;
        };

        $resolveNoticeMedia = function (string $path): string {
            $path = trim($path);
            if ($path === '') {
                return '';
            }

            if (preg_match('/^https?:\/\//i', $path)) {
                return $path;
            }

            return file_exists(public_path($path)) ? asset($path) : '';
        };

        $isVideoMedia = function (string $url): bool {
            return (bool) preg_match('/\.(mp4|webm|mov)(\?.*)?$/i', $url);
        };

        $upcomingSabbaths = $normalizeNoticeRows((array) ($adminContent['upcoming_sabbaths'] ?? []));
        $dailyCommunication = $normalizeNoticeRows((array) ($adminContent['daily_communication'] ?? []));
    @endphp

    <style>
        .home-who-page {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 450px;
            gap: 1rem;
            align-items: start;
        }

        .home-main {
            min-width: 0;
            max-width: 100%;
        }

        .who-card {
            background: transparent;
            border: 0;
            border-radius: 0;
            padding: 0;
        }

        .who-head {
            margin: 0 0 0.85rem;
            font-size: 2rem;
            color: #0f2b55;
            line-height: 1.1;
            font-weight: 800;
            padding-left: 0.7rem;
            border-left: 4px solid #0f2b55;
            text-transform: uppercase;
        }

        .who-text {
            margin: 0;
            color: var(--text-muted);
            line-height: 1.55;
            max-width: 860px;
        }

        .home-feature-section {
            margin-top: 1.2rem;
            display: grid;
            gap: 0.75rem;
            max-width: 930px;
        }

        .home-feature-head {
            margin: 0;
            color: #0f2b55;
            font-size: 1.35rem;
            line-height: 1.15;
            text-transform: uppercase;
            border-left: 4px solid #3e8391;
            padding-left: 0.6rem;
            font-weight: 800;
        }

        .home-feature-row {
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            border: 0;
            border-left: 5px solid #1f4a8a;
            border-radius: 12px;
            display: grid;
            grid-template-columns: 180px 1fr;
            gap: 0.9rem;
            padding: 0.75rem;
            box-shadow: 0 10px 20px rgba(15, 43, 85, 0.08);
        }

        .home-feature-image {
            width: 100%;
            height: 140px;
            border-radius: 10px;
            object-fit: cover;
            border: 1px solid #cedaee;
            background: #eef4ff;
        }

        .home-feature-content h2 {
            margin: 0 0 0.4rem;
            color: #0f2b55;
            font-size: 1.24rem;
            text-transform: uppercase;
            line-height: 1.12;
        }

        .home-feature-content p {
            margin: 0;
            color: var(--text-muted);
            line-height: 1.5;
        }

        .home-feature-actions {
            margin-top: 0.75rem;
        }

        .know-more-btn {
            margin-top: 0.95rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 122px;
            padding: 0.56rem 0.95rem;
            border-radius: 8px;
            background: #ffffff;
            color: #1f4a8a;
            text-decoration: none;
            font-weight: 800;
            letter-spacing: 0.2px;
            border: 1px solid #1f4a8a;
            text-transform: uppercase;
            font-size: 0.9rem;
            transition: background-color 0.18s ease, color 0.18s ease, border-color 0.18s ease;
        }

        .know-more-btn:hover {
            background: #1f4a8a;
            border-color: #1f4a8a;
            color: #ffffff;
        }

        .who-actions {
            margin-top: 0.95rem;
            display: flex;
            gap: 0.7rem;
            flex-wrap: wrap;
        }

        .notice-board {
            background: whitesmoke;
            border: 0;
            border-left: 6px solid #0f2b55;
            border-radius: 8px;
            box-shadow: 0 10px 22px rgba(15, 43, 85, 0.12);
            padding: 1.1rem 1.15rem;
            position: sticky;
            top: 6.8rem;
            width: 100%;
            z-index: 20;
            max-height: calc(100vh - 7.8rem);
            overflow: auto;
        }

        .notice-section + .notice-section {
            margin-top: 1.15rem;
            padding-top: 0.95rem;
            border-top: 0;
        }

        .notice-section-pinned {
            position: relative;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            border: 1px solid #d5deec;
            border-radius: 10px;
            padding: 1rem 1rem 0.85rem;
            box-shadow: 0 12px 24px rgba(15, 43, 85, 0.16);
            transform-origin: 50% 10px;
            animation: noticeWindSway 5.8s ease-in-out infinite;
        }

        .notice-section-pinned:hover,
        .notice-section-pinned:focus-within {
            animation-play-state: paused;
        }

        .notice-section-pinned + .notice-section-pinned {
            margin-top: 1rem;
        }

        .notice-section-pinned::before {
            content: '';
            position: absolute;
            top: -20px;
            left: 50%;
            width: 36px;
            height: 36px;
            transform: translateX(-50%);
            filter: drop-shadow(0 4px 8px rgba(20, 32, 53, 0.3));
            z-index: 3;
            pointer-events: none;
        }

        .notice-special-days::before {
            background: url('{{ asset('pi.png') }}') center/contain no-repeat;
        }

        .notice-daily-board::before {
            background: url('{{ asset('pn.jpg') }}') center/contain no-repeat;
        }

        @keyframes noticeWindSway {
            0% { transform: rotate(-1.1deg); }
            25% { transform: rotate(0.8deg); }
            50% { transform: rotate(-0.5deg); }
            75% { transform: rotate(0.9deg); }
            100% { transform: rotate(-1.1deg); }
        }

        .notice-kicker {
            margin: 0 0 0.5rem;
            font-size: 0.82rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #0f2b55;
            font-weight: 800;
        }

        .notice-title {
            margin: 0 0 0.55rem;
            font-size: 1.85rem;
            line-height: 1.1;
            color: #0f2b55;
            font-weight: 800;
        }

        .notice-list {
            margin: 0;
            padding-left: 1.15rem;
            color: var(--text-muted);
        }

        .notice-special-days .notice-list,
        .notice-special-days .notice-list li,
        .notice-special-days .notice-item span,
        .notice-daily-board .notice-list,
        .notice-daily-board .notice-list li,
        .notice-daily-board .notice-item span {
            color: #000000;
        }

        .notice-special-days .notice-list a,
        .notice-daily-board .notice-list a {
            color: #000000;
        }

        .notice-list li {
            margin-bottom: 0.55rem;
            line-height: 1.4;
        }

        .notice-list a {
            color: #0f2b55;
            text-decoration: underline;
            font-weight: 600;
        }

        .notice-text {
            margin: 0;
            color: #2f3d57;
            line-height: 1.45;
        }

        .notice-text + .notice-text {
            margin-top: 0.55rem;
        }

        .notice-item {
            display: grid;
            gap: 0.45rem;
            margin-bottom: 0.65rem;
        }

        .notice-media {
            width: 100%;
            border-radius: 10px;
            border: 1px solid #cedaeb;
            background: #ecf3ff;
            max-height: 260px;
            object-fit: cover;
        }

        @media (max-width: 1200px) {
            .home-who-page {
                grid-template-columns: 1fr;
            }

            .home-main {
                max-width: 100%;
            }

            .notice-board {
                position: static;
                width: auto;
                max-height: none;
                overflow: visible;
                margin-top: 1rem;
            }
        }

        @media (max-width: 640px) {
            .notice-section-pinned {
                animation-duration: 6.6s;
            }

            .notice-section-pinned::before {
                width: 30px;
                height: 30px;
                top: -17px;
            }

            .who-head {
                font-size: 1.55rem;
            }

            .home-feature-row {
                grid-template-columns: 1fr;
                gap: 0.65rem;
                padding: 0.65rem;
            }

            .home-feature-image {
                height: 180px;
            }

            .notice-title {
                font-size: 1.45rem;
            }
        }
    </style>

    @if(session('welcome_name'))
        <style>
            .welcome-banner {
                max-width: 1200px;
                margin: 2rem auto;
                padding: 0 1rem;
                opacity: 1;
                transition: opacity 0.35s ease;
            }

            .welcome-banner.is-hiding {
                opacity: 0;
            }

            .welcome-card {
                background: linear-gradient(135deg, #102a52 0%, #0f2b55 100%);
                border-radius: 16px;
                overflow: hidden;
                box-shadow: 0 12px 40px rgba(12, 38, 74, 0.2);
                display: flex;
                align-items: center;
                gap: 2rem;
                padding: 2.5rem;
                color: #ffffff;
                animation: slideInDown 0.6s ease-out;
            }

            @keyframes slideInDown {
                from {
                    opacity: 0;
                    transform: translateY(-20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .welcome-icon {
                width: 80px;
                height: 80px;
                background: rgba(255, 255, 255, 0.15);
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                border: 2px solid rgba(255, 255, 255, 0.3);
                overflow: hidden;
                padding: 6px;
            }

            .welcome-icon img {
                width: 100%;
                height: 100%;
                object-fit: contain;
                display: block;
            }

            .welcome-content h2 {
                margin: 0 0 0.5rem;
                font-size: 1.5rem;
                font-weight: 700;
                color: #ffffff;
            }

            .welcome-content p {
                margin: 0;
                font-size: 1rem;
                opacity: 0.95;
                color: #ffffff;
            }

            .welcome-close {
                margin-left: auto;
                background: rgba(255, 255, 255, 0.2);
                border: none;
                color: #ffffff;
                font-size: 1.5rem;
                width: 40px;
                height: 40px;
                border-radius: 8px;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
            }

            .welcome-close:hover {
                background: rgba(255, 255, 255, 0.3);
            }

            @media (max-width: 768px) {
                .welcome-card {
                    flex-direction: column;
                    text-align: center;
                    padding: 2rem 1.5rem;
                    gap: 1rem;
                }

                .welcome-close {
                    margin-left: 0;
                }

                .welcome-content h2 {
                    font-size: 1.3rem;
                }
            }
        </style>

        <div id="welcomeBanner" class="welcome-banner">
            <div class="welcome-card">
                <div class="welcome-icon"><img src="{{ asset('6.png') }}" alt="SDA Church Logo"></div>
                <div class="welcome-content">
                    <h2>Welcome, {{ session('welcome_name') }}!</h2>
                    <p>You have successfully joined the SDA CHURCH MUBS association. We're excited to have you as part of our community!</p>
                </div>
                <button class="welcome-close" onclick="hideWelcomeBanner()" aria-label="Close welcome message">×</button>
            </div>
        </div>

        <script>
            (function () {
                const welcomeBanner = document.getElementById('welcomeBanner');

                window.hideWelcomeBanner = function () {
                    if (!welcomeBanner || welcomeBanner.classList.contains('is-hiding')) {
                        return;
                    }

                    welcomeBanner.classList.add('is-hiding');
                    window.setTimeout(function () {
                        if (welcomeBanner) {
                            welcomeBanner.style.display = 'none';
                        }
                    }, 360);
                };

                window.setTimeout(function () {
                    window.hideWelcomeBanner();
                }, 7000);
            })();
        </script>
    @endif

    <section class="home-who-page" aria-label="Who we are overview">
        <div class="home-main">
            <article class="who-card">
                <h1 class="who-head">Who We Are</h1>
                <p class="who-text">
                    The East-Central Africa Division (ECD) is one of the world divisions of the Seventh-day Adventist Church,
                    serving as part of the General Conference’s global family. Established to lead and coordinate the Church’s
                    mission across East-Central Africa, the ECD is dedicated to sharing the love of Christ and fostering holistic
                    growth in the communities it serves.
                    <br><br>
                    Our territory spans <strong>11 vibrant countries — Burundi, the Democratic Republic of the Congo, Djibouti,
                    Eritrea, Ethiopia, Kenya, Rwanda, Somalia, South Sudan, Tanzania, and Uganda</strong> — a diverse region where
                    millions of Adventists actively live out their faith and engage in God’s mission.
                </p>
                <div class="who-actions">
                    <a class="know-more-btn" href="{{ route('about-us') }}">Know More</a>
                </div>
            </article>

            <section class="home-feature-section" aria-label="Main actions with images">
                <h2 class="home-feature-head">Explore</h2>

                <article class="home-feature-row">
                    <img class="home-feature-image" src="{{ asset('join.jpg') }}" alt="Join Live stream">
                    <div class="home-feature-content">
                        <h2>Join Live</h2>
                        <p>Worship with us online and stay connected to live services, messages, and ministry moments in real time.</p>
                        <div class="home-feature-actions">
                            <a class="know-more-btn" href="https://www.youtube.com/results?search_query=musdaabs" target="_blank" rel="noopener">Know More</a>
                        </div>
                    </div>
                </article>

                <article class="home-feature-row">
                    <img class="home-feature-image" src="{{ asset('pocket.png') }}" alt="Adventist Pocket resources">
                    <div class="home-feature-content">
                        <h2>Adventist Pocket</h2>
                        <p>Access Adventist tools and faith resources that support daily spiritual growth, Bible learning, and discipleship.</p>
                        <div class="home-feature-actions">
                            <a class="know-more-btn" href="{{ route('adventist-pocket') }}">Know More</a>
                        </div>
                    </div>
                </article>

                <article class="home-feature-row">
                    <img class="home-feature-image" src="{{ asset('discover.jpg') }}" alt="Discover church history">
                    <div class="home-feature-content">
                        <h2>ADVENTIST HISTORY</h2>
                        <p>A course in Church History highlighting significant details of interest to the youth of the Seventh-day Adventist Church.</p>
                        <div class="home-feature-actions">
                            <a class="know-more-btn" href="{{ route('sda-history') }}">Know More</a>
                        </div>
                    </div>
                </article>
            </section>
        </div>

        <aside class="notice-board" aria-label="Church notice board">
            <section class="notice-section notice-section-pinned notice-special-days" aria-label="Upcoming sabbaths">
                <p class="notice-kicker">Special Days</p>
                <h2 class="notice-title">Upcoming Sabbaths</h2>
                <ul class="notice-list">
                    @forelse($upcomingSabbaths as $sabbath)
                        @php
                            $mediaUrl = $resolveNoticeMedia((string) ($sabbath['media_url'] ?? ''));
                        @endphp
                        <li>
                            <div class="notice-item">
                                <span>{{ $sabbath['text'] ?? '' }}</span>
                                @if($mediaUrl !== '')
                                    @if($isVideoMedia($mediaUrl))
                                        <video class="notice-media" controls preload="metadata">
                                            <source src="{{ $mediaUrl }}">
                                        </video>
                                    @else
                                        <img class="notice-media" src="{{ $mediaUrl }}" alt="Upcoming sabbath media" loading="lazy">
                                    @endif
                                @endif
                            </div>
                        </li>
                    @empty
                        <li>No upcoming Sabbath entries yet.</li>
                    @endforelse
                </ul>
            </section>

            <section class="notice-section notice-section-pinned notice-daily-board" aria-label="Daily communication updates">
                <p class="notice-kicker">Notice Board</p>
                <h2 class="notice-title">Daily Communication</h2>
                <ul class="notice-list">
                    @forelse($dailyCommunication as $line)
                        @php
                            $mediaUrl = $resolveNoticeMedia((string) ($line['media_url'] ?? ''));
                        @endphp
                        <li>
                            <div class="notice-item">
                                <span>{{ $line['text'] ?? '' }}</span>
                                @if($mediaUrl !== '')
                                    @if($isVideoMedia($mediaUrl))
                                        <video class="notice-media" controls preload="metadata">
                                            <source src="{{ $mediaUrl }}">
                                        </video>
                                    @else
                                        <img class="notice-media" src="{{ $mediaUrl }}" alt="Daily communication media" loading="lazy">
                                    @endif
                                @endif
                            </div>
                        </li>
                    @empty
                        <li>No daily communication notices yet.</li>
                    @endforelse
                </ul>
            </section>
        </aside>
    </section>
@endsection

