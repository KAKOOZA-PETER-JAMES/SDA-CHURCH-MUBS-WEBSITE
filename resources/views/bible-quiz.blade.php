@extends('layouts.site')

@section('title', 'SDA CHURCH MUBS | Bible Quiz')

@section('content')
    @php
        $showCover = $showCover ?? request()->routeIs('bible-quiz');
        $questions = $questions ?? [];
        $books = $books ?? ['Bible Quiz'];
        $currentBook = $currentBook ?? ($books[0] ?? 'Bible Quiz');
        $currentPage = $currentPage ?? 1;
        $totalPages = $totalPages ?? 1;
        $totalBookQuestions = $totalBookQuestions ?? count($questions);
    @endphp

    <style>
        .quiz-wrap {
            max-width: 780px;
            margin: 0 auto;
            border-radius: 14px;
            padding: 0.95rem;
        }

        .quiz-wrap.cover-mode {
            max-width: 100%;
            margin: 0;
            min-height: calc(100vh - 140px);
            display: grid;
            place-items: center;
        }

        .quiz-cover-stage {
            width: min(1180px, 96vw);
            min-height: calc(100vh - 170px);
            display: grid;
            place-items: center;
            perspective: 1800px;
            border-radius: 18px;
            background:
                linear-gradient(175deg, rgba(9, 21, 44, 0.62) 0%, rgba(9, 21, 44, 0.7) 38%, rgba(9, 21, 44, 0.8) 100%),
                url('https://images.unsplash.com/photo-1504052434569-70ad5836ab65?auto=format&fit=crop&w=1600&q=80') center / cover no-repeat,
                radial-gradient(1200px 460px at 25% -10%, rgba(193, 148, 52, 0.22), transparent 70%),
                radial-gradient(900px 420px at 80% 110%, rgba(63, 116, 190, 0.2), transparent 70%),
                linear-gradient(160deg, #081a35 0%, #0f2b55 40%, #1b3f74 100%);
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.08), 0 24px 44px rgba(5, 11, 21, 0.36);
            overflow-x: hidden;
            overflow-y: auto;
            padding: 1rem;
        }

        .quiz-book-shell {
            position: relative;
            width: min(880px, 88vw);
            height: min(680px, 82vh);
            transform-style: preserve-3d;
            transition: transform 0.75s cubic-bezier(0.2, 0.8, 0.2, 1);
        }

        .quiz-book-spine {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 30px;
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
            background: linear-gradient(180deg, #c19434 0%, #9a6f1f 100%);
            box-shadow: inset -3px 0 8px rgba(0, 0, 0, 0.26);
            transform: translateZ(1px);
        }

        .quiz-book-pages {
            position: absolute;
            right: 0;
            top: 14px;
            bottom: 14px;
            width: calc(100% - 30px);
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
            background:#3e8391;
             
            box-shadow: inset 0 0 0 1px rgba(124, 101, 58, 0.24), inset 0 -8px 14px rgba(0, 0, 0, 0.06);
            transform: translateZ(-4px);
        }

        .quiz-book-front {
            position: absolute;
            left: 30px;
            top: 0;
            width: calc(100% - 30px);
            height: 100%;
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
            padding: clamp(1.2rem, 3vw, 2rem);
            color: #ffffff;
            background:
                linear-gradient(145deg, #0d284f 0%, #1f4a8a 55%, #3f6ca8 100%);
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.16), 0 16px 28px rgba(7, 19, 37, 0.34);
            transform-origin: left center;
            transform-style: preserve-3d;
            transition: transform 0.72s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow 0.72s ease;
            overflow-x: hidden;
            overflow-y: auto;
        }

        .quiz-book-front::before,
        .quiz-book-front::after {
            content: '';
            position: absolute;
            border-radius: 999px;
            pointer-events: none;
            opacity: 0.22;
        }

        .quiz-book-front::before {
            width: 260px;
            height: 260px;
            top: -120px;
            right: -90px;
            background: #c19434;
        }

        .quiz-book-front::after {
            width: 340px;
            height: 340px;
            left: -180px;
            bottom: -190px;
            background: #dce8ff;
        }

        .quiz-cover-content {
            position: relative;
            z-index: 2;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            text-align: center;
            max-height: 100%;
            overflow-y: auto;
            overflow-x: hidden;
            padding-right: 0.25rem;
            padding-top: clamp(0.55rem, 2vh, 1.2rem);
            padding-bottom: 0.8rem;
        }

        .quiz-cover-logo {
            width: 80px;
            height: 80px;
            margin-bottom: 1.2rem;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid rgba(255, 255, 255, 0.25);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
            flex-shrink: 0;
            overflow: hidden;
        }

        .quiz-cover-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 8px;
            display: block;
            filter: brightness(0) saturate(100%) invert(1);
        }

        .quiz-cover-badge {
            display: inline-block;
            background: rgba(193, 148, 52, 0.2);
            border: 1px solid rgba(193, 148, 52, 0.5);
            color: #c19434;
            padding: 0.5rem 1rem;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.8px;
            margin-bottom: 0.8rem;
        }

        .quiz-cover-title {
            margin: 0.6rem 0 0.4rem;
            font-size: clamp(2rem, 5vw, 3.2rem);
            letter-spacing: 0.7px;
        }

        .quiz-cover-sub {
            margin: 0 auto 1.15rem;
            max-width: 640px;
            color: #e8eef8;
            font-size: clamp(0.98rem, 2vw, 1.1rem);
        }

        .quiz-sample-slider {
            position: relative;
            width: min(980px, 100%);
            min-height: 12.8rem;
            overflow: hidden;
            border-radius: 18px;
            border: 2px solid rgba(193, 148, 52, 0.4);
            background: linear-gradient(135deg, rgba(15, 43, 85, 0.5) 0%, rgba(16, 42, 82, 0.4) 100%);
            margin: 0 auto 1.7rem;
            backdrop-filter: blur(2px);
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.24);
        }

        .quiz-sample-track {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .quiz-sample-item {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.4rem 2rem;
            color: #ffffff;
            font-size: clamp(1.42rem, 2.7vw, 1.95rem);
            font-weight: 700;
            line-height: 1.55;
            text-align: center;
            opacity: 0;
            transform: translateY(18px) scale(0.92);
            transition: opacity 0.48s ease, transform 0.48s ease;
            pointer-events: none;
        }

        .quiz-sample-item.is-active {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        .quiz-sample-item.is-exiting {
            opacity: 0;
            transform: translateY(-18px) scale(0.96);
        }

        @media (max-width: 760px) {
            .quiz-sample-slider {
                width: min(96vw, 100%);
                min-height: 10.2rem;
            }

            .quiz-sample-item {
                font-size: 1.2rem;
                padding: 1rem 1.15rem;
            }
        }

        .quiz-cover-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            text-decoration: none;
            border: 1px solid #c19434;
            background: #c19434;
            color: #0f2b55;
            border-radius: 999px;
            padding: 0.68rem 1.2rem;
            font-weight: 800;
            box-shadow: 0 8px 16px rgba(5, 11, 21, 0.24);
            transition: transform 0.2s ease, background 0.2s ease;
        }

        .quiz-cover-button:hover {
            background: #d1a34a;
            transform: translateY(-2px);
        }

        .quiz-book-shell.opening {
            transform: translateX(18px);
        }

        .quiz-book-shell.opening .quiz-book-front {
            transform: rotateY(-126deg) translateX(-8px);
            box-shadow: 0 10px 20px rgba(7, 19, 37, 0.16);
        }

        .quiz-book-shell.opening .quiz-book-pages {
            transform: translateZ(-4px) translateX(10px);
            transition: transform 0.72s ease;
        }

        .quiz-cover-note {
            margin: 0.9rem 0 0;
            color: #dce8ff;
            font-size: 0.88rem;
        }

        .quiz-cover-controls {
            margin-top: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
        }

        .quiz-sound-toggle {
            border: 1px solid rgba(255, 255, 255, 0.4);
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            border-radius: 999px;
            padding: 0.42rem 0.82rem;
            font-weight: 700;
            cursor: pointer;
        }

        .quiz-sound-toggle.enabled {
            background: rgba(193, 148, 52, 0.25);
            border-color: #c19434;
            color: #fff7e2;
        }

        .quiz-card {
            background: #ffffff;
            border: 1px solid #d9e0ec;
            border-radius: 12px;
            padding: 1.2rem;
            box-shadow: 0 8px 20px rgba(12, 38, 74, 0.08);
            transform-origin: left center;
            backface-visibility: hidden;
        }

        .quiz-card.page-flip-next {
            animation: page-flip-next 0.62s ease-in-out forwards;
        }

        .quiz-card.page-flip-prev {
            animation: page-flip-prev 0.62s ease-in-out forwards;
        }

        @keyframes page-flip-next {
            0% {
                transform: perspective(1300px) rotateY(0deg);
                opacity: 1;
            }

            70% {
                transform: perspective(1300px) rotateY(-28deg);
                opacity: 0.45;
            }

            100% {
                transform: perspective(1300px) rotateY(-64deg);
                opacity: 0;
            }
        }

        @keyframes page-flip-prev {
            0% {
                transform: perspective(1300px) rotateY(0deg);
                opacity: 1;
            }

            70% {
                transform: perspective(1300px) rotateY(28deg);
                opacity: 0.45;
            }

            100% {
                transform: perspective(1300px) rotateY(64deg);
                opacity: 0;
            }
        }

        .quiz-title {
            margin: 0;
            color: #1f4a8a;
        }

        .quiz-sub {
            margin: 0.35rem 0 1rem;
            color: var(--text-soft);
        }

        .quiz-alert,
        .quiz-success {
            border-radius: 8px;
            padding: 0.65rem 0.75rem;
            margin-bottom: 0.9rem;
            font-size: 0.92rem;
        }

        .quiz-alert {
            background: #fff4f4;
            border: 1px solid #f1c7c7;
            color: #9d2b2b;
        }

        .quiz-success {
            margin-bottom: 1rem;
            border-radius: 12px;
            border: 1px solid #b8e0c0;
            background: linear-gradient(135deg, #ecfff2 0%, #f9fffc 56%, #ffffff 100%);
            color: #1f6b35;
            box-shadow: 0 10px 24px rgba(31, 107, 53, 0.12);
            display: grid;
            grid-template-columns: 40px 1fr;
            gap: 0.65rem;
            align-items: start;
        }

        .quiz-success-icon {
            width: 40px;
            height: 40px;
            border-radius: 999px;
            background: #d7f4e0;
            color: #0f6a2a;
            display: grid;
            place-items: center;
            font-size: 1.05rem;
            font-weight: 800;
            line-height: 1;
        }

        .quiz-success-title {
            margin: 0;
            color: #14542c;
            font-size: 1rem;
            font-weight: 800;
        }

        .quiz-success-note {
            margin: 0.2rem 0 0;
            color: #2f5d3e;
            font-size: 0.9rem;
            line-height: 1.45;
        }

        .quiz-result {
            margin-bottom: 0.95rem;
            border-radius: 10px;
            padding: 0.75rem 0.85rem;
            border: 1px solid #d6e4f8;
            background: #f4f8ff;
            color: #1f4a8a;
            font-weight: 700;
        }

        .quiz-result.tone-low {
            border-color: #f1c7c7;
            background: #fff4f4;
            color: #9d2b2b;
        }

        .quiz-result.tone-mid {
            border-color: #f0d9a5;
            background: #fffaf0;
            color: #8a6117;
        }

        .quiz-result.tone-high {
            border-color: #cfe2ff;
            background: #eff5ff;
            color: #1f4a8a;
        }

        .quiz-result.tone-excellent {
            border-color: #8ed6a3;
            background: linear-gradient(135deg, #ebfff1 0%, #f6fffa 55%, #ffffff 100%);
            color: #0f6a2a;
        }

        .quiz-perfect {
            margin: 0 0 0.95rem;
            border-radius: 12px;
            padding: 0.85rem 0.95rem;
            border: 1px solid #8ed6a3;
            background: linear-gradient(135deg, #ebfff1 0%, #f6fffa 55%, #ffffff 100%);
            color: #0f6a2a;
            font-weight: 800;
            box-shadow: 0 8px 22px rgba(31, 107, 53, 0.14);
        }

        .confetti-area {
            position: fixed;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
            z-index: 999;
        }

        .confetti-piece {
            position: absolute;
            top: -14px;
            width: 10px;
            height: 16px;
            border-radius: 2px;
            opacity: 0.95;
            animation: confetti-fall linear forwards;
            will-change: transform, opacity;
        }

        @keyframes confetti-fall {
            0% {
                transform: translate3d(0, 0, 0) rotate(0deg);
                opacity: 1;
            }

            100% {
                transform: translate3d(var(--drift, 0px), 110vh, 0) rotate(var(--spin, 520deg));
                opacity: 0.08;
            }
        }

        .quiz-progress-wrap {
            margin: 0 0 1rem;
            border: 1px solid #dbe4f2;
            border-radius: 10px;
            padding: 0.85rem;
            background: #f8fbff;
        }

        .quiz-progress-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.7rem;
            color: #1f4a8a;
            font-weight: 700;
            font-size: 0.92rem;
            margin-bottom: 0.55rem;
        }

        .quiz-progress-track {
            width: 100%;
            height: 10px;
            border-radius: 999px;
            background: #dbe6f7;
            overflow: hidden;
        }

        .quiz-progress-fill {
            height: 100%;
            width: 0;
            background: linear-gradient(90deg, #1f4a8a, #3f74be);
            transition: width 0.2s ease, background 0.2s ease;
        }

        .quiz-progress-fill.level-0,
        .quiz-progress-fill.level-low {
            background: linear-gradient(90deg, #c24a4a, #de7a7a);
        }

        .quiz-progress-fill.level-mid {
            background: linear-gradient(90deg, #c19434, #e0b65c);
        }

        .quiz-progress-fill.level-high {
            background: linear-gradient(90deg, #2f7db5, #58a0d3);
        }

        .quiz-progress-fill.level-full {
            background: linear-gradient(90deg, #1f6b35, #39a558);
        }

        .quiz-card,
        .quiz-question,
        .quiz-option span {
            -webkit-user-select: none;
            user-select: none;
            -webkit-touch-callout: none;
        }

        .quiz-card.quiz-guard-blur {
            filter: blur(8px);
            transition: filter 0.2s ease;
        }

        .quiz-nav-wrap {
            display: grid;
            gap: 0.6rem;
            margin-bottom: 1rem;
            padding: 0.8rem;
            border: 1px solid #dbe4f2;
            border-radius: 10px;
            background: #f8fbff;
        }

        .quiz-nav-row {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
            align-items: center;
        }

        .quiz-label {
            font-size: 0.9rem;
            font-weight: 700;
            color: #1f4a8a;
        }

        .quiz-select {
            min-width: 250px;
            border: 1px solid #cfd8e7;
            border-radius: 8px;
            padding: 0.45rem 0.6rem;
            color: #1a1a1a;
            background: #ffffff;
        }

        .quiz-page-meta {
            color: #1f4a8a;
            font-weight: 700;
            font-size: 0.92rem;
        }

        .quiz-page-links {
            display: inline-flex;
            gap: 0.45rem;
        }

        .quiz-page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            border: 1px solid #1f4a8a;
            color: #1f4a8a;
            background: #ffffff;
            border-radius: 999px;
            padding: 0.35rem 0.85rem;
            font-weight: 700;
            font-size: 0.88rem;
        }

        .quiz-page-link:hover {
            background: #f2f7ff;
        }

        .quiz-nav-link.is-disabled,
        .quiz-nav-link.is-disabled:hover {
            opacity: 0.45;
            pointer-events: none;
            cursor: not-allowed;
            background: #eef2f8;
            color: #6d7f9b;
            border-color: #c9d5e7;
        }

        .quiz-pagination {
            display: inline-flex;
            align-items: center;
            border: 1px solid #cfd8e7;
            background: #f7f9fc;
            overflow: hidden;
        }

        .quiz-pagination-item {
            min-width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #1f4a8a;
            background: #ffffff;
            border-right: 1px solid #cfd8e7;
            font-weight: 700;
            font-size: 0.88rem;
            padding: 0 0.4rem;
            transition: background 0.2s ease;
        }

        .quiz-pagination-item:last-child {
            border-right: 0;
        }

        .quiz-pagination-item:hover {
            background: #eef4ff;
        }

        .quiz-pagination-item.active {
            background: #dfe8fb;
            color: #153665;
            pointer-events: none;
        }

        .quiz-pagination-item.reviewed {
            position: relative;
            padding-right: 0.9rem;
            background: #e9f8ee;
            color: #1f6b35;
        }

        .quiz-pagination-item.reviewed:hover {
            background: #dcf2e3;
        }

        .quiz-pagination-item.reviewed.active {
            background: #cfead9;
            color: #154c28;
        }

        .quiz-pagination-item.reviewed::after {
            content: '✓';
            position: absolute;
            right: 0.25rem;
            top: 0.1rem;
            font-size: 0.68rem;
            color: #1f6b35;
            font-weight: 900;
        }

        .quiz-pagination-item.ellipsis {
            background: #f5f7fb;
            color: var(--text-soft);
            pointer-events: none;
        }

        .quiz-review-note {
            margin-left: auto;
            font-size: 0.82rem;
            color: #1f6b35;
            font-weight: 700;
        }

        .quiz-submit-note {
            margin-top: 1rem;
            color: var(--text-soft);
            font-weight: 700;
            font-size: 0.9rem;
            text-align: right;
        }

        .quiz-group {
            border: 1px solid #dbe4f2;
            border-radius: 10px;
            padding: 0.9rem;
            margin-bottom: 0.8rem;
            background: #f9fbff;
        }

        .quiz-question {
            margin: 0 0 0.55rem;
            color: #1f4a8a;
            font-weight: 700;
        }

        .quiz-reference-link {
            color: #0f2b55;
            text-decoration: underline;
            text-underline-offset: 2px;
            font-weight: 800;
        }

        .quiz-reference-link:hover {
            color: #153665;
        }


        .quiz-option {
            display: block;
            margin-bottom: 0.35rem;
            color: #1a1a1a;
        }

        .quiz-option input {
            margin-right: 0.45rem;
        }

        .quiz-change-note {
            margin: 0.5rem 0 0;
            font-size: 0.82rem;
            color: var(--text-soft);
        }

        .quiz-change-note.limit {
            color: #9d2b2b;
            font-weight: 700;
        }

        .quiz-review {
            margin-top: 1rem;
            border: 1px solid #f0d9a5;
            border-radius: 12px;
            padding: 0.95rem;
            background: linear-gradient(180deg, #fffaf0 0%, #fffdf7 100%);
        }

        .quiz-review h2 {
            margin: 0 0 0.65rem;
            color: #8a6117;
        }

        .quiz-review-card {
            border: 1px solid #ecd39b;
            border-radius: 10px;
            padding: 0.75rem;
            background: #ffffff;
            margin-bottom: 0.6rem;
        }

        .quiz-review-card p {
            margin: 0.2rem 0;
            color: #3b2b0b;
        }

        .quiz-review-label {
            font-weight: 700;
            color: #8a6117;
        }

        .quiz-action {
            margin-top: 1rem;
            display: flex;
            justify-content: flex-end;
        }

        .quiz-btn {
            border: 1px solid #1f4a8a;
            background: #1f4a8a;
            color: #ffffff;
            border-radius: 999px;
            padding: 0.58rem 1rem;
            font-weight: 700;
            cursor: pointer;
        }

        .quiz-btn:hover {
            background: #153665;
            border-color: #153665;
        }

        .quiz-secondary-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            border: 1px solid #1f4a8a;
            color: #1f4a8a;
            background: #ffffff;
            border-radius: 999px;
            padding: 0.58rem 1rem;
            font-weight: 700;
        }

        .quiz-secondary-btn:hover {
            background: #f2f7ff;
        }

        @media (max-width: 900px) {
            .quiz-wrap {
                padding: 0.6rem;
            }

            .quiz-wrap.cover-mode {
                padding: 0.3rem;
                min-height: auto;
            }

            .quiz-cover-stage {
                width: 100%;
                min-height: clamp(460px, 75vh, 620px);
                padding: 0.7rem;
                border-radius: 14px;
            }

            .quiz-book-shell {
                width: min(100%, 700px);
                height: min(640px, 86vh);
            }

            .quiz-cover-content {
                justify-content: flex-start;
                padding-top: 0.35rem;
                padding-bottom: 0.65rem;
            }

            .quiz-sample-slider {
                min-height: 9.6rem;
                margin-bottom: 1.1rem;
            }

            .quiz-card {
                padding: 0.9rem;
            }

            .quiz-nav-row {
                display: grid;
                grid-template-columns: 1fr;
                align-items: stretch;
            }

            .quiz-select {
                min-width: 0;
                width: 100%;
            }

            .quiz-pagination {
                max-width: 100%;
                overflow-x: auto;
                white-space: nowrap;
            }

            .quiz-action {
                gap: 0.55rem;
                flex-wrap: wrap;
            }
        }

        @media (max-width: 640px) {
            .quiz-wrap {
                padding: 0.4rem;
            }

            .quiz-wrap.cover-mode {
                padding: 0;
            }

            .quiz-cover-stage {
                min-height: clamp(470px, 78vh, 640px);
                padding: 0.5rem;
                border-radius: 12px;
            }

            .quiz-card,
            .quiz-group,
            .quiz-nav-wrap,
            .quiz-progress-wrap,
            .quiz-review,
            .quiz-review-card {
                padding: 0.75rem;
            }

            .quiz-cover-title {
                font-size: clamp(1.55rem, 7vw, 2rem);
            }

            .quiz-cover-sub {
                font-size: 0.93rem;
                margin-bottom: 0.85rem;
            }

            .quiz-book-shell {
                width: min(100%, 96vw);
                height: min(620px, 88vh);
            }

            .quiz-book-spine {
                width: 22px;
            }

            .quiz-book-front {
                left: 22px;
                width: calc(100% - 22px);
            }

            .quiz-book-pages {
                width: calc(100% - 22px);
            }

            .quiz-sample-slider {
                min-height: 8.4rem;
                border-radius: 14px;
                margin-bottom: 0.95rem;
            }

            .quiz-sample-item {
                font-size: 1.02rem;
                line-height: 1.45;
                padding: 0.85rem 0.8rem;
            }

            .quiz-cover-button {
                width: 100%;
                max-width: 320px;
            }

            .quiz-cover-logo {
                width: 70px;
                height: 70px;
                margin-bottom: 0.95rem;
            }

            .quiz-cover-logo img {
                padding: 7px;
            }

            .quiz-page-meta,
            .quiz-review-note,
            .quiz-submit-note {
                font-size: 0.84rem;
            }

            .quiz-option {
                word-break: break-word;
            }

            .quiz-action {
                justify-content: stretch;
            }

            .quiz-page-link,
            .quiz-btn,
            .quiz-secondary-btn {
                width: 100%;
                justify-content: center;
            }

            /* Disable AI tools during quiz */
            body.bible-quiz .sda-right-bar {
                display: none !important;
            }
        }
    </style>

    @php
        $referenceCandidatePattern = '/((?:[1-3]\s*)?[A-Za-z]+(?:\s+(?:of|[A-Za-z]+)){0,3}\.?\s*\d{1,3}(?::\d{1,3}(?:-\d{1,3})?)?)/iu';

        $bookAliasLookup = [
            'genesis' => 'Genesis', 'gen' => 'Genesis', 'ge' => 'Genesis', 'gn' => 'Genesis',
            'exodus' => 'Exodus', 'exo' => 'Exodus', 'ex' => 'Exodus',
            'leviticus' => 'Leviticus', 'lev' => 'Leviticus', 'le' => 'Leviticus', 'lv' => 'Leviticus',
            'numbers' => 'Numbers', 'num' => 'Numbers', 'nu' => 'Numbers', 'nm' => 'Numbers', 'nb' => 'Numbers',
            'deuteronomy' => 'Deuteronomy', 'deut' => 'Deuteronomy', 'dt' => 'Deuteronomy',
            'joshua' => 'Joshua', 'josh' => 'Joshua', 'jos' => 'Joshua',
            'judges' => 'Judges', 'judg' => 'Judges', 'jdg' => 'Judges', 'jg' => 'Judges',
            'ruth' => 'Ruth', 'rth' => 'Ruth',
            '1samuel' => '1 Samuel', '1sam' => '1 Samuel', '1sa' => '1 Samuel',
            '2samuel' => '2 Samuel', '2sam' => '2 Samuel', '2sa' => '2 Samuel',
            '1kings' => '1 Kings', '1kgs' => '1 Kings', '1ki' => '1 Kings',
            '2kings' => '2 Kings', '2kgs' => '2 Kings', '2ki' => '2 Kings',
            '1chronicles' => '1 Chronicles', '1chron' => '1 Chronicles', '1chr' => '1 Chronicles',
            '2chronicles' => '2 Chronicles', '2chron' => '2 Chronicles', '2chr' => '2 Chronicles',
            'ezra' => 'Ezra', 'ezr' => 'Ezra',
            'nehemiah' => 'Nehemiah', 'neh' => 'Nehemiah',
            'esther' => 'Esther', 'est' => 'Esther',
            'job' => 'Job',
            'psalms' => 'Psalms', 'psalm' => 'Psalms', 'ps' => 'Psalms', 'psa' => 'Psalms', 'psm' => 'Psalms',
            'proverbs' => 'Proverbs', 'prov' => 'Proverbs', 'prv' => 'Proverbs', 'pr' => 'Proverbs',
            'ecclesiastes' => 'Ecclesiastes', 'eccles' => 'Ecclesiastes', 'ecc' => 'Ecclesiastes', 'ec' => 'Ecclesiastes',
            'songofsolomon' => 'Song of Solomon', 'songofsongs' => 'Song of Solomon', 'song' => 'Song of Solomon', 'sos' => 'Song of Solomon',
            'isaiah' => 'Isaiah', 'isa' => 'Isaiah', 'is' => 'Isaiah',
            'jeremiah' => 'Jeremiah', 'jer' => 'Jeremiah', 'je' => 'Jeremiah',
            'lamentations' => 'Lamentations', 'lam' => 'Lamentations',
            'ezekiel' => 'Ezekiel', 'ezek' => 'Ezekiel', 'eze' => 'Ezekiel', 'ezk' => 'Ezekiel',
            'daniel' => 'Daniel', 'dan' => 'Daniel', 'da' => 'Daniel',
            'hosea' => 'Hosea', 'hos' => 'Hosea',
            'joel' => 'Joel', 'jl' => 'Joel',
            'amos' => 'Amos', 'am' => 'Amos',
            'obadiah' => 'Obadiah', 'obad' => 'Obadiah', 'ob' => 'Obadiah',
            'jonah' => 'Jonah', 'jon' => 'Jonah',
            'micah' => 'Micah', 'mic' => 'Micah',
            'nahum' => 'Nahum', 'nah' => 'Nahum',
            'habakkuk' => 'Habakkuk', 'hab' => 'Habakkuk',
            'zephaniah' => 'Zephaniah', 'zeph' => 'Zephaniah', 'zep' => 'Zephaniah',
            'haggai' => 'Haggai', 'hag' => 'Haggai',
            'zechariah' => 'Zechariah', 'zech' => 'Zechariah', 'zec' => 'Zechariah',
            'malachi' => 'Malachi', 'mal' => 'Malachi',
            'matthew' => 'Matthew', 'matt' => 'Matthew', 'mat' => 'Matthew', 'mt' => 'Matthew',
            'mark' => 'Mark', 'mrk' => 'Mark', 'mk' => 'Mark',
            'luke' => 'Luke', 'luk' => 'Luke', 'lk' => 'Luke',
            'john' => 'John', 'jhn' => 'John', 'jn' => 'John',
            'acts' => 'Acts', 'act' => 'Acts',
            'romans' => 'Romans', 'rom' => 'Romans', 'ro' => 'Romans',
            '1corinthians' => '1 Corinthians', '1cor' => '1 Corinthians', '1co' => '1 Corinthians',
            '2corinthians' => '2 Corinthians', '2cor' => '2 Corinthians', '2co' => '2 Corinthians',
            'galatians' => 'Galatians', 'gal' => 'Galatians',
            'ephesians' => 'Ephesians', 'eph' => 'Ephesians',
            'philippians' => 'Philippians', 'phil' => 'Philippians', 'php' => 'Philippians',
            'colossians' => 'Colossians', 'col' => 'Colossians',
            '1thessalonians' => '1 Thessalonians', '1thess' => '1 Thessalonians', '1th' => '1 Thessalonians',
            '2thessalonians' => '2 Thessalonians', '2thess' => '2 Thessalonians', '2th' => '2 Thessalonians',
            '1timothy' => '1 Timothy', '1tim' => '1 Timothy', '1ti' => '1 Timothy',
            '2timothy' => '2 Timothy', '2tim' => '2 Timothy', '2ti' => '2 Timothy',
            'titus' => 'Titus', 'tit' => 'Titus',
            'philemon' => 'Philemon', 'philem' => 'Philemon', 'phm' => 'Philemon',
            'hebrews' => 'Hebrews', 'heb' => 'Hebrews',
            'james' => 'James', 'jas' => 'James', 'jm' => 'James',
            '1peter' => '1 Peter', '1pet' => '1 Peter', '1pe' => '1 Peter',
            '2peter' => '2 Peter', '2pet' => '2 Peter', '2pe' => '2 Peter',
            '1john' => '1 John', '1jn' => '1 John', '1jhn' => '1 John',
            '2john' => '2 John', '2jn' => '2 John', '2jhn' => '2 John',
            '3john' => '3 John', '3jn' => '3 John', '3jhn' => '3 John',
            'jude' => 'Jude', 'jud' => 'Jude',
            'revelation' => 'Revelation', 'rev' => 'Revelation', 're' => 'Revelation',
        ];

        $normalizeReference = function ($candidate) use ($bookAliasLookup) {
            $trimmed = trim((string) $candidate, " \t\n\r\0\x0B,.;:!?()[]{}\"'");

            if ($trimmed === '') {
                return null;
            }

            if (!preg_match('/^((?:[1-3]\s*)?[A-Za-z][A-Za-z.\s]*(?:of\s+[A-Za-z]+)?)\s*(\d{1,3}(?::\d{1,3}(?:-\d{1,3})?)?)$/u', $trimmed, $groups)) {
                return null;
            }

            $bookPart = trim((string) ($groups[1] ?? ''));
            $chapterVerse = trim((string) ($groups[2] ?? ''));

            if ($bookPart === '' || $chapterVerse === '') {
                return null;
            }

            $bookKey = strtolower((string) preg_replace('/[^a-z0-9]/i', '', $bookPart));

            if (!isset($bookAliasLookup[$bookKey])) {
                return null;
            }

            return $bookAliasLookup[$bookKey] . ' ' . $chapterVerse;
        };

        $renderPromptWithReferences = function ($text) use ($referenceCandidatePattern, $normalizeReference) {
            $parts = preg_split($referenceCandidatePattern, (string) $text, -1, PREG_SPLIT_DELIM_CAPTURE);

            if (!is_array($parts) || empty($parts)) {
                return e((string) $text);
            }

            $html = '';

            foreach ($parts as $index => $part) {
                if ($part === '') {
                    continue;
                }

                if ($index % 2 === 1) {
                    $normalizedReference = $normalizeReference($part);

                    if ($normalizedReference === null) {
                        $html .= e((string) $part);
                        continue;
                    }

                    $readUrl = 'https://www.biblegateway.com/passage/?search=' . rawurlencode((string) $normalizedReference) . '&version=KJV';
                    $html .= '<a class="quiz-reference-link" href="' . e($readUrl) . '" target="_blank" rel="noopener">' . e((string) $part) . '</a>';
                    continue;
                }

                $html .= e((string) $part);
            }

            return $html;
        };
    @endphp

    @php
        $welcomeName = trim((string) session('welcome_name', session('registered_user_name', '')));
    @endphp

    <section class="quiz-wrap {{ !empty($showCover) ? 'cover-mode' : '' }}">
        @if(session('success'))
            <div class="quiz-success" role="status" aria-live="polite">
                <span class="quiz-success-icon" aria-hidden="true">✓</span>
                <div>
                    <p class="quiz-success-title">Welcome{{ $welcomeName !== '' ? ', ' . $welcomeName : '' }}!</p>
                    <p class="quiz-success-note">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(!empty($showCover))
            <article class="quiz-cover-stage">
                <div id="quizBookShell" class="quiz-book-shell">
                    <div class="quiz-book-spine" aria-hidden="true"></div>
                    <div class="quiz-book-pages" aria-hidden="true"></div>

                    <div class="quiz-book-front">
                        <div class="quiz-cover-content">
                            <div class="quiz-cover-logo">
                                <img src="{{ asset('6.png') }}" alt="SDA CHURCH MUBS Logo">
                            </div>
                            <span class="quiz-cover-badge">SDA CHURCH MUBS</span>
                            <h1 class="quiz-cover-title">Test Your Bible Knowledge</h1>
                            <p class="quiz-cover-sub">Explore questions from Genesis to Revelation and grow deeper in Scripture.</p>
                            <div class="quiz-sample-slider" aria-label="Sample bible quiz questions">
                                <div class="quiz-sample-track">
                                    <p class="quiz-sample-item">Who built the ark according to Genesis?</p>
                                    <p class="quiz-sample-item">Which Psalm says "The Lord is my shepherd"?</p>
                                    <p class="quiz-sample-item">How many days did Jesus fast in the wilderness?</p>
                                    <p class="quiz-sample-item">What is the first commandment?</p>
                                    <p class="quiz-sample-item">Who wrote most of the Psalms?</p>
                                    <p class="quiz-sample-item">How many books are in the Bible?</p>
                                    <p class="quiz-sample-item">Which king built the Temple in Jerusalem?</p>
                                </div>
                            </div>
                            <a id="coverOpenButton" class="quiz-cover-button" href="{{ route('bible-quiz.start') }}">📖 Open to Start Your Attempt</a>
                            <p class="quiz-cover-note">Every page has 10 questions. Complete each page before moving forward.</p>
                        </div>
                    </div>
                </div>
            </article>
        @else
        <article class="quiz-card">
            <h1 class="quiz-title">Bible Quiz</h1>
            <p class="quiz-sub">SDA CHURCH MUBS HELPS YOU TO TEST YOUR BIBLE KNOWLEDGE CONTINUE AND TEST YOURSELF</p>

            @if(!empty($quizError))
                <div class="quiz-alert">{{ $quizError }}</div>
            @endif

            @if(!empty($pageLockError))
                <div class="quiz-alert">{{ $pageLockError }}</div>
            @endif

            @if($errors->any())
                <div class="quiz-alert">Please answer all questions before submitting the quiz.</div>
            @endif

            @if(!empty($submitted))
                @if(!empty($isPerfectScore) && !empty($perfectScoreMessage))
                    <div class="quiz-perfect">{{ $perfectScoreMessage }}</div>
                    <div id="confettiArea" class="confetti-area" aria-hidden="true"></div>
                @endif
                @php
                    $scoreTone = 'tone-low';
                    if (($percentage ?? 0) >= 100) {
                        $scoreTone = 'tone-excellent';
                    } elseif (($percentage ?? 0) >= 75) {
                        $scoreTone = 'tone-high';
                    } elseif (($percentage ?? 0) >= 50) {
                        $scoreTone = 'tone-mid';
                    }
                @endphp
                <div class="quiz-result {{ $scoreTone }}">Your score: {{ $score }} / {{ $total }} ({{ $percentage }}%) | Rank: {{ $rank }}</div>
                <div class="quiz-action" style="margin-top: 0; margin-bottom: 1rem; justify-content: flex-start;">
                    <a class="quiz-secondary-btn" href="{{ route('bible-quiz.start', ['book' => $currentBook, 'page' => $currentPage]) }}">Try Again</a>
                </div>
            @endif

            <div class="quiz-nav-wrap">
                <form method="get" action="{{ route('bible-quiz.start') }}" class="quiz-nav-row">
                    <label class="quiz-label" for="book">Book:</label>
                    <select class="quiz-select" id="book" name="book" onchange="this.form.submit()">
                        @foreach($books as $book)
                            <option value="{{ $book }}" {{ $book === $currentBook ? 'selected' : '' }}>{{ $book }}</option>
                        @endforeach
                    </select>
                </form>

                <div class="quiz-nav-row">
                    <span class="quiz-page-meta">{{ $currentBook }} | Page {{ $currentPage }} of {{ $totalPages }} | 10 questions per page</span>
                    <span class="quiz-review-note">Reviewed pages show ✓</span>
                    <button id="quizRefreshButton" class="quiz-secondary-btn" type="button">Refresh Attempt</button>
                    @php
                        $pageWindow = [];
                        $startPage = max(1, $currentPage - 4);
                        $endPage = min($totalPages, $startPage + 8);
                        $startPage = max(1, $endPage - 8);

                        for ($pageNumber = $startPage; $pageNumber <= $endPage; $pageNumber++) {
                            $pageWindow[] = $pageNumber;
                        }
                    @endphp

                    <nav class="quiz-pagination" aria-label="Quiz page navigation">
                        <a class="quiz-pagination-item quiz-nav-link" data-target-page="1" href="{{ route('bible-quiz.start', ['book' => $currentBook, 'page' => 1]) }}">«</a>
                        <a class="quiz-pagination-item quiz-nav-link" data-target-page="{{ max(1, $currentPage - 1) }}" href="{{ route('bible-quiz.start', ['book' => $currentBook, 'page' => max(1, $currentPage - 1)]) }}">‹</a>

                        @if($startPage > 1)
                            <span class="quiz-pagination-item ellipsis">...</span>
                        @endif

                        @foreach($pageWindow as $pageNumber)
                            <a
                                class="quiz-pagination-item quiz-nav-link {{ $pageNumber === $currentPage ? 'active' : '' }}"
                                data-target-page="{{ $pageNumber }}"
                                href="{{ route('bible-quiz.start', ['book' => $currentBook, 'page' => $pageNumber]) }}"
                            >{{ $pageNumber }}</a>
                        @endforeach

                        @if($endPage < $totalPages)
                            <span class="quiz-pagination-item ellipsis">...</span>
                        @endif

                        <a class="quiz-pagination-item quiz-nav-link" data-target-page="{{ min($totalPages, $currentPage + 1) }}" href="{{ route('bible-quiz.start', ['book' => $currentBook, 'page' => min($totalPages, $currentPage + 1)]) }}">›</a>
                        <a class="quiz-pagination-item quiz-nav-link" data-target-page="{{ $totalPages }}" href="{{ route('bible-quiz.start', ['book' => $currentBook, 'page' => $totalPages]) }}">»</a>
                    </nav>
                </div>
            </div>

            <div class="quiz-progress-wrap">
                <div class="quiz-progress-meta">
                    <span>Answered: <strong id="answeredCount">0</strong> / {{ $totalBookQuestions ?? count($questions) }}</span>
                    <span>Unanswered: <strong id="unansweredCount">{{ $totalBookQuestions ?? count($questions) }}</strong></span>
                    @if(!empty($submitted))
                        <span>Failed: <strong>{{ $failedCount }}</strong></span>
                    @endif
                </div>
                <div class="quiz-progress-track" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                    <div id="quizProgressFill" class="quiz-progress-fill"></div>
                </div>
            </div>

            <form action="{{ route('bible-quiz.submit') }}" method="post" novalidate>
                @csrf
                <input type="hidden" name="book" value="{{ $currentBook }}">
                <input type="hidden" name="page" value="{{ $currentPage }}">
                <input type="hidden" name="all_answers_json" id="all_answers_json" value="">

                @foreach($questions as $key => $question)
                    <div class="quiz-group">
                        <p class="quiz-question">{{ (($currentPage - 1) * 10) + $loop->iteration }}. {!! $renderPromptWithReferences($question['prompt']) !!}</p>

                        @foreach($question['options'] as $option)
                            <label class="quiz-option">
                                <input
                                    type="radio"
                                    name="answers[{{ $key }}]"
                                    value="{{ $option }}"
                                    data-question="{{ $key }}"
                                    {{ old("answers.$key", $userAnswers[$key] ?? '') === $option ? 'checked' : '' }}
                                >
                                <span>{!! $renderPromptWithReferences($option) !!}</span>
                            </label>
                        @endforeach

                        <input type="hidden" name="change_counts[{{ $key }}]" id="change_count_{{ $key }}" value="0">
                        <p class="quiz-change-note" id="change_note_{{ $key }}">You can change this answer up to 2 times.</p>
                    </div>
                @endforeach

                @if($currentPage === $totalPages)
                    <div class="quiz-action">
                        <button class="quiz-btn" type="submit">Submit Quiz</button>
                    </div>
                @else
                    <p class="quiz-submit-note">Submit button appears on the last page of this book (Page {{ $totalPages }}).</p>
                @endif
            </form>

            <div class="quiz-action" style="justify-content: space-between;">
                <a class="quiz-page-link quiz-nav-link" data-target-page="{{ max(1, $currentPage - 1) }}" href="{{ route('bible-quiz.start', ['book' => $currentBook, 'page' => max(1, $currentPage - 1)]) }}">← Previous Page</a>
                @php
                    $books = $books ?? [];
                    $currentIndex = array_search($currentBook, $books);
                    $nextBookIndex = $currentIndex + 1;
                    $nextBook = $nextBookIndex < count($books) ? $books[$nextBookIndex] : null;
                @endphp
                @if($currentPage === $totalPages && $nextBook)
                    <a class="quiz-page-link quiz-nav-link" data-target-page="{{ min($totalPages, $currentPage + 1) }}" href="{{ route('bible-quiz.start', ['book' => $nextBook, 'page' => 1]) }}">Next Book →</a>
                @elseif($currentPage < $totalPages)
                    <a class="quiz-page-link quiz-nav-link" data-target-page="{{ min($totalPages, $currentPage + 1) }}" href="{{ route('bible-quiz.start', ['book' => $currentBook, 'page' => min($totalPages, $currentPage + 1)]) }}">Next Page</a>
                @else
                    <a class="quiz-page-link quiz-nav-link is-disabled" data-target-page="{{ $currentPage }}" href="{{ route('bible-quiz.start', ['book' => $currentBook, 'page' => $currentPage]) }}" aria-disabled="true">Last Page</a>
                @endif
            </div>

            @if(!empty($submitted) && !empty($failedQuestions))
                <section class="quiz-review">
                    <h2>✨ Review Your Missed Questions</h2>

                    @foreach($failedQuestions as $failed)
                        <article class="quiz-review-card">
                            <p><span class="quiz-review-label">Question:</span> {!! $renderPromptWithReferences($failed['prompt']) !!}</p>
                            <p><span class="quiz-review-label">Your Answer:</span> {{ $failed['selected'] ?? 'No answer' }}</p>
                            <p><span class="quiz-review-label">Correct Answer:</span> {{ $failed['correct'] }}</p>
                        </article>
                    @endforeach
                </section>
            @endif

        </article>
        @endif
    </section>

    @if(!empty($showCover))
    @php
        $refreshAttemptUrl = route('bible-quiz.start', ['book' => $currentBook, 'page' => 1, 'reset' => 1]);
    @endphp
    <script>
        (function () {
            const coverOpenButton = document.getElementById('coverOpenButton');
            const quizBookShell = document.getElementById('quizBookShell');
            const sampleTrack = document.querySelector('.quiz-sample-track');
            const sampleItems = sampleTrack ? Array.from(sampleTrack.querySelectorAll('.quiz-sample-item')) : [];
            const attemptKey = 'bible_quiz_attempt_id';
            const answerPrefix = 'bible_quiz_answers_';
            const statusPrefix = 'bible_quiz_page_status_';
            let sampleIndex = 0;
            let sampleRotationId = null;

            function createAttemptId() {
                return Date.now().toString(36) + '_' + Math.random().toString(36).slice(2, 8);
            }

            function clearStoredAttempts() {
                try {
                    const keysToRemove = [];

                    for (let index = 0; index < localStorage.length; index++) {
                        const key = localStorage.key(index);

                        if (!key) {
                            continue;
                        }

                        if (key.indexOf(answerPrefix) === 0 || key.indexOf(statusPrefix) === 0) {
                            keysToRemove.push(key);
                        }
                    }

                    keysToRemove.forEach(function (key) {
                        localStorage.removeItem(key);
                    });
                } catch (error) {
                }
            }

            try {
                sessionStorage.setItem(attemptKey, createAttemptId());
            } catch (error) {
            }

            clearStoredAttempts();

            function updateSampleTrackPosition(previousIndex) {
                if (!sampleTrack || sampleItems.length === 0) {
                    return;
                }

                sampleItems.forEach(function (item, index) {
                    item.classList.remove('is-active', 'is-exiting');

                    if (index === sampleIndex) {
                        item.classList.add('is-active');
                    } else if (typeof previousIndex === 'number' && index === previousIndex) {
                        item.classList.add('is-exiting');
                    }
                });
            }

            function startSampleRotation() {
                if (!sampleTrack || sampleItems.length <= 1) {
                    return;
                }

                if (sampleRotationId !== null) {
                    window.clearInterval(sampleRotationId);
                }

                sampleRotationId = window.setInterval(function () {
                    const previousIndex = sampleIndex;
                    sampleIndex = (sampleIndex + 1) % sampleItems.length;
                    updateSampleTrackPosition(previousIndex);
                }, 2800);
            }

            updateSampleTrackPosition();
            startSampleRotation();
            window.addEventListener('resize', updateSampleTrackPosition);
            window.setTimeout(updateSampleTrackPosition, 120);

            if (!coverOpenButton || !quizBookShell) {
                return;
            }

            coverOpenButton.addEventListener('click', function (event) {
                event.preventDefault();
                quizBookShell.classList.add('opening');

                setTimeout(function () {
                    window.location.href = coverOpenButton.href;
                }, 700);
            });
        })();
    </script>
    @else
    @php
        $refreshAttemptUrl = route('bible-quiz.start', ['book' => $currentBook, 'page' => 1, 'reset' => 1]);
    @endphp
    <script>
        (function () {
            const totalQuestions = {{ count($questions) }};
            const totalBookQuestions = {{ (int) ($totalBookQuestions ?? count($questions)) }};
            const bookQuestionKeys = @json($bookQuestionKeys ?? array_keys($questions));
            const answeredCountElement = document.getElementById('answeredCount');
            const unansweredCountElement = document.getElementById('unansweredCount');
            const progressFill = document.getElementById('quizProgressFill');
            const progressTrack = document.querySelector('.quiz-progress-track');
            const radios = Array.from(document.querySelectorAll('input[type="radio"][data-question]'));
            const form = document.querySelector('form[action="{{ route('bible-quiz.submit') }}"]');
            const allAnswersField = document.getElementById('all_answers_json');
            const targetPageField = document.createElement('input');
            const confettiArea = document.getElementById('confettiArea');
            const isPerfectScore = {{ !empty($isPerfectScore) ? 'true' : 'false' }};
            const navLinks = Array.from(document.querySelectorAll('.quiz-nav-link'));
            const quizCard = document.querySelector('.quiz-card');
            const quizRefreshButton = document.getElementById('quizRefreshButton');
            const currentPage = {{ (int) $currentPage }};
            const totalPages = {{ (int) $totalPages }};
            const currentBook = @json($currentBook);
            const refreshAttemptUrl = @json($refreshAttemptUrl);
            const serverAnswers = @json($userAnswers ?? []);
            const attemptKey = 'bible_quiz_attempt_id';
            const answerPrefix = 'bible_quiz_answers_';
            const statusPrefix = 'bible_quiz_page_status_';

            targetPageField.type = 'hidden';
            targetPageField.name = 'target_page';
            targetPageField.value = String(currentPage);

            if (form) {
                form.appendChild(targetPageField);
            }

            function createAttemptId() {
                return Date.now().toString(36) + '_' + Math.random().toString(36).slice(2, 8);
            }

            let attemptId = '';

            try {
                attemptId = sessionStorage.getItem(attemptKey) || '';

                if (!attemptId) {
                    attemptId = createAttemptId();
                    sessionStorage.setItem(attemptKey, attemptId);
                }
            } catch (error) {
                attemptId = 'default_attempt';
            }

            const storageKey = answerPrefix + attemptId + '_' + currentBook;
            const pageStatusKey = statusPrefix + attemptId + '_' + currentBook;

            let persistedAnswers = {};
            let pageStatus = {};

            function clearStoredAttempts() {
                try {
                    const keysToRemove = [];

                    for (let index = 0; index < localStorage.length; index++) {
                        const key = localStorage.key(index);

                        if (!key) {
                            continue;
                        }

                        if (key.indexOf(answerPrefix) === 0 || key.indexOf(statusPrefix) === 0) {
                            keysToRemove.push(key);
                        }
                    }

                    keysToRemove.forEach(function (key) {
                        localStorage.removeItem(key);
                    });
                } catch (error) {
                }
            }

            try {
                const raw = localStorage.getItem(storageKey);
                persistedAnswers = raw ? JSON.parse(raw) : {};
            } catch (error) {
                persistedAnswers = {};
            }

            if (serverAnswers && typeof serverAnswers === 'object') {
                persistedAnswers = Object.assign({}, serverAnswers, persistedAnswers);
            }

            try {
                const rawStatus = localStorage.getItem(pageStatusKey);
                pageStatus = rawStatus ? JSON.parse(rawStatus) : {};
            } catch (error) {
                pageStatus = {};
            }

            const state = {};

            radios.forEach((radio) => {
                const questionKey = radio.getAttribute('data-question');

                if (!state[questionKey]) {
                    state[questionKey] = {
                        selected: null,
                        changes: 0,
                    };
                }

                if (radio.checked) {
                    state[questionKey].selected = radio.value;
                }
            });

            Object.keys(state).forEach((questionKey) => {
                if (state[questionKey].selected !== null) {
                    persistedAnswers[questionKey] = state[questionKey].selected;
                    return;
                }

                const savedValue = persistedAnswers[questionKey];

                if (!savedValue) {
                    return;
                }

                const savedRadio = document.querySelector('input[type="radio"][data-question="' + questionKey + '"][value="' + savedValue.replace(/"/g, '\\"') + '"]');

                if (!savedRadio) {
                    return;
                }

                savedRadio.checked = true;
                state[questionKey].selected = savedValue;
            });

            try {
                localStorage.setItem(storageKey, JSON.stringify(persistedAnswers));
            } catch (error) {
            }

            Object.keys(state).forEach((questionKey) => {
                const hidden = document.getElementById('change_count_' + questionKey);

                if (hidden) {
                    hidden.value = state[questionKey].changes;
                }
            });

            function setProgress() {
                const answered = bookQuestionKeys.filter((key) => {
                    const value = persistedAnswers[key];
                    return typeof value === 'string' && value.trim() !== '';
                }).length;
                const unanswered = totalBookQuestions - answered;
                const percent = totalBookQuestions ? Math.round((answered / totalBookQuestions) * 100) : 0;

                if (answeredCountElement) {
                    answeredCountElement.textContent = String(answered);
                }

                if (unansweredCountElement) {
                    unansweredCountElement.textContent = String(unanswered);
                }

                if (progressFill) {
                    progressFill.style.width = percent + '%';

                    progressFill.classList.remove('level-0', 'level-low', 'level-mid', 'level-high', 'level-full');

                    if (percent === 0) {
                        progressFill.classList.add('level-0');
                    } else if (percent < 50) {
                        progressFill.classList.add('level-low');
                    } else if (percent < 75) {
                        progressFill.classList.add('level-mid');
                    } else if (percent < 100) {
                        progressFill.classList.add('level-high');
                    } else {
                        progressFill.classList.add('level-full');
                    }
                }

                if (progressTrack) {
                    progressTrack.setAttribute('aria-valuenow', String(percent));
                }
            }

            function isCurrentPageFullyAnswered() {
                const answered = Object.values(state).filter((item) => item.selected !== null).length;

                return answered === totalQuestions;
            }

            function applyReviewedBadges() {
                navLinks.forEach((link) => {
                    const label = (link.textContent || '').trim();
                    const targetPage = String(link.getAttribute('data-target-page') || '');
                    const isNumberedLink = /^\d+$/.test(label);

                    if (!isNumberedLink) {
                        return;
                    }

                    link.classList.toggle('reviewed', pageStatus[targetPage] === true);
                });
            }

            function rebuildPageStatusFromAnswers() {
                const nextStatus = {};

                for (let pageNumber = 1; pageNumber <= totalPages; pageNumber++) {
                    const pageStart = (pageNumber - 1) * 10;
                    const pageKeys = bookQuestionKeys.slice(pageStart, pageStart + 10);
                    const isReviewed = pageKeys.length > 0 && pageKeys.every((key) => {
                        const answer = persistedAnswers[key];
                        return typeof answer === 'string' && answer.trim() !== '';
                    });

                    nextStatus[String(pageNumber)] = isReviewed;
                }

                pageStatus = nextStatus;

                try {
                    localStorage.setItem(pageStatusKey, JSON.stringify(pageStatus));
                } catch (error) {
                }
            }

            function updateCurrentPageReviewStatus() {
                pageStatus[String(currentPage)] = isCurrentPageFullyAnswered();

                try {
                    localStorage.setItem(pageStatusKey, JSON.stringify(pageStatus));
                } catch (error) {
                }

                applyReviewedBadges();
            }

            function updateNavigationState() {
                const pageComplete = isCurrentPageFullyAnswered();

                navLinks.forEach((link) => {
                    const targetPage = Number(link.getAttribute('data-target-page') || currentPage);
                    const movingForward = targetPage > currentPage;
                    const movingBackward = targetPage < currentPage;
                    
                    // Allow backwards navigation always; only restrict forward navigation if page incomplete
                    const shouldDisable = movingForward && !pageComplete;

                    link.classList.toggle('is-disabled', shouldDisable);
                    link.setAttribute('aria-disabled', shouldDisable ? 'true' : 'false');
                    if (shouldDisable) {
                        link.setAttribute('tabindex', '-1');
                    } else {
                        link.removeAttribute('tabindex');
                    }
                });
            }

            function updateChangeNote(questionKey) {
                const note = document.getElementById('change_note_' + questionKey);

                if (!note || !state[questionKey]) {
                    return;
                }

                const remaining = Math.max(0, 2 - state[questionKey].changes);

                if (remaining === 0) {
                    note.textContent = 'Change limit reached. You can no longer change this answer.';
                    note.classList.add('limit');
                    return;
                }

                note.textContent = 'You can still change this answer ' + remaining + ' time' + (remaining === 1 ? '' : 's') + '.';
                note.classList.remove('limit');
            }

            radios.forEach((radio) => {
                radio.addEventListener('change', function (event) {
                    const target = event.target;
                    const questionKey = target.getAttribute('data-question');

                    if (!questionKey || !state[questionKey]) {
                        setProgress();
                        return;
                    }

                    const currentSelection = state[questionKey].selected;
                    const newSelection = target.value;

                    if (currentSelection !== null && currentSelection !== newSelection) {
                        if (state[questionKey].changes >= 2) {
                            target.checked = false;

                            const previouslySelected = document.querySelector('input[type="radio"][data-question="' + questionKey + '"][value="' + currentSelection.replace(/"/g, '\\"') + '"]');

                            if (previouslySelected) {
                                previouslySelected.checked = true;
                            }

                            updateChangeNote(questionKey);
                            return;
                        }

                        state[questionKey].changes += 1;
                    }

                    state[questionKey].selected = newSelection;
                    persistedAnswers[questionKey] = newSelection;

                    try {
                        localStorage.setItem(storageKey, JSON.stringify(persistedAnswers));
                    } catch (error) {
                    }

                    const hidden = document.getElementById('change_count_' + questionKey);

                    if (hidden) {
                        hidden.value = String(state[questionKey].changes);
                    }

                    updateChangeNote(questionKey);
                    setProgress();
                    updateCurrentPageReviewStatus();
                    updateNavigationState();
                });
            });

            if (form) {
                form.addEventListener('submit', function () {
                    Object.keys(state).forEach((questionKey) => {
                        const hidden = document.getElementById('change_count_' + questionKey);

                        if (hidden) {
                            hidden.value = String(state[questionKey].changes);
                        }
                    });

                    if (allAnswersField) {
                        const filteredAnswers = {};

                        bookQuestionKeys.forEach((key) => {
                            if (typeof persistedAnswers[key] === 'string' && persistedAnswers[key].trim() !== '') {
                                filteredAnswers[key] = persistedAnswers[key];
                            }
                        });

                        allAnswersField.value = JSON.stringify(filteredAnswers);
                    }
                });
            }

            function runConfetti() {
                if (!confettiArea || !isPerfectScore) {
                    return;
                }

                const colors = ['#1f4a8a', '#3f74be', '#1f6b35', '#f2b632', '#8a6117', '#ffffff'];
                const startTime = Date.now();
                const durationMs = 2600;

                const timer = setInterval(function () {
                    const elapsed = Date.now() - startTime;

                    if (elapsed > durationMs) {
                        clearInterval(timer);
                        setTimeout(function () {
                            if (confettiArea) {
                                confettiArea.innerHTML = '';
                            }
                        }, 3600);
                        return;
                    }

                    for (let index = 0; index < 7; index++) {
                        const piece = document.createElement('span');
                        const left = Math.random() * 100;
                        const drift = (Math.random() * 220 - 110).toFixed(0) + 'px';
                        const spin = (Math.random() * 900 + 360).toFixed(0) + 'deg';
                        const size = (Math.random() * 7 + 7).toFixed(0) + 'px';
                        const duration = (Math.random() * 1.6 + 2.2).toFixed(2) + 's';

                        piece.className = 'confetti-piece';
                        piece.style.left = left + 'vw';
                        piece.style.background = colors[Math.floor(Math.random() * colors.length)];
                        piece.style.width = size;
                        piece.style.height = size;
                        piece.style.animationDuration = duration;
                        piece.style.setProperty('--drift', drift);
                        piece.style.setProperty('--spin', spin);

                        confettiArea.appendChild(piece);

                        setTimeout(function () {
                            piece.remove();
                        }, 4200);
                    }
                }, 120);
            }

            navLinks.forEach((link) => {
                link.addEventListener('click', function (event) {
                    const targetPage = Number(link.getAttribute('data-target-page') || currentPage);
                    const movingForward = targetPage > currentPage;
                    const navigatingAway = targetPage !== currentPage;

                    if (link.classList.contains('is-disabled')) {
                        event.preventDefault();
                        return;
                    }

                    if (movingForward && !isCurrentPageFullyAnswered()) {
                        event.preventDefault();
                        return;
                    }

                    if (targetPage === currentPage) {
                        return;
                    }

                    if (!form) {
                        return;
                    }

                    event.preventDefault();

                    if (allAnswersField) {
                        const filteredAnswers = {};

                        bookQuestionKeys.forEach((key) => {
                            if (typeof persistedAnswers[key] === 'string' && persistedAnswers[key].trim() !== '') {
                                filteredAnswers[key] = persistedAnswers[key];
                            }
                        });

                        allAnswersField.value = JSON.stringify(filteredAnswers);
                    }

                    targetPageField.value = String(targetPage);

                    if (quizCard) {
                        quizCard.classList.remove('page-flip-next', 'page-flip-prev');
                        quizCard.classList.add(movingForward ? 'page-flip-next' : 'page-flip-prev');
                    }

                    setTimeout(function () {
                        form.submit();
                    }, 260);
                });
            });

            function showGuardWarning(message) {
                if (!message) {
                    return;
                }

                CustomModal.show({
                    title: 'Alert',
                    message: message,
                    confirmText: 'OK',
                    cancelText: null
                });
            }

            document.addEventListener('contextmenu', function (event) {
                event.preventDefault();
            });

            document.addEventListener('copy', function (event) {
                event.preventDefault();
                showGuardWarning('Copying questions is disabled during the quiz.');
            });

            document.addEventListener('cut', function (event) {
                event.preventDefault();
            });

            document.addEventListener('dragstart', function (event) {
                event.preventDefault();
            });

            document.addEventListener('touchstart', function (event) {
                const target = event.target;

                if (target && target.closest && target.closest('.quiz-question, .quiz-option span')) {
                    target.style.webkitUserSelect = 'none';
                    target.style.userSelect = 'none';
                    target.style.webkitTouchCallout = 'none';
                }
            }, { passive: true });

            document.addEventListener('selectstart', function (event) {
                const target = event.target;

                if (target && target.closest && target.closest('.quiz-question, .quiz-option span')) {
                    event.preventDefault();
                }
            });

            document.addEventListener('keydown', function (event) {
                const key = (event.key || '').toLowerCase();
                const comboBlocked = (event.ctrlKey || event.metaKey) && ['c', 'x', 's', 'u', 'p', 'a'].indexOf(key) !== -1;
                const printScreen = key === 'printscreen';
                const devTools = key === 'f12';

                if (!comboBlocked && !printScreen && !devTools) {
                    return;
                }

                event.preventDefault();

                if (printScreen && quizCard) {
                    quizCard.classList.add('quiz-guard-blur');

                    window.setTimeout(function () {
                        quizCard.classList.remove('quiz-guard-blur');
                    }, 1300);
                }
            });

            window.addEventListener('beforeprint', function () {
                if (quizCard) {
                    quizCard.classList.add('quiz-guard-blur');
                }
            });

            window.addEventListener('afterprint', function () {
                if (quizCard) {
                    quizCard.classList.remove('quiz-guard-blur');
                }
            });

            document.addEventListener('visibilitychange', function () {
                if (!quizCard) {
                    return;
                }

                if (document.hidden) {
                    quizCard.classList.add('quiz-guard-blur');
                    return;
                }

                quizCard.classList.remove('quiz-guard-blur');
            });

            if (quizRefreshButton) {
                quizRefreshButton.addEventListener('click', function () {
                    clearStoredAttempts();

                    try {
                        sessionStorage.setItem(attemptKey, createAttemptId());
                    } catch (error) {
                    }

                    window.location.href = refreshAttemptUrl;
                });
            }

            rebuildPageStatusFromAnswers();
            Object.keys(state).forEach((questionKey) => updateChangeNote(questionKey));
            setProgress();
            updateCurrentPageReviewStatus();
            updateNavigationState();
            runConfetti();
        })();
    </script>
    @endif
@endsection

