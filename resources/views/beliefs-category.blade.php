@extends('layouts.site')

@section('title', 'SDA CHURCH MUBS | Beliefs ' . $range)

@section('content')
    <style>
        .belief-page {
            background: #efefef;
            border-radius: 0;
            border: 0;
            padding: 1.4rem;
        }

        .belief-breadcrumb {
            margin: 0 0 0.5rem;
            font-size: 1rem;
            line-height: 1.4;
        }

        .belief-breadcrumb a {
            color: #24a5b8;
            text-decoration: none;
            font-weight: 500;
        }

        .belief-breadcrumb a:hover {
            text-decoration: underline;
        }

        .belief-breadcrumb span {
            color: #3a3a3a;
        }

        .belief-main-title {
            margin: 0;
            color: #515358;
            font-size: clamp(2rem, 5vw, 3.4rem);
            line-height: 1.1;
        }

        .belief-intro {
            margin: 2.1rem 0 2.3rem;
            color: #0c1f3d;
            font-size: clamp(1.05rem, 2.1vw, 2rem);
            line-height: 1.25;
            max-width: 1000px;
            font-weight: 800;
        }

        .belief-statement-title {
            margin: 0 0 0.6rem;
            font-size: clamp(1.12rem, 2vw, 2rem);
            color: #121826;
            font-weight: 700;
        }

        .belief-statement {
            margin-bottom: 1.8rem;
        }

        .belief-statement:last-child {
            margin-bottom: 0;
        }

        .belief-statement-body {
            margin: 0;
            color: #17233f;
            font-size: 1.25rem;
            line-height: 1.8;
            max-width: 1080px;
        }

        .belief-other-wrap {
            margin-top: 2.2rem;
            border-top: 1px solid #d8dde6;
            padding-top: 1.2rem;
        }

        .belief-other-title {
            margin: 0 0 0.75rem;
            color: var(--text-muted);
            font-size: 1.08rem;
            font-weight: 700;
        }

        .belief-other-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.8rem;
        }

        .belief-other-card {
            border: 0;
            padding: 0;
            text-align: left;
            border-radius: 10px;
            overflow: hidden;
            cursor: pointer;
            background: #ffffff;
            box-shadow: 0 8px 20px rgba(15, 43, 85, 0.12);
            text-decoration: none;
            color: inherit;
        }

        .belief-other-media {
            position: relative;
            height: 180px;
            overflow: hidden;
        }

        .belief-other-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .belief-other-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(8, 21, 45, 0.12) 0%, rgba(8, 21, 45, 0.74) 100%);
            color: #ffffff;
            padding: 0.8rem;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }

        .belief-other-kicker {
            font-size: 0.72rem;
            letter-spacing: 0.8px;
            font-weight: 700;
            opacity: 0.95;
            margin-bottom: 0.2rem;
        }

        .belief-other-name {
            margin: 0;
            font-size: 1rem;
            line-height: 1.2;
            font-weight: 700;
        }

        @media (max-width: 900px) {
            .belief-other-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 640px) {
            .belief-other-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <section class="belief-page">
        <p class="belief-breadcrumb">
            <a href="{{ route('home') }}">About</a> -
            <a href="{{ route('home') }}#ourBeliefsSection">Our beliefs</a> -
            <span>Beliefs {{ $range }}</span>
        </p>

        <h1 class="belief-main-title">{{ $category['title'] }}</h1>

        <p class="belief-intro">{{ $category['intro'] }}</p>

        @foreach(($category['statements'] ?? []) as $statement)
            <article class="belief-statement">
                <h2 class="belief-statement-title">{{ $statement['number'] }}. {{ $statement['title'] }}</h2>
                <p class="belief-statement-body">{{ $statement['body'] }}</p>
            </article>
        @endforeach

        @if(!empty($otherCategories))
            <section class="belief-other-wrap" aria-label="Other belief categories">
                <h2 class="belief-other-title">Other belief categories</h2>
                <div class="belief-other-grid">
                    @foreach($otherCategories as $item)
                        <a class="belief-other-card" href="{{ route('beliefs.category', ['range' => $item['range']]) }}">
                            <div class="belief-other-media">
                                <img src="{{ asset($item['image']) }}" alt="Beliefs {{ $item['range'] }}">
                                <div class="belief-other-overlay">
                                    <span class="belief-other-kicker">BELIEFS {{ $item['range'] }}</span>
                                    <h3 class="belief-other-name">{{ $item['title'] }}</h3>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </section>
@endsection

