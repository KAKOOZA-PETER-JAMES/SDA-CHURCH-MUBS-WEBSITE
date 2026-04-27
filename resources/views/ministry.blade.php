@extends('layouts.site')

@section('title', 'SDA CHURCH MUBS | Ministry')

@section('content')
    @php
        $churchBoardRolesPath = storage_path('app/church-board/roles.json');
        $churchBoardRoles = [];

        if (file_exists($churchBoardRolesPath)) {
            $churchBoardRoles = json_decode(file_get_contents($churchBoardRolesPath), true) ?? [];
        }
    @endphp

    <style>
        .ministry-plain-intro {
            margin-bottom: 1rem;
            max-width: 960px;
        }

        .ministry-plain-intro h2 {
            margin: 0 0 0.55rem;
            color: #1f2f47;
        }

        .ministry-plain-intro p {
            margin: 0 0 0.7rem;
            color: #2f3d57;
            line-height: 1.58;
        }

        .ministry-panel {
            display: none;
        }

        .ministry-panel.active {
            display: block;
        }

        .ministry-scroll-reveal {
            opacity: 0;
            transform: translateY(34px) scale(0.98);
            filter: blur(10px);
            transition: opacity 0.72s ease, transform 0.72s cubic-bezier(0.22, 1, 0.36, 1), filter 0.72s ease;
            transition-delay: var(--reveal-delay, 0ms);
            will-change: opacity, transform, filter;
        }

        .ministry-scroll-reveal.is-visible {
            opacity: 1;
            transform: translateY(0) scale(1);
            filter: blur(0);
        }

        .ministry-scroll-reveal.reveal-from-left {
            transform: translate3d(-46px, 34px, 0) scale(0.97) rotate(-1.2deg);
            transform-origin: left center;
        }

        .ministry-scroll-reveal.reveal-from-right {
            transform: translate3d(46px, 34px, 0) scale(0.97) rotate(1.2deg);
            transform-origin: right center;
        }

        .ministry-scroll-reveal.reveal-from-left.is-visible,
        .ministry-scroll-reveal.reveal-from-right.is-visible {
            transform: translate3d(0, 0, 0) scale(1) rotate(0deg);
        }

            .panel-departments-heading {
                margin: 2rem 0 2rem;
                text-align: center;
                color: #1f4a8a;
                font-weight: 800;
                letter-spacing: 0.02em;
                text-transform: uppercase;
            }

        .dept-link {
            color: #1f4a8a;
            text-decoration: none;
            font-weight: 600;
        }

        .dept-link:hover {
            text-decoration: underline;
        }

        .department-card-title {
            padding-bottom: 0.35rem;
            margin-bottom: 0.7rem;
            border-bottom: 1px solid #dce5f3;
        }

        .dept-list {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 0.55rem;
        }

        .dept-list li {
            border: 1px solid #d9e4f4;
            border-radius: 10px;
            background: #f8fbff;
            padding: 0.5rem 0.65rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .dept-list li:hover {
            transform: translateY(-1px);
            border-color: #b9cdee;
            box-shadow: 0 6px 14px rgba(20, 46, 88, 0.08);
        }

        .dept-list .dept-link {
            display: block;
            line-height: 1.35;
        }

        .department-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-top: 3rem;
            padding-top: 0.5rem;
        }

        .leader-slider {
            margin: 0.9rem 0 0.2rem;
            border: 1px solid #d6e1ef;
            border-radius: 12px;
            overflow: hidden;
            background: linear-gradient(145deg, #fbfdff 0%, #eef4ff 100%);
            box-shadow: 0 8px 16px rgba(21, 49, 92, 0.08);
        }

        .ministry-plain-intro.ministry-scroll-reveal,
        .association-program-section.ministry-scroll-reveal,
        .panel-departments-heading.ministry-scroll-reveal,
        .leader-slider.ministry-scroll-reveal {
            transform: translateY(26px);
        }

        .leader-slider-track {
            display: flex;
            width: max-content;
            animation: leaderSlide 28s linear infinite;
        }

        .leader-slider:hover .leader-slider-track {
            animation-play-state: paused;
        }

        .leader-card {
            width: min(98vw, 620px);
            min-height: 300px;
            padding: 1.25rem;
            display: grid;
            grid-template-columns: 180px 1fr;
            gap: 0.9rem;
            align-items: center;
            border-right: 1px solid #d8e3f2;
            background: transparent;
        }

        .leader-photo {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #c2d4ea;
            background: #f3f7ff;
        }

        .leader-role {
            margin: 0;
            color: #1f4a8a;
            font-size: 0.92rem;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .leader-name {
            margin: 0.2rem 0;
            color: #122d52;
            font-size: 1.55rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .leader-message {
            margin: 0;
            color: #324a68;
            font-size: 1.05rem;
            line-height: 1.55;
            max-height: 10.8rem;
            overflow: auto;
            padding-right: 0.35rem;
        }

        .department-tile {
            background: #ffffff;
            border: 1px solid #d9e0ec;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 22px rgba(14, 37, 74, 0.08);
            transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease, opacity 0.72s ease;
        }

        /* Tiles use opacity + transform only — no blur to prevent bleed over heading */
        .department-tile.ministry-scroll-reveal {
            filter: none !important;
        }

        .department-tile.is-visible:hover {
            transform: translateY(-8px);
            border-color: #bfd1ed;
            box-shadow: 0 18px 34px rgba(14, 37, 74, 0.14);
        }

        .department-tile-link {
            display: block;
            color: inherit;
            text-decoration: none;
        }

        .department-tile-media {
            position: relative;
            height: 245px;
            overflow: hidden;
            background: #e8edf6;
        }

        .department-tile-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .department-tile-overlay {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            padding: 1rem 1rem 0.95rem;
            background: linear-gradient(180deg, rgba(11, 27, 55, 0.08) 0%, rgba(11, 27, 55, 0.72) 100%);
            color: #ffffff;
        }

        .department-kicker {
            display: inline-block;
            font-size: 0.75rem;
            letter-spacing: 0.8px;
            font-weight: 700;
            opacity: 0.92;
            margin-bottom: 0.3rem;
        }

        .department-tile-overlay h2 {
            margin: 0;
            font-size: 1.12rem;
            line-height: 1.18;
            color: #ffffff;
        }

        .department-tile-body {
            padding: 0.95rem 0.9rem 0.9rem;
        }

        .department-tile-body p {
            margin: 0;
            color: #2f3d57;
            line-height: 1.55;
        }

        .department-tile-summary {
            margin-bottom: 0.7rem;
        }

        .department-tile .dept-list {
            grid-template-columns: 1fr;
            gap: 0.45rem;
        }

        .department-tile .dept-list li {
            border: 0;
            border-radius: 0;
            background: transparent;
            padding: 0;
            box-shadow: none;
            transform: none;
        }

        .department-tile .dept-list li:hover {
            border: 0;
            box-shadow: none;
            transform: none;
        }

        .department-tile .dept-link {
            display: block;
            border: 1px solid #d9e4f4;
            border-radius: 8px;
            background: #f8fbff;
            padding: 0.44rem 0.55rem;
            line-height: 1.3;
            font-weight: 600;
            text-decoration: none;
        }

        .department-tile .dept-link:hover {
            background: #eef4ff;
            border-color: #bfd1ed;
            text-decoration: none;
        }

        .department-cta {
            display: inline-flex;
            margin-top: 0.75rem;
            color: #8b5f1d;
            font-weight: 700;
            letter-spacing: 0.4px;
            font-size: 0.86rem;
        }

        .dept-detail {
            margin-top: 1rem;
            display: none;
        }

        .dept-detail.show {
            display: block;
            max-width: 1080px;
            margin: 1.2rem auto 0;
            animation: riseFade 0.38s ease-out both;
        }

        .ministry-content.hidden {
            display: none;
        }

        .dept-detail-wrap {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
            align-items: start;
        }

        .dept-page-title {
            margin: 0 0 0.7rem;
            color: #1f2f47;
            font-size: clamp(1.6rem, 3.5vw, 2.4rem);
            line-height: 1.15;
        }

        .dept-highlight {
            display: grid;
            grid-template-columns: 1fr 240px;
            gap: 1rem;
            align-items: start;
            margin-bottom: 1rem;
        }

        .dept-campaign {
            border: 1px solid #d9e0ec;
            border-radius: 12px;
            overflow: hidden;
            background: #f6f8fc;
            min-height: 210px;
            position: relative;
        }

        .dept-campaign::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 36px;
            background: #aa2a2f;
        }

        .dept-campaign-img {
            width: 100%;
            height: 210px;
            object-fit: cover;
            display: block;
            padding-left: 36px;
        }

        .dept-campaign-copy {
            padding: 0.8rem 0.95rem 0.9rem;
        }

        .dept-campaign-copy h2 {
            margin: 0;
            color: #1f2f47;
            font-size: 1.2rem;
            line-height: 1.2;
        }

        .dept-campaign-period {
            margin: 0.2rem 0 0.35rem;
            color: #6b7b93;
            font-weight: 700;
            font-size: 0.92rem;
        }

        .dept-campaign-summary {
            margin: 0;
            color: #2f3d57;
            line-height: 1.5;
        }

        .dept-director-card {
            border: 1px solid #d9e0ec;
            border-radius: 12px;
            padding: 0.8rem;
            background: #ffffff;
        }

        .dept-director-top {
            display: flex;
            gap: 0.6rem;
            align-items: center;
        }

        .dept-director-name {
            margin: 0;
            font-weight: 700;
            color: #1f2f47;
        }

        .dept-director-role {
            margin: 0.15rem 0 0;
            color: #6b7b93;
            font-size: 0.88rem;
        }

        .dept-director-mail {
            margin: 0.55rem 0 0;
            color: #1f4a8a;
            font-size: 0.86rem;
            word-break: break-all;
        }

        .dept-sections {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .dept-section-card h3 {
            margin: 0 0 0.45rem;
            color: #1f2f47;
        }

        .dept-section-card ul {
            margin: 0;
            padding-left: 1.1rem;
        }

        .dept-section-card li {
            margin-bottom: 0.36rem;
            color: #2f3d57;
            line-height: 1.45;
        }

        .dept-photo {
            width: min(240px, 100%);
            aspect-ratio: 4 / 5;
            border-radius: 12px;
            object-fit: cover;
            border: 1px solid #d9e0ec;
            background: #f0f4fb;
            margin: 0 auto;
            display: block;
        }

        .dept-photo-name {
            margin-top: 0.45rem;
            text-align: center;
            font-weight: 700;
            color: #1f4a8a;
        }

        .dept-photo-title {
            margin-top: 0.2rem;
            text-align: center;
            color: #5a667a;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .dept-subtitle {
            margin-top: 0;
            margin-bottom: 0.45rem;
            color: #5a667a;
            font-weight: 600;
        }

        .dept-detail-wrap > div:first-child {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .dept-roles {
            margin: 0;
            padding-left: 1.15rem;
        }

        .dept-roles li {
            margin-bottom: 0.32rem;
            color: #2f3d57;
        }

        .dept-roles {
            margin: 0;
            padding-left: 1.2rem;
        }

        .dept-note {
            margin-top: 0.6rem;
            color: #5a667a;
            font-size: 0.92rem;
        }

        .dept-explore {
            margin-top: 0.9rem;
            padding-top: 0.8rem;
            border-top: 1px solid #d9e0ec;
        }

        .dept-explore h3 {
            margin: 0 0 0.45rem;
            color: #1f4a8a;
            font-size: 1rem;
        }

        .dept-explore-list {
            margin: 0;
            padding-left: 1.2rem;
        }

        .dept-explore-list li {
            margin-bottom: 0.3rem;
        }

        .dept-explore-link {
            color: #1f4a8a;
            text-decoration: none;
            font-weight: 600;
        }

        .dept-explore-link:hover {
            text-decoration: underline;
        }

        .dept-back {
            border: 1px solid #c6d4ea;
            background: #ffffff;
            color: #1f4a8a;
            border-radius: 999px;
            padding: 0.42rem 0.95rem;
            font-weight: 700;
            cursor: pointer;
            margin-bottom: 0.8rem;
        }

        .board-introduction {
            margin-top: 0.35rem;
            border: 0;
            border-radius: 0;
            background: transparent;
            min-height: auto;
            padding: 0;
        }

        .board-introduction h2 {
            margin: 0 0 0.45rem;
            font-size: 1.1rem;
        }

        .board-introduction p {
            margin: 0 0 0.7rem;
            color: #3f4f6a;
            line-height: 1.55;
        }

        .board-introduction p:last-child {
            margin-bottom: 0;
        }

        @keyframes riseFade {
            0% {
                opacity: 0;
                transform: translateY(10px) scale(0.99);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes leaderSlide {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-50%);
            }
        }

        @media (max-width: 640px) {
            .leader-card {
                width: 96vw;
                min-height: 250px;
                grid-template-columns: 120px 1fr;
                padding: 0.9rem;
                gap: 0.75rem;
            }

            .leader-photo {
                width: 120px;
                height: 120px;
                border-radius: 50%;
            }

            .leader-name {
                font-size: 1.2rem;
            }

            .leader-message {
                font-size: 0.95rem;
                max-height: 8.6rem;
            }

            .dept-list {
                grid-template-columns: 1fr;
            }

            .dept-highlight {
                grid-template-columns: 1fr;
            }

            .dept-sections {
                grid-template-columns: 1fr;
            }

            .dept-detail-wrap {
                grid-template-columns: 1fr;
            }

            .dept-photo {
                max-width: 220px;
            }

            .dept-detail-body {
                padding: 1rem;
            }

            .dept-team-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .family-calendar {
                overflow-x: auto;
            }

            .family-calendar table {
                min-width: 540px;
            }

            .department-gallery {
                grid-template-columns: 1fr;
                gap: 0.9rem;
                margin-top: 1.1rem;
            }

            .department-tile {
                border-radius: 10px;
            }

            .department-tile-media {
                height: auto;
                min-height: 210px;
                aspect-ratio: 16 / 10;
            }

            .department-tile-media img {
                object-position: center top;
            }

            .department-tile-overlay {
                padding: 0.75rem 0.72rem;
            }

            .department-tile-overlay h2 {
                font-size: 1rem;
                line-height: 1.22;
            }

            .dept-detail-header {
                padding: 1rem 0.9rem;
            }

            .dept-detail-title {
                font-size: 1.35rem;
                line-height: 1.2;
            }

            .dept-detail-body {
                padding: 0.9rem;
            }

            .dept-intro {
                margin: 0.85rem 0 1rem;
                font-size: 0.94rem;
                line-height: 1.48;
            }

            .dept-team-photo {
                width: min(62vw, 170px);
                height: min(62vw, 170px);
            }

            .dept-team-name {
                margin-top: 0.58rem;
                font-size: 1.18rem;
                line-height: 1.25;
                word-break: break-word;
            }

            .dept-team-role {
                font-size: 0.95rem;
                line-height: 1.3;
            }

            .dept-extra-content {
                padding: 0.75rem;
            }

            .dept-extra-section h3 {
                font-size: 0.96rem;
            }

            .dept-explore-button {
                width: 100%;
                justify-content: center;
                text-align: center;
            }
        }

        .dept-detail {
            margin-top: 1rem;
            display: none;
        }

        .dept-detail.show {
            display: block;
        }

        .dept-detail-page {
            background: #ececec;
            border: 0;
        }

        .dept-detail-header {
            background: #00163a;
            padding: 2rem 2.2rem;
        }

        .dept-detail-title {
            margin: 0;
            color: #ffffff;
            font-size: clamp(1.8rem, 3vw, 2.4rem);
            font-weight: 700;
        }

        .dept-detail-body {
            padding: 1.5rem 2.2rem 2rem;
        }

        .dept-breadcrumb {
            margin: 0;
            color: #5d6b81;
            font-weight: 700;
            font-size: 0.86rem;
            letter-spacing: 0.2px;
        }

        .dept-breadcrumb span {
            margin: 0 0.3rem;
        }

        .dept-intro {
            margin: 1.25rem 0 1.5rem;
            color: #303e57;
            line-height: 1.5;
            font-size: 1.02rem;
            max-width: 980px;
        }

        .dept-team-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 2.2rem;
            max-width: 780px;
            margin-bottom: 1.35rem;
        }

        .dept-team-card {
            background: transparent;
        }

        .dept-team-photo {
            width: 160px;
            height: 160px;
            object-fit: cover;
            display: block;
            background: #d7dbe3;
            border-radius: 50%;
            border: 3px solid #ffffff;
            box-shadow: 0 8px 20px rgba(20, 47, 87, 0.18);
        }

        .dept-team-name {
            margin: 0.75rem 0 0.2rem;
            color: #2f3d57;
            font-size: 2rem;
            font-weight: 700;
        }

        .dept-team-role {
            margin: 0;
            color: #3f4f66;
            font-size: 1.8rem;
        }

        .dept-extra-content {
            margin: 0.35rem 0 1.15rem;
            border: 1px solid #dde4f1;
            border-radius: 10px;
            background: #ffffff;
            padding: 1rem;
        }

        .dept-extra-section + .dept-extra-section {
            margin-top: 0.95rem;
            padding-top: 0.9rem;
            border-top: 1px solid #e6ecf6;
        }

        .dept-extra-section h3 {
            margin: 0 0 0.45rem;
            color: #1f2f47;
            font-size: 1.05rem;
        }

        .dept-extra-section p {
            margin: 0 0 0.55rem;
            color: #2f3d57;
            line-height: 1.55;
        }

        .dept-extra-section ul {
            margin: 0;
            padding-left: 1.15rem;
        }

        .dept-extra-section li {
            margin-bottom: 0.4rem;
            color: #2f3d57;
            line-height: 1.5;
        }

        .dept-extra-section a {
            color: #1f4a8a;
            text-decoration: none;
            font-weight: 600;
        }

        .dept-extra-section a:hover {
            text-decoration: underline;
        }

        .dept-explore-button {
            margin-top: 1.5rem;
            display: inline-flex;
            align-items: center;
            padding: 0.56rem 1rem;
            border-radius: 6px;
            border: 1px solid #00163a;
            background: #00163a;
            color: #ffffff;
            text-decoration: none;
            font-weight: 700;
        }

        .dept-explore-button:hover {
            background: #02265e;
            border-color: #02265e;
        }

        .association-program-section {
            margin: 1.4rem 0 1.25rem;
            display: block;
            clear: both;
        }

        .association-history-cta {
            margin: 0.5rem 0 1.75rem;
            padding: 0.8rem 0.95rem;
            border: 1px solid #d6e1f2;
            border-radius: 14px;
            background: linear-gradient(140deg, #f6f9ff 0%, #edf3ff 60%, #f9fbff 100%);
            box-shadow: 0 10px 20px rgba(15, 43, 85, 0.09);
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 0.75rem 1rem;
            align-items: center;
            clear: both;
            position: relative;
            z-index: 1;
        }

        .association-history-divider {
            width: 100%;
            height: 1px;
            margin: 0 0 1.25rem;
            background: linear-gradient(90deg, rgba(214, 225, 242, 0), rgba(214, 225, 242, 1), rgba(214, 225, 242, 0));
            clear: both;
        }

        .association-history-cta h3 {
            margin: 0 0 0.3rem;
            color: #143761;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .association-history-cta p {
            margin: 0;
            color: #304a6b;
            line-height: 1.5;
        }

        .association-history-btn {
            margin-top: 0;
            border-radius: 999px;
            padding: 0.58rem 1.05rem;
            white-space: nowrap;
            justify-self: start;
        }

        @media (max-width: 640px) {
            .association-history-cta {
                grid-template-columns: 1fr;
            }

            .association-history-btn {
                justify-self: start;
            }
        }

        .family-calendar {
            margin: 0;
            border: 0;
            border-radius: 16px;
            background: linear-gradient(150deg, #f4f8ff 0%, #e9f1ff 55%, #f8fbff 100%);
            overflow: hidden;
            box-shadow: 0 14px 30px rgba(12, 38, 74, 0.14);
        }

        .family-calendar h3 {
            margin: 0;
            padding: 1rem 1.1rem;
            background: linear-gradient(135deg, #00163a 0%, #153a68 100%);
            color: #ffffff;
            font-size: 1.04rem;
            letter-spacing: 0.7px;
            display: flex;
            align-items: center;
            gap: 0.45rem;
        }

        .family-calendar h3::before {
            content: '◆';
            color: #8ce4f0;
            font-size: 0.88rem;
        }

        .fellowship-accordion {
            padding: 0.75rem;
        }

        .fellowship-item {
            margin-bottom: 0.6rem;
            background: #ffffff;
            border: 1px solid #d7e3f5;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(15, 43, 85, 0.08);
            transition: all 0.3s ease;
        }

        .fellowship-item:hover {
            box-shadow: 0 6px 16px rgba(15, 43, 85, 0.15);
            border-color: #3e8391;
        }

        .fellowship-trigger {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.9rem 1rem;
            background: linear-gradient(135deg, #f0f6fb 0%, #eef4f9 100%);
            border: none;
            cursor: pointer;
            font-size: 0.95rem;
            font-weight: 700;
            color: #0f2b55;
            transition: all 0.3s ease;
        }

        .fellowship-trigger:hover {
            background: linear-gradient(135deg, #e5f1fb 0%, #e3ecf7 100%);
            color: #1f4a8a;
        }

        .fellowship-item.active .fellowship-trigger {
            background: linear-gradient(135deg, #0f2b55 0%, #1a4578 100%);
            color: #ffffff;
        }

        .fellowship-trigger-day {
            font-weight: 800;
            color: inherit;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex: 1;
        }

        .fellowship-trigger-day::before {
            content: '▸';
            display: inline-block;
            width: 1.2rem;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .fellowship-item.active .fellowship-trigger-day::before {
            transform: rotate(90deg);
        }

        .fellowship-trigger-icon {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.85rem;
            opacity: 0.7;
        }

        .fellowship-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, padding 0.3s ease;
            background: #ffffff;
        }

        .fellowship-item.active .fellowship-content {
            max-height: 300px;
            padding: 1rem;
            border-top: 2px solid #e6edf9;
        }

        .fellowship-details {
            display: grid;
            gap: 0.8rem;
        }

        .fellowship-detail-row {
            display: grid;
            grid-template-columns: 140px 1fr;
            gap: 1rem;
            align-items: start;
        }

        .fellowship-detail-label {
            font-weight: 700;
            color: #0f2b55;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .fellowship-detail-value {
            color: #33435b;
            line-height: 1.6;
            font-size: 0.94rem;
        }

        @media (max-width: 980px) {
            .fellowship-detail-row {
                grid-template-columns: 1fr;
                gap: 0.3rem;
            }
        }

        @media (max-width: 560px) {
            .fellowship-trigger {
                padding: 0.75rem 0.85rem;
                font-size: 0.9rem;
            }

            .fellowship-detail-label {
                font-size: 0.8rem;
            }

            .fellowship-detail-value {
                font-size: 0.88rem;
            }

            .fellowship-item.active .fellowship-content {
                max-height: 400px;
            }
        }

    </style>

    <div id="ministry-content" class="ministry-content">
        @php
            $adminContent = \App\Support\AdminContentStore::get();
            $adminDepartments = $adminContent['departments'] ?? [];
            $churchBoardDepartments = array_values($adminDepartments['church_board'] ?? []);
            $associationDepartments = array_values($adminDepartments['association'] ?? []);
            $churchFamilyDepartments = array_values($adminDepartments['church_families'] ?? []);
            $churchBoardLeaders = array_values($adminDepartments['church_board_leaders'] ?? []);
            $associationLeaders = array_values($adminDepartments['association_leaders'] ?? []);

            $boardFallbackDepartments = [
                ['name' => 'Children’s Ministries', 'image' => '4.jpg', 'intro' => 'Faith nurturing programs for children.', 'details' => 'Sabbath programs and child discipleship support.'],
                ['name' => 'Communication', 'image' => 'adventist world radio.png', 'intro' => 'Church communication and media witness.', 'details' => 'Church messaging, media publishing, and announcements.'],
                ['name' => 'Education Department', 'image' => '6.png', 'intro' => 'Supports Adventist education and mentorship.', 'details' => 'Education-focused discipleship and academic support.'],
                ['name' => 'Family Ministries', 'image' => 'S1.jpg', 'intro' => 'Builds strong Christ-centered homes.', 'details' => 'Marriage, parenting, and home discipleship enrichment.'],
                ['name' => 'Health Ministries', 'image' => 'S2.jpg', 'intro' => 'Whole-person wellness ministry.', 'details' => 'Lifestyle education and community wellness outreach.'],
                ['name' => 'Publishing', 'image' => 'HOPE.png', 'intro' => 'Literature and publication ministry.', 'details' => 'Sharing truth through literature and publications.'],
                ['name' => 'Sabbath School/Personal Ministries', 'image' => 'adventist news network.jpg', 'intro' => 'Bible study and personal evangelism.', 'details' => 'Sabbath School classes and soul-winning initiatives.'],
                ['name' => 'Stewardship', 'image' => 'tp-clean.png', 'intro' => 'Faithful stewardship of life and resources.', 'details' => 'Biblical stewardship education and practical accountability.'],
                ['name' => 'Women’s Ministries', 'image' => 'sda.png', 'intro' => 'Nurtures women for mission and service.', 'details' => 'Women-focused spiritual growth and support initiatives.'],
                ['name' => 'Adventist Youth Ministry', 'image' => 'S1.jpg', 'intro' => 'Youth discipleship and leadership growth.', 'details' => 'Youth worship, mission, and leadership pathways.'],
                ['name' => 'Pathfinders', 'image' => '4.jpg', 'intro' => 'Pathfinder club training and outreach.', 'details' => 'Youth club discipline, practical skills, and mission.'],
                ['name' => 'Adventurers Club', 'image' => 'S2.jpg', 'intro' => 'Children’s club faith development.', 'details' => 'Adventure-based discipleship for younger children.'],
            ];

            $associationFallbackDepartments = [
                ['name' => 'Family Ministries', 'image' => 'S1.jpg', 'intro' => 'Family-focused pastoral and discipleship support.', 'details' => 'Marriage, parenting, and family life enrichment.'],
                ['name' => 'Health Ministries', 'image' => 'S2.jpg', 'intro' => 'Promotes healthy lifestyle and prevention.', 'details' => 'Health awareness and wellness outreach programs.'],
                ['name' => 'Communication', 'image' => 'adventist world radio.png', 'intro' => 'Association communication and media.', 'details' => 'Official communication and media coordination.'],
                ['name' => 'Stewardship', 'image' => 'tp-clean.png', 'intro' => 'Stewardship and accountability ministry.', 'details' => 'Stewardship mentorship for members and leaders.'],
                ['name' => 'Women’s Ministries', 'image' => 'sda.png', 'intro' => 'Encourages women in mission and discipleship.', 'details' => 'Training, fellowship, and service opportunities.'],
                ['name' => 'Adventist Youth Ministry', 'image' => '4.jpg', 'intro' => 'Youth mobilization and mission pathways.', 'details' => 'Youth congresses, bible camps, and mission events.'],
                ['name' => 'Children’s Ministries', 'image' => '6.png', 'intro' => 'Children’s faith formation and support.', 'details' => 'Faith growth curriculum and mentorship support.'],
                ['name' => 'Education Department', 'image' => 'adventist news network.jpg', 'intro' => 'Educational support and Adventist values.', 'details' => 'Values-based education and mentorship programs.'],
                ['name' => 'Publishing', 'image' => 'HOPE.png', 'intro' => 'Literature evangelism and resources.', 'details' => 'Publishing initiatives and distribution channels.'],
                ['name' => 'Public Affairs and Religious Liberty', 'image' => 'S2.jpg', 'intro' => 'Religious liberty advocacy and awareness.', 'details' => 'Community relations and legal awareness initiatives.'],
            ];

            $mergeWithFallbacks = function (array $rows, array $fallbacks): array {
                $normalized = collect($rows)->filter(function ($item) {
                    return trim((string) ($item['name'] ?? '')) !== '';
                })->values()->all();

                $existingNames = collect($normalized)->map(function ($item) {
                    return strtolower(trim((string) ($item['name'] ?? '')));
                })->filter()->values()->all();

                foreach ($fallbacks as $fallback) {
                    $fallbackName = strtolower(trim((string) ($fallback['name'] ?? '')));
                    if ($fallbackName === '' || in_array($fallbackName, $existingNames, true)) {
                        continue;
                    }

                    $normalized[] = array_merge([
                        'secretary_name' => 'Office Secretary',
                        'explore_url' => '',
                        'pastor_name' => 'Department Head',
                        'pastor_phone' => '',
                        'pastor_email' => '',
                        'pastor_info' => '',
                    ], $fallback);
                }

                return $normalized;
            };

            $churchBoardDepartments = $mergeWithFallbacks($churchBoardDepartments, $boardFallbackDepartments);
            $associationDepartments = $mergeWithFallbacks($associationDepartments, $associationFallbackDepartments);

            $boardDescription = 'Church board member details are available in the official board document.';
            $boardPdfPath = public_path('Church-Board-Member.pdf');

            if (is_file($boardPdfPath) && class_exists(\Smalot\PdfParser\Parser::class)) {
                try {
                    $pdfParser = new \Smalot\PdfParser\Parser();
                    $rawBoardText = $pdfParser->parseFile($boardPdfPath)->getText();
                    $normalizedBoardText = trim((string) preg_replace('/\s+/', ' ', $rawBoardText));

                    if ($normalizedBoardText !== '') {
                        $boardSentences = preg_split('/(?<=[.!?])\s+/', $normalizedBoardText) ?: [];
                        $boardDescription = trim(implode(' ', array_slice($boardSentences, 0, 3)));
                    }
                } catch (\Throwable $error) {
                    $boardDescription = 'Church board member details are available in the official board document.';
                }
            }

            $officialSdaDepartmentUrls = [
                'children’s ministries' => 'https://children.adventist.org',
                'children\'s ministries' => 'https://children.adventist.org',
                'communication' => 'https://communication.adventist.org',
                'communications department' => 'https://communication.adventist.org',
                'education department' => 'https://education.adventist.org',
                'family ministries' => 'https://family.adventist.org',
                'family ministry' => 'https://family.adventist.org',
                'health ministries' => 'https://health.adventist.org',
                'health and temperance' => 'https://health.adventist.org',
                'public affairs and religious liberty' => 'https://adventistliberty.org',
                'religious liberty' => 'https://adventistliberty.org',
                'publishing' => 'https://publishing.adventist.org',
                'sabbath school/personal ministries' => 'https://sabbathschoolpersonalministries.org',
                'personal ministry' => 'https://sabbathschoolpersonalministries.org',
                'stewardship' => 'https://stewardship.adventist.org',
                'women’s ministries' => 'https://women.adventist.org',
                'women\'s ministries' => 'https://women.adventist.org',
                'adventist youth ministry' => 'https://youth.adventist.org',
                'youth' => 'https://youth.adventist.org',
                'pathfinders' => 'https://pathfinders.adventist.org',
                'adventurers club' => 'https://adventurers.org',
            ];

            $resolveDepartmentImage = function ($imagePath, bool $allowFallback = true) {
                if (is_string($imagePath) && preg_match('/^https?:\/\//i', $imagePath)) {
                    return $imagePath;
                }

                if (is_string($imagePath) && trim($imagePath) !== '' && file_exists(public_path($imagePath))) {
                    return asset($imagePath);
                }

                return $allowFallback ? asset('department-head-placeholder.svg') : '';
            };

            $departmentDataMap = [];

            $buildPanelDepartments = function (array $rows) use (&$departmentDataMap, $officialSdaDepartmentUrls, $resolveDepartmentImage) {
                return collect($rows)->map(function ($department) use (&$departmentDataMap, $officialSdaDepartmentUrls, $resolveDepartmentImage) {
                    $name = trim((string) ($department['name'] ?? ''));
                    $imagePath = (string) ($department['image'] ?? '');
                    $key = strtolower($name);
                    $exploreUrl = trim((string) ($department['explore_url'] ?? ''));

                    if ($exploreUrl === '' && isset($officialSdaDepartmentUrls[$key])) {
                        $exploreUrl = $officialSdaDepartmentUrls[$key];
                    }

                    $departmentDataMap[$name] = [
                        'intro' => trim((string) ($department['department_introduction'] ?? $department['intro'] ?? $department['details'] ?? '')),
                        'headName' => trim((string) ($department['department_head_name'] ?? $department['pastor_name'] ?? 'Department Head')),
                        'positionTitle' => 'Director',
                        'photo' => $resolveDepartmentImage((string) ($department['department_head_photo'] ?? $imagePath)),
                        'pastorPhone' => trim((string) ($department['pastor_phone'] ?? '')),
                        'pastorEmail' => trim((string) ($department['pastor_email'] ?? '')),
                        'secretaryName' => trim((string) ($department['secretary_name'] ?? 'Office Secretary')),
                        'secretaryTitle' => 'Office Secretary',
                        'secretaryPhoto' => $resolveDepartmentImage((string) ($department['secretary_photo'] ?? $imagePath)),
                        'website' => $exploreUrl,
                        'extraContentHtml' => '<div class="dept-extra-section"><h3>Department Information</h3><p>' . e(trim((string) ($department['details'] ?? 'Detailed department information will be shared soon.'))) . '</p></div>',
                    ];

                    return [
                        'name' => $name,
                        'image' => $resolveDepartmentImage($imagePath, false),
                    ];
                })->filter(function ($department) {
                    return trim((string) ($department['name'] ?? '')) !== '';
                })->values()->all();
            };

            $sharedDepartmentPanels = [
                'board-panel' => [
                    'title' => 'Church Board Description',
                    'intro' => $boardDescription,
                    'label' => 'Church board departments',
                    'download_url' => asset('Church-Board-Member.pdf'),
                    'departments' => $buildPanelDepartments($churchBoardDepartments),
                    'leaders' => collect($churchBoardLeaders)->filter(function ($row) {
                        return trim((string) ($row['name'] ?? '')) !== ''
                            || trim((string) ($row['role'] ?? '')) !== ''
                            || trim((string) ($row['message'] ?? '')) !== '';
                    })->values()->all(),
                ],
                'association-panel' => [
                    'title' => 'MUSDAA- BS Departments',
                    'intro' => 'MUSDAA-BS department details and leadership information.',
                    'label' => 'Association departments',
                    'download_url' => null,
                    'departments' => $buildPanelDepartments($associationDepartments),
                    'leaders' => collect($associationLeaders)->filter(function ($row) {
                        return trim((string) ($row['name'] ?? '')) !== ''
                            || trim((string) ($row['role'] ?? '')) !== ''
                            || trim((string) ($row['message'] ?? '')) !== '';
                    })->values()->all(),
                ],
                'families-panel' => [
                    'title' => 'Church Families Departments',
                    'intro' => 'Church families and their ministry leadership details.',
                    'label' => 'Church family departments',
                    'download_url' => null,
                    'departments' => $buildPanelDepartments($churchFamilyDepartments),
                    'leaders' => [],
                ],
            ];

        @endphp
        @foreach($sharedDepartmentPanels as $panelId => $panelMeta)
            <section id="{{ $panelId }}" class="ministry-panel">
                <article class="board-introduction ministry-plain-intro ministry-scroll-reveal" style="--reveal-delay: 0ms;" aria-label="{{ $panelMeta['title'] }} introduction">
                    <h2>{{ $panelMeta['title'] }}</h2>
                    <p>{{ $panelMeta['intro'] }}</p>
                    @if(!empty($panelMeta['download_url']))
                        <p>
                            <a class="dept-explore-button" href="{{ $panelMeta['download_url'] }}" download>Detailed PDF</a>
                        </p>
                    @endif
                </article>

                @if($panelId === 'association-panel')
                    <section class="association-program-section ministry-scroll-reveal" style="--reveal-delay: 90ms;" aria-label="Association post and fellowship schedule">
                        <section class="family-calendar" aria-label="Fellowship program calendar">
                            <h3>FELLOWSHIP PROGRAM</h3>
                            <div class="fellowship-accordion" id="ministry-fellowship-accordion">
                                <div class="fellowship-item">
                                    <button class="fellowship-trigger" aria-expanded="false" data-day="Monday">
                                        <span class="fellowship-trigger-day">Monday (Second Day)</span>
                                        <span class="fellowship-trigger-icon">📍</span>
                                    </button>
                                    <div class="fellowship-content">
                                        <div class="fellowship-details">
                                            <div class="fellowship-detail-row">
                                                <span class="fellowship-detail-label">Area:</span>
                                                <span class="fellowship-detail-value">Mwanga</span>
                                            </div>
                                            <div class="fellowship-detail-row">
                                                <span class="fellowship-detail-label">Hostels:</span>
                                                <span class="fellowship-detail-value">KABBS, IDEAL CLASSIC, AKAMWEESI HOSTEL, ESTERI, VISION HOSTEL, LUXY, DEVINE, DOFRA, BETSAM, VARRYCOURTS, ST.VICENT and any residence between those hostels.</span>
                                            </div>
                                            <div class="fellowship-detail-row">
                                                <span class="fellowship-detail-label">Time:</span>
                                                <span class="fellowship-detail-value">5:30 PM - 7:30 PM</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="fellowship-item">
                                    <button class="fellowship-trigger" aria-expanded="false" data-day="Tuesday">
                                        <span class="fellowship-trigger-day">Tuesday (Third Day)</span>
                                        <span class="fellowship-trigger-icon">📍</span>
                                    </button>
                                    <div class="fellowship-content">
                                        <div class="fellowship-details">
                                            <div class="fellowship-detail-row">
                                                <span class="fellowship-detail-label">Area:</span>
                                                <span class="fellowship-detail-value">Kataza</span>
                                            </div>
                                            <div class="fellowship-detail-row">
                                                <span class="fellowship-detail-label">Hostels:</span>
                                                <span class="fellowship-detail-value">IDEAL DIAMONT, LORDS HOSTEL, PALMERS, MUYAMMY, NAIVASHA, B2, IDEAL PLATINUM, IDEAL ANNEX, IDEAL OLD, PACKACH HOSTEL and any residence between those hostels.</span>
                                            </div>
                                            <div class="fellowship-detail-row">
                                                <span class="fellowship-detail-label">Time:</span>
                                                <span class="fellowship-detail-value">5:30 PM - 7:30 PM</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="fellowship-item">
                                    <button class="fellowship-trigger" aria-expanded="false" data-day="Wednesday">
                                        <span class="fellowship-trigger-day">Wednesday (Fourth Day)</span>
                                        <span class="fellowship-trigger-icon">📍</span>
                                    </button>
                                    <div class="fellowship-content">
                                        <div class="fellowship-details">
                                            <div class="fellowship-detail-row">
                                                <span class="fellowship-detail-label">Area:</span>
                                                <span class="fellowship-detail-value">Small Gate</span>
                                            </div>
                                            <div class="fellowship-detail-row">
                                                <span class="fellowship-detail-label">Coverage:</span>
                                                <span class="fellowship-detail-value">Students residing around small gate.</span>
                                            </div>
                                            <div class="fellowship-detail-row">
                                                <span class="fellowship-detail-label">Time:</span>
                                                <span class="fellowship-detail-value">5:30 PM - 7:30 PM</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="fellowship-item">
                                    <button class="fellowship-trigger" aria-expanded="false" data-day="Thursday">
                                        <span class="fellowship-trigger-day">Thursday (Fifth Day)</span>
                                        <span class="fellowship-trigger-icon">📍</span>
                                    </button>
                                    <div class="fellowship-content">
                                        <div class="fellowship-details">
                                            <div class="fellowship-detail-row">
                                                <span class="fellowship-detail-label">Area:</span>
                                                <span class="fellowship-detail-value">Berlin Common Room</span>
                                            </div>
                                            <div class="fellowship-detail-row">
                                                <span class="fellowship-detail-label">Coverage:</span>
                                                <span class="fellowship-detail-value">Involving all students from all fellowshiping areas.</span>
                                            </div>
                                            <div class="fellowship-detail-row">
                                                <span class="fellowship-detail-label">Time:</span>
                                                <span class="fellowship-detail-value">5:30 PM - 7:30 PM</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="fellowship-item">
                                    <button class="fellowship-trigger" aria-expanded="false" data-day="Friday">
                                        <span class="fellowship-trigger-day">Friday (Sixth Day)</span>
                                        <span class="fellowship-trigger-icon">📍</span>
                                    </button>
                                    <div class="fellowship-content">
                                        <div class="fellowship-details">
                                            <div class="fellowship-detail-row">
                                                <span class="fellowship-detail-label">Area:</span>
                                                <span class="fellowship-detail-value">Berlin Common Room</span>
                                            </div>
                                            <div class="fellowship-detail-row">
                                                <span class="fellowship-detail-label">Coverage:</span>
                                                <span class="fellowship-detail-value">Involving all students from all fellowshiping areas.</span>
                                            </div>
                                            <div class="fellowship-detail-row">
                                                <span class="fellowship-detail-label">Time:</span>
                                                <span class="fellowship-detail-value">5:30 PM - 7:30 PM</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="fellowship-item">
                                    <button class="fellowship-trigger" aria-expanded="false" data-day="Saturday">
                                        <span class="fellowship-trigger-day">Saturday (Seventh Day)</span>
                                        <span class="fellowship-trigger-icon">📍</span>
                                    </button>
                                    <div class="fellowship-content">
                                        <div class="fellowship-details">
                                            <div class="fellowship-detail-row">
                                                <span class="fellowship-detail-label">Area:</span>
                                                <span class="fellowship-detail-value">Berlin Common Room</span>
                                            </div>
                                            <div class="fellowship-detail-row">
                                                <span class="fellowship-detail-label">Coverage:</span>
                                                <span class="fellowship-detail-value">Sabbath worship and fellowship for all students.</span>
                                            </div>
                                            <div class="fellowship-detail-row">
                                                <span class="fellowship-detail-label">Time:</span>
                                                <span class="fellowship-detail-value">8:00 AM - 6:00 PM</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </section>
                @endif

                @if(!empty($panelMeta['leaders']))
                    @php
                        $sliderRows = array_merge($panelMeta['leaders'], $panelMeta['leaders']);
                    @endphp
                    <section class="leader-slider ministry-scroll-reveal" style="--reveal-delay: 140ms;" aria-label="{{ $panelMeta['title'] }} leadership slider">
                        <div class="leader-slider-track">
                            @foreach($sliderRows as $leader)
                                @php
                                    $leaderImage = $resolveDepartmentImage((string) ($leader['image'] ?? ''));
                                @endphp
                                <article class="leader-card">
                                    <img class="leader-photo" src="{{ $leaderImage }}" alt="{{ $leader['name'] ?? 'Leader' }} photo">
                                    <div>
                                        <p class="leader-role">{{ $leader['role'] ?? 'Leader' }}</p>
                                        <p class="leader-name">{{ $leader['name'] ?? 'Name to be added' }}</p>
                                        <p class="leader-message">{{ $leader['message'] ?? 'Leadership message will appear here.' }}</p>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif

                    @if($panelId === 'board-panel')
                        <h3 class="panel-departments-heading ministry-scroll-reveal" style="--reveal-delay: 180ms;">Local Church Departments of SDA MUBS</h3>
                    @elseif($panelId === 'association-panel')
                        <h3 class="panel-departments-heading ministry-scroll-reveal" style="--reveal-delay: 180ms;">Departments of MUSDAA-BS</h3>
                    @endif

                <div class="department-gallery" aria-label="{{ $panelMeta['label'] }}">
                    @foreach($panelMeta['departments'] as $index => $department)
                        <article class="department-tile ministry-scroll-reveal {{ $index % 2 === 0 ? 'reveal-from-left' : 'reveal-from-right' }}" style="--reveal-delay: {{ min($index * 85, 680) }}ms;">
                            <a class="department-tile-link dept-link" href="#" data-dept="{{ $department['name'] }}">
                                <div class="department-tile-media">
                                    @if(!empty($department['image']))
                                        <img src="{{ $department['image'] }}" alt="{{ $department['name'] }}">
                                    @endif
                                    <div class="department-tile-overlay">
                                        <span class="department-kicker">DEPARTMENT</span>
                                        <h2>{{ $department['name'] }}</h2>
                                    </div>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>

                @if($panelId === 'association-panel')
                    <div class="association-history-divider" aria-hidden="true"></div>
                    <section class="association-history-cta" aria-label="Association leadership archive">
                        <h3>Leadership Archive</h3>
                        <p>See the full record of previous association presidents and executive teams by period.</p>
                        <a class="dept-explore-button association-history-btn" href="{{ route('association.previous-executives') }}">Previous Executives</a>
                    </section>
                @endif
            </section>
        @endforeach
    </div>

    <section id="dept-detail" class="dept-detail" aria-live="polite">
        <article class="dept-detail-page">
            <header class="dept-detail-header">
                <h1 id="dept-title" class="dept-detail-title">Department</h1>
            </header>

            <div class="dept-detail-body">
                <nav class="dept-breadcrumb" aria-label="Department breadcrumb">
                    <a id="dept-about-link" href="{{ route('home') }}">ABOUT</a>
                    <span aria-hidden="true">›</span>
                    <a id="dept-departments-link" href="{{ route('ministry') }}">DEPARTMENTS</a>
                    <span aria-hidden="true">›</span>
                    <span id="dept-breadcrumb-title">DEPARTMENT</span>
                </nav>

                <p id="dept-intro" class="dept-intro">Department introduction will appear here.</p>

                <div class="dept-team-grid" aria-label="Department leadership">
                    <article class="dept-team-card">
                        <img id="dept-photo" class="dept-team-photo" src="{{ asset('department-head-placeholder.svg') }}" alt="Department head photo">
                        <h2 id="dept-photo-name" class="dept-team-name">HEAD: Department Head</h2>
                        <p id="dept-photo-title" class="dept-team-role">Director</p>
                    </article>

                    <article class="dept-team-card">
                        <img id="dept-secretary-photo" class="dept-team-photo" src="{{ asset('department-head-placeholder.svg') }}" alt="Department office secretary photo">
                        <h2 id="dept-secretary-name" class="dept-team-name">Office Secretary</h2>
                        <p id="dept-secretary-title" class="dept-team-role">Office Secretary</p>
                    </article>
                </div>

                <section id="dept-extra-content" class="dept-extra-content" aria-label="Department information"></section>

                <a id="dept-website-link" class="dept-explore-button" href="#" target="_blank" rel="noopener">Explore Department Website</a>
            </div>
        </article>
    </section>

    <script>
        (function () {
            const panels = document.querySelectorAll('.ministry-panel');
            const deptLinks = document.querySelectorAll('.dept-link');
            const detailCard = document.getElementById('dept-detail');
            const ministryContent = document.getElementById('ministry-content');
            const detailTitle = document.getElementById('dept-title');
            const detailAboutLink = document.getElementById('dept-about-link');
            const detailDepartmentsLink = document.getElementById('dept-departments-link');
            const detailBreadcrumbTitle = document.getElementById('dept-breadcrumb-title');
            const detailIntro = document.getElementById('dept-intro');
            const detailPhoto = document.getElementById('dept-photo');
            const detailPhotoName = document.getElementById('dept-photo-name');
            const detailPhotoTitle = document.getElementById('dept-photo-title');
            const detailSecretaryPhoto = document.getElementById('dept-secretary-photo');
            const detailSecretaryName = document.getElementById('dept-secretary-name');
            const detailSecretaryTitle = document.getElementById('dept-secretary-title');
            const detailExtraContent = document.getElementById('dept-extra-content');
            const detailWebsiteLink = document.getElementById('dept-website-link');
            const ministryBaseUrl = @json(route('ministry'));
            const departmentData = @json($departmentDataMap);

            const defaultPhoto = "{{ asset('department-head-placeholder.svg') }}";

            function resetDetail() {
                detailCard.classList.remove('show');
                ministryContent.classList.remove('hidden');
            }

            function showDepartmentDetail(departmentName) {
                const details = departmentData[departmentName] || {
                    intro: 'Department introduction will be shared soon.',
                    headName: 'Name to be updated',
                    positionTitle: 'Director',
                    photo: defaultPhoto,
                    pastorPhone: '',
                    pastorEmail: '',
                    secretaryName: 'Office Secretary',
                    secretaryTitle: 'Office Secretary',
                    secretaryPhoto: defaultPhoto,
                    website: ''
                };

                detailTitle.textContent = departmentName;
                detailBreadcrumbTitle.textContent = departmentName.toUpperCase();
                detailIntro.textContent = details.intro || 'Department introduction will be shared soon.';

                if (detailAboutLink) {
                    detailAboutLink.href = '{{ route('home') }}';
                }

                if (detailDepartmentsLink) {
                    const sectionName = sectionParam === 'association'
                        ? 'association'
                        : (sectionParam === 'families' ? 'families' : 'board');
                    detailDepartmentsLink.href = ministryBaseUrl + '?section=' + encodeURIComponent(sectionName);
                }

                detailPhotoName.textContent = 'HEAD: ' + (details.headName || 'Name to be updated');
                detailPhotoTitle.textContent = details.positionTitle || departmentName;
                detailPhoto.src = details.photo || defaultPhoto;
                detailPhoto.alt = departmentName + ' head photo';

                detailSecretaryName.textContent = details.secretaryName || 'Office Secretary';
                detailSecretaryTitle.textContent = details.secretaryTitle || 'Office Secretary';
                detailSecretaryPhoto.src = details.secretaryPhoto || defaultPhoto;
                detailSecretaryPhoto.alt = departmentName + ' office secretary photo';

                if (detailWebsiteLink) {
                    const hasWebsite = typeof details.website === 'string' && details.website.trim() !== '';
                    detailWebsiteLink.href = hasWebsite ? details.website : '#';
                    detailWebsiteLink.textContent = hasWebsite ? ('Explore: ' + details.website.replace(/^https?:\/\//, '')) : 'Website coming soon';
                    detailWebsiteLink.style.pointerEvents = hasWebsite ? 'auto' : 'none';
                    detailWebsiteLink.style.opacity = hasWebsite ? '1' : '0.6';
                }

                if (detailExtraContent) {
                    let extraHtml = (typeof details.extraContentHtml === 'string' && details.extraContentHtml.trim() !== '')
                        ? details.extraContentHtml
                        : '<div class="dept-extra-section"><h3>Department Information</h3><p>Detailed department information will be shared soon.</p></div>';

                    if (/pastor/i.test(departmentName)) {
                        const hasPhone = typeof details.pastorPhone === 'string' && details.pastorPhone.trim() !== '';
                        const hasEmail = typeof details.pastorEmail === 'string' && details.pastorEmail.trim() !== '';
                        const phoneValue = hasPhone ? details.pastorPhone.trim() : '';
                        const phoneHref = hasPhone ? ('tel:' + phoneValue.replace(/\s+/g, '')) : '#';
                        const emailValue = hasEmail ? details.pastorEmail.trim() : '';
                        const emailHref = hasEmail ? ('mailto:' + emailValue) : '#';

                        extraHtml += '<div class="dept-extra-section">'
                            + '<h3>Ask Pastor</h3>'
                            + '<p>Need prayer, counseling, or Bible guidance? Use the options below to reach the pastor.</p>'
                            + '<ul>'
                            + '<li><a href="#">Book a pastoral appointment</a></li>'
                            + '<li><a href="#">Submit your question</a></li>'
                            + (hasPhone ? ('<li><a href="' + phoneHref + '">Call pastor: ' + phoneValue + '</a></li>') : '<li>Pastor phone will be added soon.</li>')
                            + (hasEmail ? ('<li><a href="' + emailHref + '">Email pastor: ' + emailValue + '</a></li>') : '<li>Pastor email will be added soon.</li>')
                            + '</ul>'
                            + '</div>';
                    }

                    detailExtraContent.innerHTML = extraHtml;
                }

                ministryContent.classList.add('hidden');
                detailCard.classList.add('show');
            }

            const urlParams = new URLSearchParams(window.location.search);
            const sectionParam = urlParams.get('section');
            const initialTarget = sectionParam === 'association'
                ? 'association-panel'
                : (sectionParam === 'families' ? 'families-panel' : 'board-panel');

            panels.forEach(function (panel) {
                panel.classList.remove('active');
            });

            const initialPanel = document.getElementById(initialTarget);
            if (initialPanel) {
                initialPanel.classList.add('active');
            }

            deptLinks.forEach(function (link) {
                link.addEventListener('click', function (event) {
                    event.preventDefault();

                    const departmentName = link.getAttribute('data-dept');
                    const panel = link.closest('.ministry-panel');
                    const sectionName = panel && panel.id === 'association-panel'
                        ? 'association'
                        : (panel && panel.id === 'families-panel' ? 'families' : 'board');

                    window.location.href = ministryBaseUrl + '?section=' + encodeURIComponent(sectionName) + '&dept=' + encodeURIComponent(departmentName || '');
                });
            });

            const deptParam = urlParams.get('dept');
            if (deptParam) {
                showDepartmentDetail(deptParam);
            }

            const revealTargets = Array.from(document.querySelectorAll('#board-panel .ministry-scroll-reveal, #association-panel .ministry-scroll-reveal'));

            if ('IntersectionObserver' in window && revealTargets.length) {
                const revealObserver = new IntersectionObserver(function (entries, observer) {
                    entries.forEach(function (entry) {
                        if (!entry.isIntersecting) {
                            return;
                        }

                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    });
                }, {
                    threshold: 0.16,
                    rootMargin: '0px 0px -10% 0px'
                });

                revealTargets.forEach(function (target) {
                    if (target.closest('.ministry-panel.active')) {
                        revealObserver.observe(target);
                    }
                });
            } else {
                revealTargets.forEach(function (target) {
                    target.classList.add('is-visible');
                });
            }
        })();
    </script>
@endsection

