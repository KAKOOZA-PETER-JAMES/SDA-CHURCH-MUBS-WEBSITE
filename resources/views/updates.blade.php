@extends('layouts.site')

@section('title', 'SDA CHURCH MUBS | Updates')

@section('content')
    @php
        $adminContent = \App\Support\AdminContentStore::get();
        $updates = collect($adminContent['updates'] ?? []);
        $orderedUpdates = $updates->values();

        $bubbleLabel = function (array $update, int $position): string {
            $dateRange = trim((string) ($update['date_range'] ?? ''));
            $month = trim((string) ($update['month'] ?? ''));

            if ($dateRange !== '') {
                return mb_strtoupper($dateRange);
            }

            if ($month !== '') {
                return mb_strtoupper($month);
            }

            return 'UPDATE ' . str_pad((string) ($position + 1), 2, '0', STR_PAD_LEFT);
        };
    @endphp

    <style>
        .updates-page {
            display: grid;
            gap: 1rem;
        }

        .updates-hero {
            position: relative;
            border-radius: 18px;
            overflow: hidden;
            min-height: 210px;
            border: 1px solid #d7e0ed;
            box-shadow: 0 16px 34px rgba(17, 35, 61, 0.16);
            background: linear-gradient(130deg, #102a52 0%, #1f446f 55%, #3e5f84 100%);
        }

        .updates-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(130deg, rgba(11, 31, 64, 0.93) 0%, rgba(13, 54, 87, 0.78) 45%, rgba(16, 42, 82, 0.58) 100%);
            z-index: 1;
        }

        .updates-hero-copy {
            position: relative;
            z-index: 2;
            padding: 1.5rem 1.2rem;
            color: #ffffff;
            max-width: 720px;
        }

        .updates-hero-kicker {
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-size: 0.82rem;
            font-weight: 800;
            color: #8ce4f0;
        }

        .updates-page h1 {
            margin: 0 0 0.45rem;
            font-size: clamp(2rem, 4.8vw, 3rem);
            line-height: 1.08;
            color: #ffffff;
        }

        .updates-page .updates-subtitle {
            margin: 0;
            font-size: clamp(1rem, 2.1vw, 1.35rem);
            color: #d8e8ff;
            font-weight: 700;
        }

        .updates-flow {
            position: relative;
            background: linear-gradient(165deg, #eef4ff 0%, #f9fbff 48%, #f2f7ff 100%);
            border: 1px solid #d8e2f2;
            border-radius: 18px;
            padding: 1rem;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.9), 0 10px 26px rgba(18, 36, 62, 0.1);
        }

        .updates-flow::before {
            content: '';
            position: absolute;
            top: 1rem;
            bottom: 1rem;
            left: 50%;
            width: 2px;
            transform: translateX(-50%);
            background: linear-gradient(180deg, #c6d7f0 0%, #9fbde8 40%, #c6d7f0 100%);
            opacity: 0.65;
        }

        .update-row {
            position: relative;
            display: grid;
            grid-template-columns: 1fr 84px;
            align-items: center;
            gap: 0.9rem;
            margin-bottom: 0.85rem;
        }

        .update-row:last-child {
            margin-bottom: 0;
        }

        .update-row.is-right {
            grid-template-columns: 84px 1fr;
        }

        .update-bubble {
            width: 84px;
            min-height: 84px;
            border-radius: 999px;
            border: 3px solid #3a8daa;
            background: #ffffff;
            color: #2f6f8f;
            font-weight: 800;
            font-size: 0.82rem;
            line-height: 1.15;
            letter-spacing: 0.02em;
            display: grid;
            place-items: center;
            text-align: center;
            padding: 0.65rem 0.5rem;
            box-shadow: 0 8px 16px rgba(26, 67, 98, 0.14);
            z-index: 2;
        }

        .update-body {
            background: #ffffff;
            border: 1px solid #d8e4f4;
            border-radius: 14px;
            padding: 0.85rem 0.95rem;
            box-shadow: 0 8px 18px rgba(18, 36, 62, 0.08);
            position: relative;
            z-index: 1;
        }

        .update-row.is-left .update-body::after,
        .update-row.is-right .update-body::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 16px;
            height: 16px;
            background: #ffffff;
            border-top: 1px solid #d8e4f4;
            border-right: 1px solid #d8e4f4;
            transform: translateY(-50%) rotate(45deg);
        }

        .update-row.is-left .update-body::after {
            right: -9px;
        }

        .update-row.is-right .update-body::after {
            left: -9px;
            transform: translateY(-50%) rotate(225deg);
        }

        .update-meta {
            color: #1b7e8a;
            font-size: 0.9rem;
            font-weight: 700;
            margin-bottom: 0.2rem;
        }

        .update-title {
            margin: 0;
            color: #223956;
            font-size: 1.1rem;
            line-height: 1.3;
        }

        .update-actions {
            margin-top: 0.5rem;
        }

        .update-action {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            color: #1f8d9c;
            text-decoration: none;
            font-weight: 700;
            white-space: nowrap;
            border: 1px solid #b8deea;
            background: #effbff;
            padding: 0.4rem 0.65rem;
            border-radius: 999px;
            cursor: pointer;
        }

        .update-action:hover,
        .update-action:focus-visible {
            background: #daf3fb;
            border-color: #9ccfdf;
            outline: none;
        }

        .update-action-icon {
            width: auto;
            height: auto;
            border: 0;
            border-radius: 0;
            display: inline-block;
            font-size: 0.86rem;
            line-height: 1;
            transition: transform 0.2s ease;
            background: transparent;
        }

        .update-action[aria-expanded='true'] .update-action-icon {
            transform: rotate(180deg);
        }

        .update-details {
            margin-top: 0.55rem;
            color: #334965;
            font-size: 0.98rem;
            line-height: 1.55;
        }

        .updates-empty {
            margin: 0;
            padding: 1rem;
            color: #66758d;
            font-size: 0.98rem;
            background: #ffffff;
            border: 1px dashed #bfd0ea;
            border-radius: 10px;
        }

        @media (max-width: 900px) {
            .updates-flow::before {
                display: none;
            }

            .update-row {
                grid-template-columns: 74px 1fr;
            }

            .update-row.is-right {
                grid-template-columns: 74px 1fr;
            }

            .update-row.is-left .update-bubble,
            .update-row.is-right .update-bubble {
                grid-column: 1;
            }

            .update-row.is-left .update-body,
            .update-row.is-right .update-body {
                grid-column: 2;
            }

            .update-bubble {
                width: 74px;
                min-height: 74px;
                font-size: 0.74rem;
            }

            .update-row.is-left .update-body::after,
            .update-row.is-right .update-body::after {
                left: -9px;
                right: auto;
                transform: translateY(-50%) rotate(225deg);
            }
        }

        @media (max-width: 640px) {
            .updates-page {
                gap: 0.75rem;
            }

            .updates-hero {
                min-height: 148px;
                border-radius: 12px;
            }

            .updates-hero-copy {
                padding: 0.9rem 0.85rem;
            }

            .updates-hero-kicker {
                font-size: 0.7rem;
            }

            .updates-page h1 {
                font-size: clamp(1.35rem, 7vw, 1.8rem);
                margin-bottom: 0.25rem;
            }

            .updates-page .updates-subtitle {
                font-size: 0.88rem;
                line-height: 1.4;
                font-weight: 600;
            }

            .updates-flow {
                border-radius: 12px;
                padding: 0.7rem;
            }

            .update-row,
            .update-row.is-right {
                grid-template-columns: 1fr;
                gap: 0.45rem;
                align-items: stretch;
                margin-bottom: 0.75rem;
            }

            .update-bubble {
                width: auto;
                min-height: 0;
                border-width: 1px;
                border-radius: 999px;
                font-size: 0.72rem;
                line-height: 1.2;
                padding: 0.33rem 0.62rem;
                justify-self: start;
                background: #f1fbff;
                color: #236d86;
                box-shadow: 0 4px 10px rgba(26, 67, 98, 0.08);
            }

            .update-body {
                padding: 0.66rem 0.7rem;
                border-radius: 10px;
            }

            .update-row.is-left .update-bubble,
            .update-row.is-right .update-bubble,
            .update-row.is-left .update-body,
            .update-row.is-right .update-body {
                grid-column: 1;
            }

            .update-meta {
                font-size: 0.76rem;
                margin-bottom: 0.16rem;
            }

            .update-title {
                font-size: 0.95rem;
                line-height: 1.35;
            }

            .update-actions {
                margin-top: 0.42rem;
            }

            .update-action {
                width: 100%;
                justify-content: space-between;
                font-size: 0.82rem;
                padding: 0.38rem 0.62rem;
            }

            .update-details {
                margin-top: 0.44rem;
                font-size: 0.86rem;
                line-height: 1.45;
            }

            .update-row.is-left .update-body::after,
            .update-row.is-right .update-body::after {
                display: none;
            }
        }
    </style>

    <section class="updates-page" aria-label="Church updates">
        <header class="updates-hero" aria-label="Updates hero">
            <div class="updates-hero-copy">
                <p class="updates-hero-kicker">SDA CHURCH MUBS</p>
                <h1>Updates</h1>
                <p class="updates-subtitle">Stay informed as a sober Christian. Dont Miss Any Information We Are Here To Inform You.</p>
            </div>
        </header>

        <section class="updates-flow" aria-label="Scheduled updates timeline">
            @forelse($orderedUpdates as $update)
                @php
                    $detailId = 'update-details-' . $loop->index;
                    $isRight = $loop->index % 2 === 1;
                @endphp
                <article class="update-row {{ $isRight ? 'is-right' : 'is-left' }}">
                    @if(!$isRight)
                        <section class="update-body">
                            <div class="update-meta">{{ $update['department'] ?? 'Department' }} · {{ $update['month'] ?? 'Month TBA' }}</div>
                            <h3 class="update-title">{{ $update['title'] ?? 'Church Update' }}</h3>
                            <div class="update-actions">
                                <button
                                    type="button"
                                    class="update-action update-info-btn"
                                    aria-label="More info about {{ $update['title'] ?? 'church update' }}"
                                    aria-expanded="false"
                                    aria-controls="{{ $detailId }}"
                                    data-target="{{ $detailId }}"
                                >
                                    <span>Info</span>
                                    <span class="update-action-icon">⌄</span>
                                </button>
                            </div>
                            <p class="update-details" id="{{ $detailId }}" hidden>{{ $update['details'] ?? 'More details will be shared soon.' }}</p>
                        </section>
                        <div class="update-bubble" aria-hidden="true">{{ $bubbleLabel((array) $update, $loop->index) }}</div>
                    @else
                        <div class="update-bubble" aria-hidden="true">{{ $bubbleLabel((array) $update, $loop->index) }}</div>
                        <section class="update-body">
                            <div class="update-meta">{{ $update['department'] ?? 'Department' }} · {{ $update['month'] ?? 'Month TBA' }}</div>
                            <h3 class="update-title">{{ $update['title'] ?? 'Church Update' }}</h3>
                            <div class="update-actions">
                                <button
                                    type="button"
                                    class="update-action update-info-btn"
                                    aria-label="More info about {{ $update['title'] ?? 'church update' }}"
                                    aria-expanded="false"
                                    aria-controls="{{ $detailId }}"
                                    data-target="{{ $detailId }}"
                                >
                                    <span>Info</span>
                                    <span class="update-action-icon">⌄</span>
                                </button>
                            </div>
                            <p class="update-details" id="{{ $detailId }}" hidden>{{ $update['details'] ?? 'More details will be shared soon.' }}</p>
                        </section>
                    @endif
                </article>
            @empty
                <p class="updates-empty">No updates available yet.</p>
            @endforelse
        </section>
    </section>

    <script>
        (function () {
            const infoButtons = Array.from(document.querySelectorAll('.update-info-btn'));

            infoButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    const targetId = button.getAttribute('data-target');
                    const detail = targetId ? document.getElementById(targetId) : null;

                    if (!detail) {
                        return;
                    }

                    const shouldOpen = detail.hasAttribute('hidden');

                    if (shouldOpen) {
                        detail.removeAttribute('hidden');
                    } else {
                        detail.setAttribute('hidden', 'hidden');
                    }

                    button.setAttribute('aria-expanded', shouldOpen ? 'true' : 'false');
                });
            });
        })();
    </script>
@endsection

