@extends('layouts.site')

@section('title', 'SDA CHURCH MUBS | About Us')

@section('content')
    @php
        $content = \App\Support\AdminContentStore::get();
        $pastorsSinceInception = collect($content['pastors_since_inception'] ?? [])->values();
    @endphp
    <style>
        .about-page {
            display: grid;
            gap: 1rem;
        }

        .about-hero {
            position: relative;
            min-height: 290px;
            border-radius: 10px;
            overflow: hidden;
            background-image: linear-gradient(135deg, rgba(15, 43, 85, 0.84), rgba(62, 131, 145, 0.62)),
                url('https://unitedafricansda.org/wp-content/uploads/2023/05/temp-hp-banner.jpg');
            background-size: cover;
            background-position: center;
            display: grid;
            align-content: center;
            padding: 1.3rem;
        }

        .about-hero h1 {
            margin: 0;
            color: #ffffff;
            text-transform: uppercase;
            line-height: 1.06;
            font-size: clamp(2rem, 5.2vw, 3.4rem);
        }

        .about-hero p {
            margin: 0.7rem 0 0;
            color: #f2f7ff;
            line-height: 1.55;
            max-width: 920px;
            font-size: 1.01rem;
        }

        .about-section {
            background: #ffffff;
            border-radius: 10px;
            padding: 1rem;
            border: 1px solid #d9e0ec;
        }

        .about-section-title {
            margin: 0 0 0.8rem;
            color: #0f2b55;
            font-size: clamp(1.5rem, 3vw, 2.15rem);
            text-align: center;
            text-transform: uppercase;
        }

        .approach-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.9rem;
        }

        .approach-item {
            background: whitesmoke;
            border: 1px solid #d9e0ec;
            border-left: 5px solid #3e8391;
            border-radius: 10px;
            padding: 0.85rem;
        }

        .approach-item h3 {
            margin: 0 0 0.45rem;
            color: #0f2b55;
            font-size: 1.16rem;
            text-transform: uppercase;
        }

        .approach-item p {
            margin: 0;
            color: #33435b;
            line-height: 1.56;
        }

        .history-wrap {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            padding: 1.15rem;
            background-image: linear-gradient(145deg, rgba(15, 43, 85, 0.88), rgba(15, 43, 85, 0.78)),
                url('https://unitedafricansda.org/wp-content/uploads/2022/04/b878c7ff7fe36ad344f3837ddb8358e0.jpg');
            background-size: cover;
            background-position: center;
        }

        .history-kicker {
            margin: 0;
            text-align: center;
            color: #9dc9d0;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .history-title {
            margin: 0.32rem 0 0.9rem;
            text-align: center;
            color: #ffffff;
            text-transform: uppercase;
            font-size: clamp(1.6rem, 3.1vw, 2.3rem);
        }

        .history-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.95rem;
        }

        .history-grid p {
            margin: 0;
            color: #f2f6ff;
            line-height: 1.64;
            text-align: justify;
        }

        .pastors-wrap {
            background: #f4f7fc;
            border: 1px solid #d9e0ec;
            border-radius: 10px;
            padding: 1rem;
        }

        .pastors-title {
            margin: 0 0 1rem;
            text-align: center;
            color: #0f2b55;
            text-transform: uppercase;
            font-size: clamp(1.45rem, 2.8vw, 2.1rem);
        }

        .timeline {
            position: relative;
            display: grid;
            gap: 0.85rem;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 50%;
            top: 0;
            bottom: 0;
            width: 3px;
            background: #0f2b55;
            opacity: 0.22;
            transform: translateX(-50%);
        }

        .timeline-item {
            position: relative;
            display: grid;
            grid-template-columns: 1fr 36px 1fr;
            align-items: center;
            gap: 0.85rem;
        }

        .timeline-dot {
            width: 14px;
            height: 14px;
            border-radius: 999px;
            background: #3e8391;
            border: 2px solid #0f2b55;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .pastor-card {
            background: #ffffff;
            border: 1px solid #d9e0ec;
            border-radius: 10px;
            padding: 0.7rem;
            display: grid;
            grid-template-columns: 90px 1fr;
            gap: 0.7rem;
            align-items: center;
        }

        .pastor-card img {
            width: 90px;
            height: 120px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid #cfd8e6;
            background: #f2f5fb;
        }

        .pastor-empty {
            width: 90px;
            height: 120px;
            border-radius: 8px;
            border: 1px dashed #9cb0cc;
            color: #566b89;
            font-size: 0.76rem;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f2f5fb;
            padding: 0.25rem;
        }

        .pastor-years {
            margin: 0;
            color: #3e8391;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-weight: 700;
        }

        .pastor-name {
            margin: 0.2rem 0 0;
            color: #0f2b55;
            font-size: 1.06rem;
            text-transform: uppercase;
        }

        .timeline-item.left .pastor-card {
            grid-column: 1;
        }

        .timeline-item.left .timeline-dot {
            grid-column: 2;
        }

        .timeline-item.right .pastor-card {
            grid-column: 3;
        }

        .timeline-item.right .timeline-dot {
            grid-column: 2;
        }

        .elders-wrap {
            background: #ffffff;
            border: 1px solid #d9e0ec;
            border-radius: 10px;
            padding: 0.95rem;
            text-align: center;
        }

        .elders-wrap img {
            width: 100%;
            max-width: 860px;
            border-radius: 10px;
            border: 1px solid #d3deed;
            display: block;
            margin: 0 auto;
        }

        .elders-wrap p {
            margin: 0.7rem 0 0;
            color: #0f2b55;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 700;
            font-size: 0.92rem;
        }

        @media (max-width: 940px) {
            .approach-grid,
            .history-grid {
                grid-template-columns: 1fr;
            }

            .timeline::before {
                left: 18px;
                transform: none;
            }

            .timeline-item {
                grid-template-columns: 36px 1fr;
            }

            .timeline-dot {
                grid-column: 1 !important;
            }

            .timeline-item .pastor-card {
                grid-column: 2 !important;
            }
        }
    </style>

    <section class="about-page" aria-label="About us page">
        <header class="about-hero">
            <h1>About Us</h1>
            <p>SDA CHURCH MUBS Kireka Hill District is a spiritually vibrant and mission-focused church family committed to Christ-centered worship, discipleship, and practical ministry in the community. We exist to prepare people for Christ’s soon return through Bible truth, active service, and unified fellowship.</p>
        </header>

        <section class="about-section" aria-label="Our approach section">
            <h2 class="about-section-title">Our Approach</h2>
            <div class="approach-grid">
                <article class="approach-item">
                    <h3>Our Mission</h3>
                    <p>Make disciples of Jesus Christ whose lives witness to His saving grace and proclaim the everlasting gospel in preparation for His soon return.</p>
                </article>

                <article class="approach-item">
                    <h3>Our Method</h3>
                    <p>Guided by the Bible and the Holy Spirit, we build a spiritually and culturally relevant church community where every member uses spiritual gifts in ministry.</p>
                </article>

                <article class="approach-item">
                    <h3>Vision Statement</h3>
                    <p>We envision a united, dedicated, spiritually vibrant, and mission-minded Christian community whose Christlike character advances God’s kingdom.</p>
                </article>
            </div>
        </section>

        <section class="history-wrap" aria-label="Our history section">
            <p class="history-kicker">With God all things are possible</p>
            <h2 class="history-title">Our History</h2>
            <div class="history-grid">
                <p>In its early stages, the church family began with a small number of believers meeting for prayer, Bible study, and fellowship. Over time, this fellowship grew into a committed community of worshipers who desired to establish a stronger local ministry presence rooted in faith and service.</p>
                <p>As membership expanded, ministry structures developed to support evangelism, family discipleship, youth formation, and community outreach. Through consistent prayer, stewardship, and shared mission, SDA CHURCH MUBS Kireka Hill District continues to grow as a Christ-centered church dedicated to preparing people for the second coming of Jesus.</p>
            </div>
        </section>

        <section class="pastors-wrap" aria-label="Pastors since inception section">
            <h2 class="pastors-title">Pastors Since Inception</h2>
            <div class="timeline">
                @forelse($pastorsSinceInception as $index => $pastor)
                    @php
                        $position = $index % 2 === 0 ? 'left' : 'right';
                        $name = trim((string) ($pastor['name'] ?? ''));
                        $years = trim((string) ($pastor['years'] ?? ''));
                        $photo = trim((string) ($pastor['photo'] ?? ''));
                    @endphp
                    <article class="timeline-item {{ $position }}">
                        @if($position === 'right')
                            <span class="timeline-dot" aria-hidden="true"></span>
                        @endif
                        <div class="pastor-card">
                            @if($photo !== '')
                                <img src="{{ \Illuminate\Support\Str::startsWith($photo, ['http://', 'https://']) ? $photo : asset($photo) }}" alt="{{ $name !== '' ? $name : 'Pastor photo' }}" loading="lazy">
                            @else
                                <div class="pastor-empty">Image placeholder</div>
                            @endif
                            <div>
                                <p class="pastor-years">{{ $years !== '' ? $years : 'Years not set' }}</p>
                                <h3 class="pastor-name">{{ $name !== '' ? $name : 'Pastor name' }}</h3>
                            </div>
                        </div>
                        @if($position === 'left')
                            <span class="timeline-dot" aria-hidden="true"></span>
                        @endif
                    </article>
                @empty
                    <article class="timeline-item left">
                        <div class="pastor-card">
                            <div class="pastor-empty">Image placeholder</div>
                            <div>
                                <p class="pastor-years">Years not set</p>
                                <h3 class="pastor-name">Pastor history not available</h3>
                            </div>
                        </div>
                        <span class="timeline-dot" aria-hidden="true"></span>
                    </article>
                @endforelse
            </div>
        </section>

        <section class="elders-wrap" aria-label="Pioneer elders section">
            <img src="https://unitedafricansda.org/wp-content/uploads/brizy/imgs/UASDAC-Elders-scaled-570x440x56x7x513x383x1686632417.jpg" alt="The Pioneer Elders of United African SDA Church" loading="lazy">
            <p>The Pioneer Elders of SDA CHURCH MUBS</p>
        </section>
    </section>
@endsection

