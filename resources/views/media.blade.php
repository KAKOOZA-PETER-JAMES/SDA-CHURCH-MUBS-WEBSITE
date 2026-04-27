@extends('layouts.site')

@section('title', 'SDA CHURCH MUBS | Media')

@section('content')
    <style>
        .video-page {
            display: grid;
            gap: 1rem;
        }

        .video-breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
            color: #5a6a83;
            font-size: 0.9rem;
        }

        .video-breadcrumb a {
            color: #1f4a8a;
            text-decoration: none;
            font-weight: 700;
        }

        .video-main-grid {
            display: flex;
            gap: 1rem;
            align-items: flex-start;
        }

        .video-main-card {
            flex: 1 1 66%;
            border: 1px solid #d9e0ec;
            border-radius: 10px;
            overflow: hidden;
            background: #ffffff;
        }

        .video-embed {
            position: relative;
            width: 100%;
            padding-top: 56.25%;
            background: #0f2b55;
        }

        .video-embed iframe {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }

        .video-main-meta {
            padding: 0.9rem;
        }

        .video-main-meta h2 {
            margin: 0;
            color: #0f2b55;
            font-size: 1.24rem;
            line-height: 1.3;
        }

        .video-main-date {
            margin: 0.35rem 0 0;
            color: #5b6c84;
            font-weight: 700;
        }

        .video-main-copy {
            margin: 0.45rem 0 0;
            color: #2f3d57;
        }

        .video-side {
            flex: 1 1 34%;
            display: grid;
            gap: 0.75rem;
        }

        .video-side-card {
            border: 1px solid #d9e0ec;
            border-radius: 10px;
            background: #ffffff;
            padding: 0.8rem;
        }

        .video-side-card h3 {
            margin: 0 0 0.5rem;
            color: #0f2b55;
            text-transform: uppercase;
            font-size: 0.95rem;
            letter-spacing: 0.03em;
        }

        .video-list {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            gap: 0.45rem;
        }

        .video-list a {
            color: #1f4a8a;
            text-decoration: none;
            font-weight: 700;
            line-height: 1.35;
        }

        .video-list small {
            display: block;
            color: #62738d;
            font-weight: 600;
        }

        .video-platforms {
            display: grid;
            gap: 0.45rem;
        }

        .video-platform {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            text-decoration: none;
            border: 1px solid #d6e0ef;
            border-radius: 8px;
            padding: 0.4rem 0.5rem;
            color: #1f4a8a;
            font-weight: 700;
        }

        .video-platform img {
            width: 40px;
            height: 40px;
            object-fit: contain;
            border-radius: 6px;
            border: 1px solid #d9e0ec;
            background: #ffffff;
        }

        @media (max-width: 980px) {
            .video-main-grid {
                flex-direction: column;
            }
        }
    </style>

    <section class="video-page" aria-label="Videos page">
        <nav class="video-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('media') }}">Watch</a>
            <span>/</span>
            <span>Channels</span>
            <span>/</span>
            <span>My Sabbath Sermons</span>
        </nav>

        <div class="video-main-grid">
            <article class="video-main-card">
                <div class="video-embed">
                    <iframe src="https://www.youtube.com/embed/r3of0iTDH64?rel=0&amp;controls=1" title="Sermon by Jerome Sassa" allowfullscreen loading="lazy"></iframe>
                </div>
                <div class="video-main-meta">
                    <h2>Sermon by: Jerome Sassa</h2>
                    <p class="video-main-date">12 Jun 2020</p>
                    <p class="video-main-copy">Watch the featured Sabbath sermon and continue with more church media links on the right.</p>
                </div>
            </article>

            <aside class="video-side" aria-label="Video sidebar">
                <article class="video-side-card">
                    <h3>More Videos</h3>
                    <ul class="video-list">
                        <li><a href="https://www.youtube.com/results?search_query=MUBS+SDA+Church+Sermon" target="_blank" rel="noopener">MUBS SDA Sabbath Sermons</a><small>YouTube Channel Search</small></li>
                        <li><a href="https://www.youtube.com/results?search_query=Hope+Channel+Uganda+Sabbath" target="_blank" rel="noopener">Hope Channel Uganda Sermons</a><small>Recent video collection</small></li>
                        <li><a href="https://adventist.news/" target="_blank" rel="noopener">Adventist News Network</a><small>Latest global church updates</small></li>
                    </ul>
                </article>

                <article class="video-side-card">
                    <h3>Media Platforms</h3>
                    <div class="video-platforms">
                        <a class="video-platform" href="https://www.primeradio.co.ug/" target="_blank" rel="noopener">
                            <img src="{{ asset('PRIME.jpg') }}" alt="Prime Radio logo">
                            <span>Prime Radio</span>
                        </a>
                        <a class="video-platform" href="https://awr.org/" target="_blank" rel="noopener">
                            <img src="{{ asset('adventist world radio.png') }}" alt="Adventist World Radio logo">
                            <span>Adventist World Radio</span>
                        </a>
                        <a class="video-platform" href="https://www.youtube.com/results?search_query=Hope+Channel+Uganda" target="_blank" rel="noopener">
                            <img src="{{ asset('HOPE.png') }}" alt="Hope Channel logo">
                            <span>Hope Channel Uganda</span>
                        </a>
                        <a class="video-platform" href="https://adventist.news/" target="_blank" rel="noopener">
                            <img src="{{ asset('adventist news network.jpg') }}" alt="Adventist News Network logo">
                            <span>Adventist News Network</span>
                        </a>
                    </div>
                </article>
            </aside>
        </div>
    </section>
@endsection

