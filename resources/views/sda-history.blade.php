@extends('layouts.site')

@section('title', 'SDA CHURCH MUBS | SDA History Library')

@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Manrope:wght@500;700;800&display=swap');

        .history-page {
            display: grid;
            gap: 1.1rem;
            position: relative;
        }

        .history-page::before {
            content: "";
            position: absolute;
            inset: -18px -16px auto;
            height: 224px;
            border-radius: 22px;
            background:
                radial-gradient(circle at 18% 20%, rgba(255, 188, 103, 0.26), rgba(255, 188, 103, 0) 44%),
                radial-gradient(circle at 84% 8%, rgba(147, 211, 255, 0.24), rgba(147, 211, 255, 0) 46%),
                linear-gradient(132deg, #0d1f38 0%, #153d67 44%, #205f92 100%);
            box-shadow: 0 22px 36px rgba(8, 27, 52, 0.24);
            z-index: 0;
            pointer-events: none;
        }

        .history-hero {
            position: relative;
            overflow: hidden;
            border-radius: 18px;
            padding: clamp(1.05rem, 3.3vw, 2rem);
            background: linear-gradient(128deg, rgba(9, 35, 61, 0.94) 0%, rgba(24, 68, 112, 0.9) 52%, rgba(36, 101, 151, 0.86) 100%);
            box-shadow: 0 16px 32px rgba(10, 35, 67, 0.24);
            color: #ffffff;
            z-index: 1;
        }

        .history-hero::after {
            content: "";
            position: absolute;
            right: -46px;
            top: -48px;
            width: 232px;
            height: 232px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.24), rgba(255, 255, 255, 0));
            pointer-events: none;
        }

        .history-hero h1 {
            margin: 0;
            font-size: clamp(1.66rem, 4.2vw, 2.62rem);
            line-height: 1.04;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            position: relative;
            z-index: 1;
            font-family: 'DM Serif Display', Georgia, serif;
        }

        .history-hero .lead {
            margin: 0.75rem 0 0;
            color: rgba(237, 245, 255, 0.95);
            line-height: 1.62;
            max-width: 760px;
            position: relative;
            z-index: 1;
            font-family: 'Manrope', 'Segoe UI', sans-serif;
        }

        .history-meta {
            margin-top: 0.75rem;
            color: #d8e8ff;
            font-size: 0.84rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            position: relative;
            z-index: 1;
            font-family: 'Manrope', 'Segoe UI', sans-serif;
        }

        .history-viewer {
            border: 1px solid #d4e3f6;
            border-radius: 18px;
            background: #ffffff;
            padding: 0.9rem;
            box-shadow: 0 14px 28px rgba(16, 44, 84, 0.11);
            z-index: 1;
        }

        .history-viewer-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.6rem;
            flex-wrap: wrap;
            margin-bottom: 0.7rem;
        }

        .history-viewer-title {
            margin: 0;
            font-size: 1.03rem;
            color: #12355f;
            font-weight: 800;
            font-family: 'Manrope', 'Segoe UI', sans-serif;
        }

        .history-viewer-frame {
            width: 100%;
            height: min(72vh, 780px);
            border: 1px solid #cfdaec;
            border-radius: 12px;
            background: #f7faff;
        }

        .history-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
            z-index: 1;
        }

        .history-item {
            border: 1px solid #d7e4f7;
            border-radius: 16px;
            background: linear-gradient(180deg, #ffffff, #f8fbff);
            padding: 0.95rem;
            display: grid;
            gap: 0.64rem;
            box-shadow: 0 9px 18px rgba(15, 43, 85, 0.08);
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        .history-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 24px rgba(15, 43, 85, 0.12);
        }

        .history-item-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.7rem;
            flex-wrap: wrap;
        }

        .history-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            height: 34px;
            border-radius: 999px;
            background: #e8f2ff;
            border: 1px solid #c6d8f2;
            color: #0f2b55;
            font-weight: 800;
            font-size: 0.85rem;
            font-family: 'Manrope', 'Segoe UI', sans-serif;
        }

        .history-item h2 {
            margin: 0;
            font-size: 1.12rem;
            color: #12355f;
            line-height: 1.35;
            font-family: 'DM Serif Display', Georgia, serif;
            letter-spacing: 0.01em;
        }

        .history-summary {
            margin: 0;
            color: #3d4f6b;
            font-size: 0.92rem;
            line-height: 1.55;
            font-family: 'Manrope', 'Segoe UI', sans-serif;
        }

        .history-actions {
            display: flex;
            gap: 0.55rem;
            flex-wrap: wrap;
            padding-top: 0.08rem;
        }

        .history-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            padding: 0.5rem 0.86rem;
            text-decoration: none;
            font-weight: 800;
            border: 1px solid #0f2b55;
            color: #0f2b55;
            background: #ffffff;
            font-family: 'Manrope', 'Segoe UI', sans-serif;
        }

        .history-btn:hover {
            background: #eef4ff;
        }

        .history-btn.primary {
            background: #0f2b55;
            color: #ffffff;
        }

        .history-btn.primary:hover {
            background: #153665;
            border-color: #153665;
        }

        .history-empty {
            margin-top: 0.4rem;
            z-index: 1;
        }

        @media (max-width: 900px) {
            .history-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 680px) {
            .history-page::before {
                inset: -12px -10px auto;
                height: 182px;
            }

            .history-viewer-frame {
                height: min(62vh, 620px);
            }

            .history-item h2 {
                font-size: 1.02rem;
            }
        }
    </style>

    <section class="history-page">
        <section class="history-hero" aria-label="SDA History hero section">
            <h1>Know More About SDA History</h1>
            <p class="lead">A course in Church History highlighting significant details of interest to the youth of the Seventh-day Adventist Church.</p>
            <p class="history-meta">Available documents: {{ count($documents) }}</p>
        </section>

        @if(!empty($activeDocument))
            <section class="history-viewer">
            <div class="history-viewer-head">
                <h2 class="history-viewer-title">Now Reading: {{ $activeDocument['title'] }}</h2>
                <a class="history-btn" href="{{ route('sda-history') }}">Close Reader</a>
            </div>
            <iframe class="history-viewer-frame" src="{{ $activeDocument['viewUrl'] }}" title="{{ $activeDocument['title'] }}"></iframe>
            </section>
        @endif

        @if(!empty($documents))
            <section class="history-grid">
            @foreach($documents as $document)
                <article class="history-item">
                    <div class="history-item-head">
                        <span class="history-badge">{{ $loop->iteration }}</span>
                    </div>

                    <h2>{{ $document['title'] }}</h2>
                    <p class="history-summary">{{ $document['summary'] }}</p>
                    <div class="history-actions">
                        <a class="history-btn" href="{{ route('sda-history', ['read' => $document['file']]) }}">Read</a>
                        <a class="history-btn primary" href="{{ $document['downloadUrl'] }}" download>Download</a>
                    </div>
                </article>
            @endforeach
            </section>
        @else
            <section class="card history-empty">
                <h2>No history documents available</h2>
                <p class="meta">Upload history documents into the public folder and they will appear here.</p>
            </section>
        @endif
    </section>
@endsection

