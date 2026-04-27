<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SDA CHURCH MUBS')</title>
    <link rel="icon" type="image/png" href="{{ asset('sda.png') }}">
    <link rel="shortcut icon" href="{{ asset('sda.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('sda.png') }}">
    <meta name="theme-color" content="#0f2b55">
    <style>
        :root {
            --primary: #102a52;
            --accent: #c19434;
            
            --background: #ffffff;
            --text: #18314f;
            --text-strong: #10233f;
            --text-muted: #314a69;
            --text-soft: #4e6483;
            --muted: #314a69;
            --surface: #ffffff;
            --border: #d9e0ec;
            --sda-right-bar-bg: #3e8391;
            --footer-bg: #0f2b55;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: var(--background);
            color: var(--text);
            line-height: 1.65;
            padding-right: 176px;
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
        }

        p,
        li,
        dd,
        dt,
        td,
        th,
        label,
        input,
        textarea,
        select,
        button {
            color: var(--text);
        }

        main {
            color: var(--text-muted);
        }

        main h1,
        main h2,
        main h3,
        main h4,
        main h5,
        main h6,
        main strong,
        main b,
        main dt,
        main th,
        main legend {
            color: var(--text-strong);
        }

        main p,
        main li,
        main dd,
        main td,
        main label,
        main input,
        main textarea,
        main select,
        main small {
            color: var(--text-muted);
        }

        main ::placeholder {
            color: var(--text-soft);
            opacity: 1;
        }

        .sda-right-bar {
            position: fixed;
            top: 0;
            right: 0;
            width: 160px;
            height: 100vh;
            background: var(--sda-right-bar-bg);
            border-left: 1px solid rgba(255, 255, 255, 0.18);
            z-index: 95;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding-top: 1rem;
            transition: background-color 0.85s ease, border-left-color 0.85s ease;
        }

        .sda-right-bar.is-background {
            background: var(--background);
            border-left-color: rgba(15, 43, 85, 0.22);
        }

        .sda-right-bar.is-footer {
            background: var(--footer-bg);
            border-left-color: rgba(255, 255, 255, 0.22);
        }

        .sda-right-bar:hover {
            filter: brightness(1.02);
        }

        .sda-right-bar-logo {
            width: 124px;
            max-width: 90%;
            height: auto;
            display: block;
            background: transparent;
            mix-blend-mode: normal;
            filter: brightness(0) saturate(100%) invert(1);
            transition: filter 0.3s ease, transform 0.2s ease;
        }

        .sda-right-bar.on-light .sda-right-bar-logo {
            filter: brightness(0) saturate(100%) invert(14%) sepia(24%) saturate(1115%) hue-rotate(179deg) brightness(94%) contrast(95%);
        }

        .sda-logo-btn:hover .sda-right-bar-logo {
            transform: scale(1.05);
            filter: brightness(0) saturate(100%) invert(1) drop-shadow(0 0 4px rgba(15, 43, 85, 0.3));
        }

        .sda-right-bar.on-light .sda-logo-btn:hover .sda-right-bar-logo {
            filter: brightness(0) saturate(100%) invert(14%) sepia(24%) saturate(1115%) hue-rotate(179deg) brightness(100%) contrast(100%) drop-shadow(0 0 4px rgba(15, 43, 85, 0.3));
        }

        .sda-logo-btn {
            border: 0;
            background: transparent;
            padding: 0.2rem;
            border-radius: 10px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .sda-logo-btn:hover {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 12px;
        }

        .sda-logo-text-panel {
            position: fixed;
            right: 190px;
            top: 5.4rem;
            width: min(440px, calc(100vw - 220px));
            background: #ffffff;
            border: 1px solid #c8d9ee;
            border-radius: 14px;
            box-shadow: 0 22px 48px rgba(15, 43, 85, 0.22);
            padding: 0.75rem;
            z-index: 210;
            display: block;
            max-height: calc(100vh - 6.6rem);
            overflow: hidden;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transform: translateY(16px) scale(0.98);
            transition: opacity 0.28s ease, transform 0.28s ease, visibility 0.28s ease;
        }

        .sda-logo-text-panel.show {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transform: translateY(0) scale(1);
        }

        .sda-logo-img-panel {
            position: relative;
            display: grid;
            gap: 0.55rem;
            justify-items: center;
            padding: 0.2rem;
            border-radius: 12px;
            background: radial-gradient(circle at 20% 15%, #f8fbff 0%, #eef4ff 70%, #e4eefc 100%);
            border: 1px solid #d2e1f4;
        }

        .sda-logo-main-img {
            width: 100%;
            max-height: calc(100vh - 11.8rem);
            object-fit: contain;
            display: block;
            border-radius: 11px;
            background: #ffffff;
            border: 1px solid #cdddf0;
            cursor: zoom-in;
            box-shadow: 0 14px 34px rgba(15, 43, 85, 0.18);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .sda-logo-main-img:hover {
            transform: scale(1.015);
            box-shadow: 0 18px 38px rgba(15, 43, 85, 0.24);
        }

        .sda-logo-lens {
            position: absolute;
            width: 138px;
            height: 138px;
            border-radius: 50%;
            border: 2px solid #1f4a8a;
            background-repeat: no-repeat;
            box-shadow: 0 10px 26px rgba(10, 31, 61, 0.28);
            pointer-events: none;
            opacity: 0;
            transform: translate(-50%, -50%) scale(0.88);
            transition: opacity 0.14s ease, transform 0.14s ease;
            z-index: 3;
        }

        .sda-logo-lens.show {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }

        .sda-logo-zoom-hint {
            margin: 0;
            color: #44648f;
            font-size: 0.76rem;
            font-weight: 600;
            letter-spacing: 0.01em;
        }

        .sda-logo-zoom-overlay {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(8, 21, 44, 0.9);
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity 0.22s ease, visibility 0.22s ease;
            cursor: zoom-out;
        }

        .sda-logo-zoom-overlay.active {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        .sda-logo-zoom-overlay img {
            width: auto;
            max-width: 92vw;
            max-height: 92vh;
            object-fit: contain;
            border-radius: 14px;
            box-shadow: 0 24px 70px rgba(0, 0, 0, 0.58);
            animation: sdaZoomIn 0.2s ease;
        }

        .sda-zoom-close {
            position: absolute;
            top: 0.9rem;
            right: 0.9rem;
            width: 2.2rem;
            height: 2.2rem;
            border: 1px solid rgba(255, 255, 255, 0.32);
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            font-size: 1.45rem;
            font-weight: 700;
            line-height: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        @keyframes sdaZoomIn {
            from {
                transform: scale(0.9);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .sda-symbol-section-title {
            margin: 0.35rem 0 0.45rem;
            color: var(--footer-bg);
            font-size: 1rem;
            font-weight: 700;
        }

        .sda-symbol-copy p {
            margin: 0 0 0.6rem;
            color: #2f3d57;
            font-size: 0.92rem;
            line-height: 1.58;
        }

        .sda-symbol-grid {
            margin-top: 0.8rem;
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 0.8rem;
        }

        .sda-symbol-card {
            background: #ffffff;
            border: 1px solid #d2e0f1;
            border-radius: 12px;
            padding: 0.7rem;
            box-shadow: 0 10px 22px rgba(16, 42, 82, 0.08);
        }

        .sda-symbol-card img {
            width: 100%;
            height: 132px;
            object-fit: contain;
            display: block;
            background: #f4f8ff;
            border-radius: 8px;
            padding: 0.4rem;
            border: 1px solid #d9e5f3;
        }

        .sda-symbol-card h4 {
            margin: 0.55rem 0 0.3rem;
            color: #153761;
            font-size: 0.98rem;
        }

        .sda-symbol-card p {
            margin: 0;
            color: #2f3d57;
            font-size: 0.87rem;
            line-height: 1.5;
        }

        @media (max-width: 1100px) and (min-width: 641px) {
            body {
                padding-right: 132px;
            }

            .sda-right-bar {
                width: 120px;
            }

            .sda-right-bar-logo {
                width: 96px;
            }

            .sda-logo-text-panel {
                right: 142px;
                width: min(440px, calc(100vw - 172px));
            }
        }

        @media (max-width: 900px) {
            body {
                padding-right: 0;
            }

            .sda-right-bar {
                width: 56px;
                height: 56px;
                top: auto;
                bottom: 5.3rem;
                left: 50%;
                right: auto;
                padding-top: 0;
                border-left: 0;
                border-radius: 999px;
                display: flex;
                align-items: center;
                justify-content: center;
                background: rgba(62, 131, 145, 0.95);
                box-shadow: 0 10px 20px rgba(10, 31, 61, 0.28);
                transform: translateX(-50%);
            }

            .sda-right-bar-logo {
                width: 34px;
                max-width: 34px;
            }

            .sda-logo-text-panel {
                right: auto;
                left: 50%;
                top: 4.7rem;
                width: min(360px, calc(100vw - 1.5rem));
                max-height: calc(100vh - 5.4rem);
                transform: translate(-50%, 16px) scale(0.98);
            }

            .sda-logo-text-panel.show {
                transform: translate(-50%, 0) scale(1);
            }

            .ask-pastor-float {
                right: 0.65rem;
                bottom: 0.7rem;
                padding: 0.4rem 0.78rem 0.4rem 0.4rem;
                gap: 0.42rem;
            }

            .ask-pastor-float.is-reading:not(.is-open):not(:hover):not(:focus-visible) {
                transform: translateX(calc(100% - 52px));
                opacity: 0.54;
                animation-play-state: paused;
            }

            .ask-pastor-avatar,
            .ask-pastor-avatar-fallback {
                width: 40px;
                height: 40px;
            }

            .ask-pastor-options {
                right: 0.65rem;
                bottom: 4.6rem;
                min-width: min(320px, calc(100vw - 1.3rem));
            }
        }

        @media (max-width: 640px) {
            body {
                overflow-x: hidden;
            }

            main {
                padding: 1rem 0 2rem;
            }

            .card,
            .hero {
                padding: 0.9rem;
            }

            .site-breadcrumb {
                margin: 0.45rem 0 0.75rem;
            }

            .sda-logo-text-panel {
                right: auto;
                left: 50%;
                width: min(340px, calc(100vw - 1.1rem));
                top: 4.8rem;
                padding: 0.62rem;
                transform: translate(-50%, 16px) scale(0.98);
            }

            .sda-logo-text-panel.show {
                transform: translate(-50%, 0) scale(1);
            }

            .sda-right-bar {
                width: 52px;
                height: 52px;
                left: 50%;
                right: auto;
                bottom: 5.15rem;
                transform: translateX(-50%);
            }

            .sda-right-bar-logo {
                width: 30px;
                max-width: 30px;
            }

            .sda-logo-main-img {
                max-height: calc(100vh - 10.4rem);
            }

            .sda-logo-lens {
                display: none;
            }

            .sda-symbol-grid {
                grid-template-columns: 1fr;
            }

            .sda-symbol-card img {
                height: 116px;
            }

            .ask-pastor-text {
                display: none;
            }

            .ask-pastor-float {
                padding-right: 0.42rem;
            }

            .ask-pastor-options {
                min-width: min(320px, calc(100vw - 1.1rem));
            }
        }

        @media (max-width: 1180px) and (min-width: 641px) {
            .sda-symbol-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        .container {
            width: min(1080px, 92%);
            margin: 0 auto;
        }

        .site-header {
            color: #0f2b55;
            background: whitesmoke;
            padding: 0.9rem 0;
            border-bottom: none;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .site-breadcrumb {
            background: transparent;
            border: 0;
            border-radius: 0;
            padding: 0;
            margin: 0.75rem 0 0.9rem;
        }

        .site-breadcrumb-list {
            margin: 0;
            padding: 0;
            list-style: none;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.38rem;
        }

        .site-breadcrumb-item {
            display: inline-flex;
            align-items: center;
            font-size: 0.86rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: var(--text-soft);
            font-weight: 700;
        }

        .site-breadcrumb-item a {
            color: var(--text-strong);
            text-decoration: underline;
            text-underline-offset: 2px;
        }

        .site-breadcrumb-item.current {
            color: var(--text-soft);
            text-decoration: none;
        }

        .site-breadcrumb-sep {
            color: #c19434;
            font-weight: 900;
            margin: 0 0.08rem;
        }

        .header-wrap {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto auto;
            align-items: center;
            gap: 1rem;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .brand img {
            width: 64px;
            height: 64px;
            object-fit: contain;
            display: block;
            mix-blend-mode: normal;
            filter: brightness(0) saturate(100%);
        }

        .brand-title {
            display: block;
            line-height: 1.2;
            font-size: 0.98rem;
            letter-spacing: 0.35px;
            color: var(--text-strong);
        }

        .header-start {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            min-width: 0;
        }

        .header-social {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            justify-self: end;
            flex-wrap: wrap;
        }

        .header-social-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.3rem;
            height: 34px;
            width: 34px;
            padding: 0;
            border-radius: 999px;
            text-decoration: none;
            border: 0;
            color: #0f2b55;
            font-size: 0.7rem;
            font-weight: 800;
            line-height: 1;
            background: transparent;
            transition: none;
        }

        .header-social-icon {
            width: 20px;
            height: 20px;
            border-radius: 999px;
            border: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            line-height: 1;
        }

        .header-social-icon svg {
            width: 100%;
            height: 100%;
            display: block;
            fill: currentColor;
        }

        .header-social-icon img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
            border-radius: 999px;
        }

        .header-social-name {
            display: none;
        }

        .header-social-link:hover .header-social-name,
        .header-social-link:focus-visible .header-social-name {
            max-width: 140px;
            opacity: 1;
        }

        .header-social-link:hover,
        .header-social-link:focus-visible {
            background: transparent;
            color: inherit;
            outline: none;
        }

        .header-social-link.facebook,
        .header-social-link.facebook:hover,
        .header-social-link.facebook:focus-visible {
            color: #1877f2;
            background: transparent;
            border-color: transparent;
        }

        .header-social-link.twitter,
        .header-social-link.twitter:hover,
        .header-social-link.twitter:focus-visible {
            color: #000000;
            background: transparent;
            border-color: transparent;
        }

        .header-social-link.tiktok,
        .header-social-link.tiktok:hover,
        .header-social-link.tiktok:focus-visible {
            color: #010101;
            background: transparent;
            border-color: transparent;
        }

        .header-social-link.youtube,
        .header-social-link.youtube:hover,
        .header-social-link.youtube:focus-visible {
            color: #ff0000;
            background: transparent;
            border-color: transparent;
        }

        .header-social-text {
            font-size: 0.76rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            font-weight: 800;
            color: var(--text-strong);
            white-space: nowrap;
        }

        .site-nav {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            grid-column: 1 / -1;
            align-items: center;
        }

        .site-nav-links {
            display: inline-flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.5rem;
            min-width: 0;
        }

        .site-nav-toggle {
            display: none;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            border: 1px solid #d7e1ef;
            border-radius: 10px;
            background: #ffffff;
            color: #0f2b55;
            padding: 0.5rem 0.72rem;
            font-size: 0.86rem;
            font-weight: 800;
            line-height: 1;
            cursor: pointer;
            justify-self: end;
        }

        .site-nav-toggle:focus-visible {
            outline: 2px solid #b6cae8;
            outline-offset: 2px;
        }

        .site-nav-toggle-icon {
            font-size: 0.98rem;
            line-height: 1;
        }

        .header-quick-actions {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            margin-top: 0;
            margin-left: auto;
            position: relative;
        }

        .quick-action-control {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .quick-action-btn,
        .quick-action-link,
        .quick-user-badge {
            border: 0;
            border-radius: 999px;
            min-width: 36px;
            height: 36px;
            padding: 0 0.6rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #0f2b55;
            color: #ffffff;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.2s ease;
            text-decoration: none;
            font-weight: 700;
            letter-spacing: 0.02em;
            font-size: 0.9rem;
        }

        .quick-action-btn:hover,
        .quick-action-btn:focus-visible,
        .quick-action-link:hover,
        .quick-action-link:focus-visible,
        .quick-user-badge:hover,
        .quick-user-badge:focus-visible {
            background: #1f4a8a;
            transform: translateY(-1px);
            outline: none;
        }

        .quick-action-icon {
            width: 16px;
            height: 16px;
            display: inline-block;
        }

        .notification-btn .quick-action-icon {
            width: 21px;
            height: 21px;
        }

        .quick-chat-link {
            background: #ffffff;
            color: #0f2b55;
            border: 1px solid #0f2b55;
        }

        .quick-chat-link:hover,
        .quick-chat-link:focus-visible {
            background: #eff4fc;
            color: #0f2b55;
        }

        .quick-user-badge {
            background: #e36d2f;
            min-width: 38px;
            padding: 0;
            font-size: 0.88rem;
        }

        .quick-action-badge {
            position: absolute;
            top: -7px;
            right: -8px;
            min-width: 18px;
            height: 18px;
            padding: 0 4px;
            border-radius: 999px;
            background: #d92020;
            border: 2px solid #f5f5f5;
            color: #ffffff;
            font-size: 0.62rem;
            font-weight: 800;
            line-height: 1;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .quick-action-badge.is-visible {
            display: inline-flex;
        }

        .quick-action-control[data-tooltip]::after {
            content: attr(data-tooltip);
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            background: #102a52;
            color: #ffffff;
            font-size: 0.73rem;
            letter-spacing: 0.02em;
            border-radius: 6px;
            padding: 0.3rem 0.45rem;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-3px);
            transition: opacity 0.18s ease, transform 0.18s ease;
            pointer-events: none;
            z-index: 240;
        }

        .quick-action-control:hover::after,
        .quick-action-control:focus-within::after {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .header-notification-menu {
            position: absolute;
            top: calc(100% + 10px);
            right: 82px;
            width: min(320px, 84vw);
            background: #ffffff;
            border: 1px solid #cfd9ea;
            border-radius: 10px;
            box-shadow: 0 14px 30px rgba(18, 31, 53, 0.2);
            padding: 0.6rem;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transform: translateY(6px);
            transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s ease;
            z-index: 230;
        }

        .header-notification-menu.is-open {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transform: translateY(0);
        }

        .notification-menu-title {
            margin: 0 0 0.45rem;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: #2e4668;
            font-weight: 800;
        }

        .notification-menu-list {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            gap: 0.28rem;
            max-height: min(55vh, 420px);
            overflow-y: auto;
            padding-right: 0.18rem;
        }

        .notification-menu-list::-webkit-scrollbar {
            width: 7px;
        }

        .notification-menu-list::-webkit-scrollbar-thumb {
            background: #c4d3e8;
            border-radius: 999px;
        }

        .notification-menu-list a {
            display: block;
            text-decoration: none;
            color: #0f2b55;
            font-size: 0.82rem;
            line-height: 1.32;
            border: 1px solid #dbe4f1;
            border-radius: 7px;
            padding: 0.45rem;
        }

        .notification-menu-list a:hover,
        .notification-menu-list a:focus-visible {
            background: #edf3ff;
            border-color: #c9d7f0;
            outline: none;
        }

        .notification-item-title {
            display: block;
            font-weight: 700;
        }

        .notification-item-meta {
            display: block;
            margin-top: 0.12rem;
            color: #4b5f7b;
            font-size: 0.74rem;
            line-height: 1.3;
        }

        .notification-menu-empty {
            margin: 0;
            font-size: 0.82rem;
            color: #4a5c76;
        }

        .notification-menu-link {
            display: inline-block;
            margin-top: 0.55rem;
            font-size: 0.8rem;
            font-weight: 700;
            color: #1f4a8a;
            text-decoration: none;
        }

        .notification-menu-link:hover,
        .notification-menu-link:focus-visible {
            text-decoration: underline;
            outline: none;
        }

        .notification-menu-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.55rem;
            margin-top: 0.55rem;
        }

        .notification-menu-clear {
            border: 1px solid #cfdbef;
            border-radius: 6px;
            background: #f3f7ff;
            color: #1f4a8a;
            padding: 0.28rem 0.5rem;
            font-size: 0.76rem;
            font-weight: 700;
            cursor: pointer;
        }

        .notification-menu-clear:hover,
        .notification-menu-clear:focus-visible {
            background: #e7efff;
            border-color: #b8cae7;
            outline: none;
        }

        .site-nav a {
            color: #0f2b55;
            text-decoration: none;
            padding: 0.4rem 0.65rem;
            /* border-radius: 6px; */
            font-size: 0.95rem;
            border: 1px solid transparent;
        }

        .site-nav button {
            color: #0f2b55;
            text-decoration: none;
            padding: 0.4rem 0.65rem;
            border-radius: 6px;
            font-size: 0.95rem;
            border: 1px solid transparent;
            background: transparent;
            font-family: inherit;
            line-height: 1.2;
            cursor: pointer;
        }

        .site-nav a.active,
        .site-nav button.active {
            background: transparent;
            border-color: transparent;
        }

        .site-nav button:focus-visible {
            background: #edf2fb;
            border-color: #d5dfef;
        }

        .nav-item-dropdown {
            position: relative;
        }

        .nav-dropdown-trigger {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .nav-dropdown-caret {
            font-size: 0.72rem;
            opacity: 0.85;
        }

        .nav-dropdown-panel,
        .nav-submenu {
            list-style: none;
            margin: 0;
            padding: 0.35rem;
            background: #f8fbff;
            border: 0;
            border-left: 3px solid var(--sda-right-bar-bg);
            border-radius: 0;
            box-shadow: none;
            min-width: 250px;
            display: block;
            position: absolute;
            z-index: 180;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transform: translateY(6px);
            transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s ease;
        }

        .nav-dropdown-panel {
            top: 100%;
            left: 0;
            padding: 0.45rem;
            overflow: visible;
            max-height: 100vh;
        }

        .nav-item-dropdown:hover > .nav-dropdown-panel,
        .nav-item-dropdown:focus-within > .nav-dropdown-panel,
        .nav-item-dropdown.is-open > .nav-dropdown-panel {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transform: translateY(0);
        }

        .nav-dropdown-head {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--muted);
            font-weight: 700;
            padding: 0.35rem 0.55rem 0.45rem;
            /* border-bottom: 1px solid #e8eef8; */
            margin-bottom: 0.2rem;
        }

        .nav-dropdown-item {
            position: relative;
        }

        .nav-dropdown-item + .nav-dropdown-item {
            margin-top: 0.1rem;
        }

        .nav-dropdown-main,
        .nav-submenu-link {
            width: 100%;
            color: var(--sda-right-bar-bg);
            text-decoration: none;
            font-size: 0.88rem;
            border-radius: 0;
            border: 0;
            padding: 0.5rem 0.65rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
            background: transparent;
        }

        .nav-dropdown-main {
            font-weight: 600;
            cursor: pointer;
            font: inherit;
        }

        .nav-dropdown-main.is-active {
            background: var(--sda-right-bar-bg);
            color: #ffffff;
        }

        .nav-dropdown-main:hover,
        .nav-dropdown-main:focus-visible,
        .nav-submenu-link:hover,
        .nav-submenu-link:focus-visible {
            background: var(--sda-right-bar-bg);
            color: #ffffff;
            outline: none;
        }

        .nav-dropdown-main.is-active .nav-submenu-caret,
        .nav-dropdown-main:hover .nav-submenu-caret,
        .nav-dropdown-main:focus-visible .nav-submenu-caret {
            color: #ffffff;
        }

        .nav-submenu {
            position: static;
            margin-top: 0.2rem;
            min-width: 100%;
            border-radius: 0;
            box-shadow: none;
            border: 0;
            border-left: 3px solid var(--sda-right-bar-bg);
            background: #ffffff;
            transform: none;
            max-height: 0;
            overflow: hidden;
            padding: 0;
            transition: max-height 0.22s ease, opacity 0.2s ease, visibility 0.2s ease;
        }

        .nav-dropdown-item:hover > .nav-submenu,
        .nav-dropdown-item:focus-within > .nav-submenu,
        .nav-dropdown-item.is-open > .nav-submenu {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            max-height: 220px;
            padding: 0.3rem;
        }

        .nav-submenu-caret {
            color: var(--muted);
            font-size: 0.8rem;
            transition: transform 0.2s ease;
        }

        .nav-dropdown-item.is-open .nav-submenu-caret {
            transform: rotate(90deg);
        }

        @media (max-width: 860px) {
            .nav-dropdown-panel,
            .nav-submenu {
                min-width: 220px;
            }

            .nav-submenu {
                min-width: 100%;
            }
        }

        @media (max-width: 1024px) {
            .header-wrap {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }

            .header-social {
                justify-self: start;
                flex-wrap: wrap;
                gap: 0.4rem;
            }

            .site-nav {
                gap: 0.35rem;
            }

            .header-quick-actions {
                margin-left: 0;
            }

            .site-nav a {
                padding: 0.34rem 0.52rem;
                font-size: 0.88rem;
            }
        }

        @media (max-width: 900px) {
            .site-header {
                padding: 0.62rem 0;
                background: var(--sda-right-bar-bg);
                box-shadow: 0 8px 16px rgba(15, 43, 85, 0.2);
            }

            .header-wrap {
                display: grid;
                grid-template-columns: minmax(0, 1fr) auto;
                grid-template-areas:
                    'start start'
                    'social social'
                    'menu quick'
                    'nav nav';
                gap: 0.45rem;
                align-items: center;
            }

            .header-start {
                grid-area: start;
                width: 100%;
                justify-content: center;
            }

            .brand {
                flex-direction: column;
                gap: 0.3rem;
                width: 100%;
                align-items: center;
                justify-content: center;
                text-align: center;
            }

            .brand img {
                width: 46px;
                height: 46px;
                flex-shrink: 0;
            }

            .brand-title {
                font-size: 0.82rem;
                line-height: 1.25;
                letter-spacing: 0.2px;
                max-width: 100%;
                color: #ffffff;
                text-shadow: 0 1px 2px rgba(0, 0, 0, 0.22);
            }

            .header-social {
                grid-area: social;
                width: 100%;
                display: inline-flex;
                gap: 0.2rem;
                align-items: center;
                justify-content: center;
                flex-wrap: nowrap;
                background: transparent;
                border: 0;
                border-radius: 10px;
                padding: 0;
            }

            .header-social-link {
                height: 40px;
                width: 40px;
                justify-content: center;
                padding: 0;
                border-radius: 8px;
                font-size: 0.68rem;
                border: 0;
                background: transparent;
            }

            .header-social-icon {
                width: 22px;
                height: 22px;
                padding: 0;
            }

            .header-social-name {
                display: none;
            }

            .header-social-text {
                display: none;
            }

            .header-social-link.facebook,
            .header-social-link.facebook:hover,
            .header-social-link.facebook:focus-visible {
                color: #1877f2;
            }

            .header-social-link.twitter,
            .header-social-link.twitter:hover,
            .header-social-link.twitter:focus-visible {
                color: #111111;
            }

            .header-social-link.tiktok,
            .header-social-link.tiktok:hover,
            .header-social-link.tiktok:focus-visible {
                color: #ffffff;
            }

            .header-social-link.youtube,
            .header-social-link.youtube:hover,
            .header-social-link.youtube:focus-visible {
                color: #ff3030;
            }

            .site-nav-toggle {
                grid-area: menu;
                display: inline-flex;
                margin-top: 0;
                width: auto;
                justify-self: start;
            }

            .site-nav {
                grid-area: nav;
                display: block;
                width: 100%;
                margin-top: 0;
                padding: 0;
                border: 0;
                border-radius: 0;
                background: transparent;
                box-shadow: none;
                position: relative;
                overflow: visible;
                z-index: 0;
            }

            .site-nav-links {
                grid-area: nav;
                display: none;
            }

            .site-nav-links > a,
            .site-nav-links > .nav-item-dropdown {
                display: none;
            }

            .site-nav.is-open {
                display: block;
            }

            .site-nav.is-open .site-nav-links {
                display: grid;
                grid-area: nav;
                position: static;
                width: 100%;
                margin-top: 0.35rem;
                padding: 0.55rem;
                border: 1px solid rgba(255, 255, 255, 0.35);
                border-radius: 12px;
                background: rgba(255, 255, 255, 0.16);
                box-shadow: 0 8px 18px rgba(16, 42, 82, 0.14);
                z-index: 0;
            }

            .site-nav.is-open .site-nav-links > a,
            .site-nav.is-open .site-nav-links > .nav-item-dropdown {
                display: block;
            }

            .site-nav-links > a,
            .site-nav-links > .nav-item-dropdown,
            .header-quick-actions {
                min-width: 0;
            }

            .site-nav a,
            .site-nav button {
                width: 100%;
                justify-content: center;
                border: 1px solid #d7e1ef;
                border-radius: 8px;
                background: #ffffff;
                padding: 0.5rem 0.55rem;
                font-size: 0.84rem;
                font-weight: 700;
            }

            .nav-item-dropdown {
                position: relative;
            }

            .site-nav.is-open .nav-item-dropdown > .nav-dropdown-panel {
                position: static;
                left: auto;
                right: auto;
                top: auto;
                min-width: 0;
                margin-top: 0.3rem;
                max-height: 0;
                overflow: hidden;
                opacity: 1;
                visibility: visible;
                pointer-events: auto;
                transform: none;
                padding: 0;
            }

            .site-nav.is-open .nav-item-dropdown.is-open > .nav-dropdown-panel {
                max-height: 65vh;
                overflow-y: auto;
                padding: 0.45rem;
            }

            .nav-dropdown-main,
            .nav-submenu-link {
                white-space: normal;
                text-align: left;
                justify-content: space-between;
            }

            .header-quick-actions {
                grid-area: quick;
                justify-content: flex-end;
                margin-top: 0;
                margin-left: 0;
                margin-right: 0;
                width: auto;
                justify-self: end;
            }

            .header-notification-menu {
                right: 0;
                top: calc(100% + 8px);
                width: min(320px, calc(100vw - 1.3rem));
            }
        }

        @media (max-width: 560px) {
            .site-nav-links {
                grid-template-columns: 1fr;
            }

            .site-nav.is-open .site-nav-links {
                width: 100%;
            }

            .brand img {
                width: 40px;
                height: 40px;
            }

            .brand {
                gap: 0.22rem;
            }

            .brand-title {
                font-size: 0.75rem;
                line-height: 1.22;
                text-align: center;
            }

            .site-nav-toggle {
                width: auto;
                justify-content: center;
            }

            .nav-dropdown-panel,
            .nav-submenu {
                max-height: 60vh;
                overflow-y: auto;
                overflow-x: hidden;
                min-width: 200px;
            }

            .site-nav.is-open .nav-item-dropdown.is-open > .nav-dropdown-panel {
                max-height: 60vh;
            }

            .sda-right-bar {
                display: none;
            }

            .header-social-text {
                display: block;
                font-size: 0.6rem;
                letter-spacing: 0.05em;
                flex-basis: 100%;
                text-align: center;
            }

            .header-social {
                display: flex;
                justify-content: center;
                align-items: center;
                flex-wrap: wrap;
                gap: 0.16rem;
                padding: 0;
                width: 100%;
            }

            .header-quick-actions {
                justify-content: flex-start;
                width: auto;
            }

            .header-social-link {
                width: 40px;
                min-width: 40px;
                height: 40px;
                padding: 0;
                border: 0;
                border-radius: 999px;
                background: transparent;
            }

            .header-social-icon {
                width: 22px;
                height: 22px;
                border: 0;
                border-radius: 999px;
                padding: 0;
            }

            .quick-action-control[data-tooltip]::after {
                display: none;
                content: none;
            }

            .quick-action-control:hover::after,
            .quick-action-control:focus-within::after {
                opacity: 0;
                visibility: hidden;
            }

            .notification-menu-list a:hover,
            .notification-menu-list a:focus-visible {
                background: #ffffff;
                border-color: #dbe4f1;
                outline: none;
            }

            .notification-btn:hover,
            .notification-btn:focus-visible {
                background: #0f2b55;
                transform: none;
            }
        }

        main {
            padding: 2rem 0 2.4rem;
        }

        .hero,
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1.2rem;
        }

        .hero {
            margin-bottom: 1rem;
            border-left: 5px solid var(--accent);
        }

        h1, h2 {
            margin-top: 0;
            color: var(--primary);
        }

        p.lead {
            font-size: 1.05rem;
            color: var(--muted);
            margin-bottom: 0;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 0.9rem;
            margin-top: 1rem;
        }

        ul.clean {
            margin: 0;
            padding-left: 1.2rem;
        }

        .meta {
            color: var(--muted);
            font-size: 0.95rem;
        }

        .site-footer {
            background: #00163a;
            color: #ffffff;
            padding: 1.9rem 0 1.25rem;
            font-size: 0.9rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: minmax(420px, 1fr) minmax(120px, 170px) minmax(120px, 170px);
            gap: 2.2rem;
            align-items: start;
        }

        .footer-note {
            margin: 0;
            font-size: 2rem;
            line-height: 1.28;
            color: #f5f9ff;
            max-width: 700px;
        }

        .footer-column-title {
            display: none;
        }

        .footer-links {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 0.43rem;
            display: flex;
            align-items: center;
            gap: 0.45rem;
            letter-spacing: 0.55px;
            text-transform: uppercase;
            font-size: 0.79rem;
            font-weight: 700;
            color: #dfe8f8;
        }

        .footer-links a {
            color: #f3f7ff;
            text-decoration: none;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }

        .footer-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 0.72rem;
            color: #d8e6fb;
            opacity: 0.92;
            font-size: 0.72rem;
            line-height: 1;
        }

        .footer-bottom {
            margin-top: 1.2rem;
            padding-top: 0;
            color: #d7e3f6;
            font-size: 0.75rem;
            font-weight: 600;
        }

        @media (max-width: 900px) {
            .footer-grid {
                grid-template-columns: 1fr;
                gap: 0.9rem;
            }

            .footer-note {
                font-size: 1.3rem;
            }
        }

        .ask-pastor-float {
            position: fixed;
            right: 1rem;
            bottom: 1rem;
            z-index: 140;
            display: inline-flex;
            align-items: center;
            gap: 0.58rem;
            padding: 0.46rem 0.95rem 0.46rem 0.46rem;
            border-radius: 999px;
            background: linear-gradient(140deg, #0f2b55 0%, #1f4a8a 55%, #2a5ea4 100%);
            color: #ffffff;
            text-decoration: none;
            font-weight: 700;
            box-shadow: 0 14px 30px rgba(11, 33, 66, 0.34);
            border: 1px solid rgba(255, 255, 255, 0.34);
            cursor: pointer;
            transition: transform 0.28s ease, box-shadow 0.22s ease, filter 0.22s ease, opacity 0.28s ease;
            animation: askPastorBounce 3.4s ease-in-out infinite;
        }

        .ask-pastor-float:hover {
            transform: translateY(-2px) scale(1.01);
            box-shadow: 0 18px 34px rgba(11, 33, 66, 0.38);
            filter: saturate(1.06);
            animation-play-state: paused;
        }

        .ask-pastor-float:focus-visible {
            outline: 3px solid rgba(31, 74, 138, 0.28);
            outline-offset: 3px;
            animation-play-state: paused;
        }

        .ask-pastor-float.is-open {
            animation-play-state: paused;
        }

        @keyframes askPastorBounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-4px);
            }
        }

        .ask-pastor-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.92);
            flex-shrink: 0;
        }

        .ask-pastor-avatar-fallback {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.9);
            background: rgba(255, 255, 255, 0.16);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            font-weight: 800;
            flex-shrink: 0;
        }

        .ask-pastor-text {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            line-height: 1.1;
        }

        .ask-pastor-title {
            font-size: 0.9rem;
            letter-spacing: 0.22px;
        }

        .ask-pastor-name {
            font-size: 0.77rem;
            color: rgba(255, 255, 255, 0.88);
            font-weight: 600;
        }

        .ask-pastor-options {
            position: fixed;
            right: 1rem;
            bottom: 5rem;
            z-index: 141;
            background: linear-gradient(180deg, #f6f9ff 0%, #edf3ff 100%);
            border: 1px solid #d0dff5;
            border-radius: 18px;
            box-shadow: 0 18px 42px rgba(11, 33, 66, 0.24);
            padding: 0.72rem;
            min-width: 330px;
            max-width: min(380px, calc(100vw - 1.6rem));
            opacity: 0;
            transform: translateY(10px) scale(0.96);
            pointer-events: none;
            transition: opacity 0.2s ease, transform 0.2s ease;
        }

        .ask-pastor-options.show {
            display: grid;
            gap: 0.35rem;
            opacity: 1;
            transform: translateY(0) scale(1);
            pointer-events: auto;
        }

        .ask-pastor-card-head {
            display: flex;
            align-items: center;
            gap: 0.78rem;
            padding: 0.68rem;
            border-radius: 14px;
            background: linear-gradient(140deg, #ffffff 0%, #eaf2ff 100%);
            border: 1px solid #cdddf6;
            margin-bottom: 0.42rem;
        }

        .ask-pastor-head-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ffffff;
            box-shadow: 0 4px 12px rgba(14, 44, 87, 0.18);
        }

        .ask-pastor-head-fallback {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #1f4a8a;
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            box-shadow: 0 4px 12px rgba(14, 44, 87, 0.18);
        }

        .ask-pastor-head-title {
            margin: 0;
            color: #2c598f;
            font-size: 0.8rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .ask-pastor-head-name {
            margin: 0;
            color: #112e58;
            font-size: 1rem;
            font-weight: 800;
        }

        .ask-pastor-option {
            display: flex;
            align-items: center;
            gap: 0.72rem;
            text-decoration: none;
            color: #173a69;
            background: #ffffff;
            border-radius: 12px;
            padding: 0.7rem;
            font-weight: 600;
            border: 1px solid #cbdaf2;
            transition: transform 0.2s ease, background 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .ask-pastor-option:hover {
            background: #f1f6ff;
            border-color: #adc7ee;
            transform: translateX(2px);
            box-shadow: 0 8px 20px rgba(31, 74, 138, 0.15);
        }

        .ask-pastor-option-icon {
            width: 34px;
            height: 34px;
            border-radius: 999px;
            background: linear-gradient(145deg, #f8fbff, #eaf2ff);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
            border: 1px solid #ccdaf1;
        }

        .ask-pastor-option-text {
            display: flex;
            flex-direction: column;
            line-height: 1.18;
        }

        .ask-pastor-option-label {
            color: #1b426f;
            font-size: 0.95rem;
            font-weight: 800;
        }

        .ask-pastor-option-hint {
            color: #456083;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .ask-pastor-option.disabled {
            color: #6c778d;
            background: #f2f4f8;
            border-color: #e4e8ef;
            pointer-events: none;
        }

        .ask-pastor-option.disabled .ask-pastor-option-label,
        .ask-pastor-option.disabled .ask-pastor-option-hint {
            color: #7c8699;
        }

        .ask-pastor-step {
            display: grid;
            gap: 0.35rem;
        }

        .ask-pastor-step.hidden {
            display: none;
        }

        .ask-pastor-back {
            border: 1px solid #c8d9f4;
            background: #ffffff;
            color: #1f4a8a;
            border-radius: 10px;
            padding: 0.52rem 0.68rem;
            font-weight: 700;
            text-align: left;
            cursor: pointer;
        }

        .ask-pastor-back:hover {
            background: #e6efff;
        }

        .ask-pastor-step-title {
            margin: 0;
            color: #173c6d;
            font-size: 0.84rem;
            font-weight: 800;
            padding: 0 0.2rem;
        }

        .cookie-consent {
            position: fixed;
            left: 1rem;
            bottom: 1rem;
            width: min(420px, calc(100vw - 2rem));
            background:
                linear-gradient(155deg, rgba(15, 43, 85, 0.98) 0%, rgba(26, 76, 141, 0.98) 100%);
            color: #f6f9ff;
            border: 1px solid rgba(255, 209, 102, 0.45);
            border-radius: 16px;
            box-shadow: 0 16px 40px rgba(9, 22, 45, 0.42);
            padding: 1rem;
            z-index: 320;
            display: none;
        }

        .cookie-consent.show {
            display: block;
        }

        .cookie-consent-title {
            margin: 0 0 0.4rem;
            color: #ffffff;
            font-size: 1.02rem;
            font-weight: 800;
            letter-spacing: 0.01em;
        }

        .cookie-consent-copy {
            margin: 0;
            color: #e7eefb;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .cookie-consent-actions {
            margin-top: 1rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.65rem;
        }

        .cookie-btn {
            border: 1px solid transparent;
            border-radius: 10px;
            padding: 0.62rem 0.75rem;
            font-size: 0.95rem;
            font-weight: 800;
            cursor: pointer;
            transition: transform 0.18s ease, box-shadow 0.2s ease, opacity 0.2s ease;
        }

        .cookie-btn:hover,
        .cookie-btn:focus-visible {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(9, 22, 45, 0.28);
        }

        .cookie-btn.reject {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.28);
            color: #eff4ff;
        }

        .cookie-btn.accept {
            background: #ffd166;
            border-color: #ffd166;
            color: #0d2447;
        }

        .cookie-settings-link {
            margin-top: 0.85rem;
            width: 100%;
            background: transparent;
            border: 0;
            color: #ffe39a;
            text-decoration: underline;
            text-underline-offset: 2px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
        }

        .cookie-settings-modal {
            position: fixed;
            inset: 0;
            background: rgba(8, 14, 24, 0.68);
            z-index: 330;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .cookie-settings-modal.show {
            display: flex;
        }

        .cookie-settings-dialog {
            width: min(560px, 96vw);
            background: #f5f9ff;
            border: 1px solid #c8d7f0;
            border-radius: 14px;
            box-shadow: 0 18px 40px rgba(9, 23, 47, 0.35);
            overflow: hidden;
        }

        .cookie-settings-head {
            padding: 0.95rem 1rem 0.6rem;
            border-bottom: 1px solid #d8e3f3;
            background: linear-gradient(180deg, #edf4ff 0%, #f6faff 100%);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.6rem;
        }

        .cookie-settings-head h3 {
            margin: 0;
            color: #0f2b55;
            font-size: 1rem;
        }

        .cookie-settings-close {
            border: 1px solid #b9cae6;
            background: #ffffff;
            border-radius: 8px;
            color: #2d3e5c;
            font-weight: 800;
            cursor: pointer;
            width: 34px;
            height: 34px;
        }

        .cookie-settings-body {
            padding: 0.85rem 1rem;
            display: grid;
            gap: 0.7rem;
        }

        .cookie-settings-intro {
            margin: 0;
            color: #435572;
            font-size: 0.93rem;
            line-height: 1.5;
        }

        .cookie-settings-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 0.7rem;
            border: 1px solid #d5e2f6;
            border-radius: 10px;
            padding: 0.7rem;
            background: #ffffff;
        }

        .cookie-settings-label {
            margin: 0;
            color: #1b355a;
            font-weight: 800;
            font-size: 0.92rem;
        }

        .cookie-settings-help {
            margin: 0.24rem 0 0;
            color: #526686;
            font-size: 0.84rem;
            line-height: 1.45;
        }

        .cookie-settings-toggle {
            margin-top: 0.2rem;
            transform: scale(1.08);
        }

        .cookie-settings-footer {
            padding: 0.75rem 1rem 0.95rem;
            border-top: 1px solid #d8e3f3;
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 0.55rem;
            background: #f8fbff;
        }

        .cookie-settings-btn {
            border: 1px solid #c7d8ef;
            border-radius: 9px;
            padding: 0.5rem 0.8rem;
            font-weight: 800;
            cursor: pointer;
            background: #ffffff;
            color: #1c3e70;
        }

        .cookie-settings-btn.save {
            background: #0f2b55;
            border-color: #0f2b55;
            color: #ffffff;
        }

        .footer-cookie-btn {
            border: 0;
            background: transparent;
            color: #f8fbff;
            text-decoration: underline;
            text-underline-offset: 2px;
            cursor: pointer;
            font: inherit;
            padding: 0;
        }

        @media (max-width: 640px) {
            .ask-pastor-options {
                min-width: 255px;
                right: 0.7rem;
            }

            .ask-pastor-float {
                right: 0.7rem;
                bottom: 0.7rem;
            }

            .ask-pastor-float.is-reading:not(.is-open):not(:hover):not(:focus-visible) {
                transform: translateX(calc(100% - 48px));
                opacity: 0.52;
                animation-play-state: paused;
            }

            .cookie-consent {
                left: 0.6rem;
                right: 0.6rem;
                bottom: 0.6rem;
                width: auto;
                padding: 0.85rem;
            }

            .cookie-consent-actions {
                grid-template-columns: 1fr;
            }
        }

        /* Global mobile readability and overflow protection for all pages */
        @media (max-width: 900px) {
            html,
            body {
                overflow-x: hidden;
            }

            .container {
                width: min(1080px, 96%);
            }

            main {
                padding: 1rem 0 1.7rem;
            }

            main h1 {
                font-size: clamp(1.2rem, 5.2vw, 1.7rem);
                line-height: 1.25;
            }

            main h2 {
                font-size: clamp(1.05rem, 4.4vw, 1.4rem);
                line-height: 1.28;
            }

            main p,
            main li,
            main dd,
            main td,
            main label,
            main input,
            main textarea,
            main select,
            main button {
                font-size: 0.95rem;
                line-height: 1.5;
            }

            main img,
            main video,
            main canvas,
            main svg {
                max-width: 100%;
                height: auto;
            }

            main iframe {
                width: 100%;
                max-width: 100%;
                min-height: 260px;
            }

            main table {
                width: 100%;
                display: block;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            main table[style*="min-width"] {
                min-width: 0 !important;
            }

            main table thead,
            main table tbody,
            main table tr,
            main table th,
            main table td {
                white-space: normal;
            }

            input,
            select,
            textarea,
            button {
                min-height: 42px;
            }
        }

        @media (max-width: 560px) {
            .container {
                width: min(1080px, 97%);
            }

            main {
                padding: 0.85rem 0 1.45rem;
            }

            .hero,
            .card {
                padding: 0.8rem;
                border-radius: 10px;
            }

            .site-breadcrumb-item {
                font-size: 0.76rem;
            }
        }
    </style>
</head>
<body{{ request()->routeIs('bible-quiz*') ? ' class="bible-quiz"' : '' }}>
<aside class="sda-right-bar" aria-label="SDA right bar">
    <button id="sdaLogoBtn" class="sda-logo-btn" type="button" aria-expanded="false" aria-controls="sdaLogoTextPanel">
        <img class="sda-right-bar-logo" src="{{ asset('6.png') }}" alt="SDA logo">
    </button>
</aside>
<section id="sdaLogoTextPanel" class="sda-logo-text-panel" aria-hidden="true">
    <div class="sda-logo-img-panel">
        <img id="sdaLogoMainImg" src="{{ asset('D LOGO.jpg') }}" alt="Seventh-day Adventist D Logo" class="sda-logo-main-img">
        <div id="sdaLogoLens" class="sda-logo-lens" aria-hidden="true"></div>
        <p class="sda-logo-zoom-hint">&#128269; Click image to zoom</p>
    </div>
</section>
<div id="sdaLogoZoomOverlay" class="sda-logo-zoom-overlay" role="dialog" aria-modal="true" aria-label="Zoomed SDA logo">
    <button class="sda-zoom-close" id="sdaZoomClose" type="button" aria-label="Close zoom">×</button>
    <img src="{{ asset('D LOGO.jpg') }}" alt="Seventh-day Adventist D Logo full size">
</div>
@php
    $adminContent = \App\Support\AdminContentStore::get();
    $isAdminDashboardSession = (bool) session('is_admin_dashboard');
    $registeredUserName = trim((string) session('registered_user_name', ''));
    $registeredUserData = session('registration_data', null);
    $persistentRegisteredEmail = trim((string) request()->cookie('mubs_registered_email', ''));
    $persistentRegisteredName = trim((string) request()->cookie('mubs_registered_name', ''));

    if ((!$registeredUserData || $registeredUserName === '') && ($persistentRegisteredEmail !== '' || $persistentRegisteredName !== '')) {
        $registeredUserRecord = null;

        if ($persistentRegisteredEmail !== '') {
            $registeredUserRecord = \App\Models\Registration::where('email', $persistentRegisteredEmail)->orderByDesc('id')->first();
        }

        if (!$registeredUserRecord && $persistentRegisteredName !== '') {
            $registeredUserRecord = \App\Models\Registration::where('full_name', $persistentRegisteredName)->orderByDesc('id')->first();
        }

        if ($registeredUserRecord) {
            $registeredUserData = $registeredUserRecord->toArray();
            $registeredUserName = trim((string) ($registeredUserRecord->full_name ?? $persistentRegisteredName));
        }
    }

    if (!$registeredUserData && $registeredUserName !== '') {
        $registeredUserRecord = \App\Models\Registration::where('full_name', $registeredUserName)->orderByDesc('id')->first();
        $registeredUserData = $registeredUserRecord ? $registeredUserRecord->toArray() : null;
    }

    $registeredUserInitial = $registeredUserName !== '' ? strtoupper(substr($registeredUserName, 0, 1)) : '';

    $adminUpdates = is_array($adminContent['updates'] ?? null) ? $adminContent['updates'] : [];
    $formatEventCategoryLabel = function (string $slug): string {
        $normalized = trim(str_replace(['_', ' '], '-', strtolower($slug)));

        if ($normalized === '') {
            return 'Events';
        }

        return ucwords(str_replace('-', ' ', $normalized));
    };
    $eventSectionLabels = [
        'gallery' => 'Photo',
        'story' => 'Story',
        'videos' => 'Video',
    ];
    $eventNotifications = collect($adminContent['event_media'] ?? [])
        ->filter(function ($item) {
            return is_array($item);
        })
        ->map(function ($item) use ($formatEventCategoryLabel, $eventSectionLabels) {
            $section = strtolower(trim((string) ($item['section'] ?? '')));
            $categorySlug = trim(str_replace(['_', ' '], '-', strtolower((string) ($item['category'] ?? ''))));

            if (!isset($eventSectionLabels[$section]) || $categorySlug === '') {
                return null;
            }

            $title = trim((string) ($item['title'] ?? ''));
            $description = trim((string) ($item['description'] ?? ''));
            $subject = trim(preg_replace('/\s+/', ' ', $title !== '' ? $title : $description));

            if ($subject === '') {
                $subject = $formatEventCategoryLabel($categorySlug);
            }

            if (strlen($subject) > 72) {
                $subject = substr($subject, 0, 69) . '...';
            }

            return [
                'title' => 'New ' . $eventSectionLabels[$section] . ' about ' . $subject,
                'meta' => $formatEventCategoryLabel($categorySlug) . ' - ' . $eventSectionLabels[$section],
                'url' => route('events', ['category' => $categorySlug, 'section' => $section]),
            ];
        })
        ->filter()
        ->values()
        ->all();
    $headerAdminUpdates = array_values(array_merge($eventNotifications, $adminUpdates));
    $adminUpdatesCount = count($headerAdminUpdates);
    $canShowNotificationCounts = \App\Models\Registration::query()->exists();
    $notificationVisibleCount = $canShowNotificationCounts ? $adminUpdatesCount : 0;
    $adminUpdateTimestamp = trim((string) data_get($adminContent, '_meta.last_admin_update_at', ''));

    if ($adminUpdateTimestamp === '') {
        $contentStorePath = \App\Support\AdminContentStore::path();
        $contentStoreMtime = file_exists($contentStorePath) ? filemtime($contentStorePath) : time();
        $adminUpdateTimestamp = date(DATE_ATOM, $contentStoreMtime ?: time());
    }

    $pastor = array_merge(config('church.pastor', []), $adminContent['pastor'] ?? []);
    $pastorName = $pastor['name'] ?? 'Pastor';
    $pastorInitial = strtoupper(substr($pastorName, 0, 1));
    $pastorPhoto = $pastor['photo'] ?? null;
    $pastorPhotoUrl = null;

    if (is_string($pastorPhoto) && $pastorPhoto !== '' && file_exists(public_path($pastorPhoto))) {
        $pastorPhotoUrl = asset($pastorPhoto);
    }

    $pastorPhone = $pastor['phone'] ?? null;
    $pastorWhatsapp = $pastor['whatsapp'] ?? $pastorPhone;
    $pastorEmail = $pastor['email'] ?? null;

    $pastorWhatsappUrl = $pastorWhatsapp ? 'https://wa.me/' . preg_replace('/\D+/', '', $pastorWhatsapp) : '#';
    $pastorCallUrl = $pastorPhone ? 'tel:' . preg_replace('/\s+/', '', $pastorPhone) : '#';
    $pastorEmailUrl = $pastorEmail ? 'mailto:' . $pastorEmail : '#';

    $askPastorSupportOptions = [
        [
            'label' => 'Bible Questions',
            'icon' => '📖',
            'hint' => 'Ask about Bible teachings',
            'message' => 'I need help with Bible questions.',
        ],
        [
            'label' => 'Prayer Request',
            'icon' => '🙏',
            'hint' => 'Share your prayer needs',
            'message' => 'I have a prayer request.',
        ],
        [
            'label' => 'Guidance',
            'icon' => '🧭',
            'hint' => 'Seek spiritual guidance',
            'message' => 'I need guidance.',
        ],
        [
            'label' => 'Counselling',
            'icon' => '💙',
            'hint' => 'Request counselling support',
            'message' => 'I would like counselling support.',
        ],
    ];

    $routeName = optional(request()->route())->getName() ?? '';
    $titleRaw = trim((string) $__env->yieldContent('title', ''));
    $titleParts = $titleRaw !== '' ? array_map('trim', explode('|', $titleRaw)) : [];
    $pageLabel = count($titleParts) > 1 ? end($titleParts) : ($titleParts[0] ?? '');
    $pageLabel = $pageLabel === '' ? 'Page' : $pageLabel;

    $topLabel = 'Home';
    $topRoute = route('home');

    if (request()->routeIs('our-journey') || request()->routeIs('our-beliefs') || request()->routeIs('about-us') || request()->routeIs('other-resources')) {
        $topLabel = 'About';
        $topRoute = route('our-journey');
    } elseif (request()->routeIs('ministry') || request()->routeIs('church-families')) {
        $topLabel = 'Departments';
        $topRoute = route('ministry');
    } elseif (request()->routeIs('events')) {
        $topLabel = 'Events';
        $topRoute = route('events');
    } elseif (request()->routeIs('media')) {
        $topLabel = 'Media';
        $topRoute = route('media');
    } elseif (request()->routeIs('database')) {
        $topLabel = 'Register';
        $topRoute = route('database');
    } elseif (request()->routeIs('bible-quiz') || request()->routeIs('bible-quiz.*')) {
        $topLabel = 'Bible Quiz';
        $topRoute = route('bible-quiz');
    }

    $categoryLabel = trim(str_replace(['-', '_'], ' ', (string) request()->query('category', '')));
    $sectionLabel = trim(str_replace(['-', '_'], ' ', (string) request()->query('section', '')));

    $breadcrumbItems = [];

    if ($topLabel !== 'Home' || !request()->routeIs('home')) {
        $breadcrumbItems[] = ['label' => $topLabel, 'href' => $topRoute, 'isCurrent' => false];
    }

    if ($categoryLabel !== '') {
        $breadcrumbItems[] = ['label' => ucwords($categoryLabel), 'href' => null, 'isCurrent' => false];
    }

    if ($sectionLabel !== '') {
        $breadcrumbItems[] = ['label' => ucwords($sectionLabel), 'href' => null, 'isCurrent' => false];
    }

    $normalizedPageLabel = strtoupper(trim($pageLabel));
    $lastBreadcrumbLabel = !empty($breadcrumbItems) ? strtoupper((string) end($breadcrumbItems)['label']) : '';

    if ($normalizedPageLabel !== '' && $normalizedPageLabel !== $lastBreadcrumbLabel) {
        $breadcrumbItems[] = ['label' => $pageLabel, 'href' => null, 'isCurrent' => true];
    } elseif (!empty($breadcrumbItems)) {
        $breadcrumbItems[count($breadcrumbItems) - 1]['isCurrent'] = true;
    }

    if (empty($breadcrumbItems)) {
        $breadcrumbItems[] = ['label' => 'Home', 'href' => route('home'), 'isCurrent' => true];
    }
@endphp
<header class="site-header">
    <div class="container header-wrap">
        <div class="header-start">
            <div class="brand">
                <img src="{{ asset('6.png') }}" alt="SDA CHURCH MUBS logo">
                <span class="brand-title">SDA CHURCH MUBS - KIREKA HILL DISTRICT</span>
            </div>
        </div>
        <div class="header-social" aria-label="Social media links">
            <a class="header-social-link facebook" href="https://www.facebook.com/Musdaabs" aria-label="Facebook" target="_blank" rel="noopener noreferrer">
                <span class="header-social-icon" aria-hidden="true">
                    <img src="{{ asset('facebook.jpg') }}" alt="">
                </span>
                <span class="header-social-name">Facebook</span>
            </a>
            <a class="header-social-link twitter" href="https://x.com/Musdaa_Mubs?s=09" aria-label="X (Twitter)" target="_blank" rel="noopener noreferrer">
                <span class="header-social-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" role="presentation" focusable="false">
                        <path d="M18.24 2H21l-6.56 7.5L22 22h-5.83l-4.56-5.97L6.37 22H3.61l7.02-8.02L2 2h5.98l4.12 5.45L18.24 2zm-1.02 18h1.62L5.13 3.87H3.4L17.22 20z"/>
                    </svg>
                </span>
                <span class="header-social-name">X (Twitter)</span>
            </a>
            <a class="header-social-link tiktok" href="https://www.tiktok.com/@musdaamubs1?_r=1&_t=ZM-91OWrIpRGUm" aria-label="TikTok" target="_blank" rel="noopener noreferrer">
                <span class="header-social-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" role="presentation" focusable="false">
                        <path d="M15.2 3c.2 1.8 1.2 3.1 3 3.5V9a7.1 7.1 0 0 1-2.9-.8v5.1a5.4 5.4 0 1 1-4.8-5.4v2.6a2.8 2.8 0 1 0 2.2 2.8V3h2.5z"/>
                    </svg>
                </span>
                <span class="header-social-name">TikTok</span>
            </a>
            <a class="header-social-link youtube" href="https://youtube.com/@musdaa-mubs5019?si=Krf5rbOxB7XJ3_x6" aria-label="YouTube" target="_blank" rel="noopener noreferrer">
                <span class="header-social-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" role="presentation" focusable="false">
                        <path d="M21.4 7.2a2.7 2.7 0 0 0-1.9-1.9C17.8 5 12 5 12 5s-5.8 0-7.5.3a2.7 2.7 0 0 0-1.9 1.9A28 28 0 0 0 2.3 12c0 1.6.1 3.2.3 4.8a2.7 2.7 0 0 0 1.9 1.9c1.7.3 7.5.3 7.5.3s5.8 0 7.5-.3a2.7 2.7 0 0 0 1.9-1.9c.2-1.6.3-3.2.3-4.8 0-1.6-.1-3.2-.3-4.8zM10 15.5V8.5L16 12l-6 3.5z"/>
                    </svg>
                </span>
                <span class="header-social-name">YouTube</span>
            </a>
            <span class="header-social-text">Follow SDA CHURCH MUBS</span>
        </div>
        <button
            id="mobileNavToggle"
            class="site-nav-toggle"
            type="button"
            aria-expanded="false"
            aria-controls="siteMainNav"
        >
            <span class="site-nav-toggle-icon" aria-hidden="true">☰</span>
            <span>Menu</span>
        </button>
        <div class="header-quick-actions" aria-label="Quick actions">
            <span class="quick-action-control" data-tooltip="Toggle notification menu">
                <button
                    id="notificationToggle"
                    class="quick-action-btn notification-btn"
                    type="button"
                    aria-expanded="false"
                    aria-controls="notificationMenu"
                    aria-label="Toggle notification menu"
                    data-admin-updated-at="{{ $adminUpdateTimestamp }}"
                    data-admin-updates-count="{{ $notificationVisibleCount }}"
                    data-can-show-counts="{{ $canShowNotificationCounts ? '1' : '0' }}"
                    data-updates-feed-url="{{ route('updates.feed') }}"
                >
                    <svg class="quick-action-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M12 4a5 5 0 0 0-5 5v2.8c0 .7-.2 1.4-.6 2L5.2 16a1 1 0 0 0 .8 1.5h12a1 1 0 0 0 .8-1.5l-1.2-2.2c-.4-.6-.6-1.3-.6-2V9a5 5 0 0 0-5-5Z" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M10 19a2 2 0 0 0 4 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                </button>
                <span id="adminUpdateBadge" class="quick-action-badge" aria-hidden="true"></span>
            </span>
            <span class="quick-action-control" data-tooltip="Toggle chat room">
                <a href="{{ route('forum') }}" class="quick-action-link quick-chat-link" aria-label="Toggle chat room">
                    <svg class="quick-action-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M4 7.5A3.5 3.5 0 0 1 7.5 4h9A3.5 3.5 0 0 1 20 7.5v5A3.5 3.5 0 0 1 16.5 16H10l-3.8 3a.8.8 0 0 1-1.3-.6V16.6A3.5 3.5 0 0 1 4 13.5v-6Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                    </svg>
                </a>
            </span>
            @if($registeredUserInitial !== '')
                @if($isAdminDashboardSession)
                    <a id="memberBadgeBtn" class="quick-action-control" data-tooltip="{{ $registeredUserName }}" href="{{ route('admin.dashboard') }}" aria-label="Open admin dashboard">
                        <span class="quick-user-badge" role="status" aria-label="Administrator initial">{{ $registeredUserInitial }}</span>
                    </a>
                @else
                    <button id="memberBadgeBtn" class="quick-action-control" data-tooltip="{{ $registeredUserName }}" type="button" aria-expanded="false" aria-controls="memberDetailsModal" aria-label="Open member details">
                        <span class="quick-user-badge" role="status" aria-label="Registered member initial">{{ $registeredUserInitial }}</span>
                    </button>
                @endif
            @endif

            <div id="notificationMenu" class="header-notification-menu" aria-hidden="true">
                <p id="notificationMenuTitle" class="notification-menu-title">
                    {{ $canShowNotificationCounts ? ('SDA MUBS Updates (' . $notificationVisibleCount . ' available)') : 'SDA MUBS Updates' }}
                </p>
                @if(!empty($headerAdminUpdates))
                    <p id="notificationMenuEmpty" class="notification-menu-empty" style="display:none;">No SDA MUBS updates yet.</p>
                    <ul id="notificationMenuList" class="notification-menu-list">
                        @foreach($headerAdminUpdates as $update)
                            @php
                                $updateTitle = trim((string) ($update['title'] ?? 'Church update'));
                                $updateUrl = trim((string) ($update['url'] ?? route('updates')));
                                $updateMeta = trim((string) ($update['meta'] ?? ''));
                                $updateDepartment = trim((string) ($update['department'] ?? ''));
                                $updateDateRange = trim((string) ($update['date_range'] ?? ''));
                                $updateMonth = trim((string) ($update['month'] ?? ''));
                            @endphp
                            <li>
                                <a href="{{ $updateUrl !== '' ? $updateUrl : route('updates') }}">
                                    <span class="notification-item-title">{{ $updateTitle }}</span>
                                    @if($updateMeta !== '' || $updateDepartment !== '' || $updateDateRange !== '' || $updateMonth !== '')
                                        <span class="notification-item-meta">
                                            @if($updateMeta !== '')
                                                {{ $updateMeta }}
                                            @else
                                                @if($updateDepartment !== ''){{ $updateDepartment }}@endif
                                                @if($updateDepartment !== '' && $updateDateRange !== '') - @endif
                                                @if($updateDateRange !== ''){{ $updateDateRange }}@endif
                                                @if($updateDepartment === '' && $updateDateRange === '' && $updateMonth !== ''){{ $updateMonth }}@endif
                                            @endif
                                        </span>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p id="notificationMenuEmpty" class="notification-menu-empty">No SDA MUBS updates yet.</p>
                    <ul id="notificationMenuList" class="notification-menu-list" style="display:none;"></ul>
                @endif
                <div class="notification-menu-actions">
                    <button id="clearNotificationsBtn" class="notification-menu-clear" type="button">Clear</button>
                    <a class="notification-menu-link" href="{{ route('updates') }}">Open updates page</a>
                </div>
            </div>
        </div>
        <nav id="siteMainNav" class="site-nav" aria-label="Main navigation">
            <div class="site-nav-links">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
            <div class="nav-item-dropdown {{ request()->routeIs('ministry') || request()->routeIs('church-families') ? 'is-open' : '' }}" id="ministryNavDropdown">
                <button
                    id="ministryDropdownTrigger"
                    class="nav-dropdown-trigger {{ request()->routeIs('ministry') || request()->routeIs('church-families') ? 'active' : '' }}"
                    type="button"
                    aria-haspopup="true"
                    aria-expanded="false"
                    aria-controls="ministryDropdownPanel"
                >
                    Ministry <span class="nav-dropdown-caret" aria-hidden="true">▾</span>
                </button>
                <ul id="ministryDropdownPanel" class="nav-dropdown-panel" role="menu" aria-label="Ministry links">
                    <li class="nav-dropdown-head" role="presentation">Ministry Menu</li>
                    <li class="nav-dropdown-item" role="none">
                        <a class="nav-dropdown-main" href="{{ route('ministry', ['section' => 'board']) }}" role="menuitem">
                            <span>Church Board</span>
                        </a>
                    </li>
                    <li class="nav-dropdown-item" role="none">
                        <a class="nav-dropdown-main" href="{{ route('ministry', ['section' => 'association']) }}" role="menuitem">
                            <span>Association (MUSDAA-BS)</span>
                        </a>
                    </li>
                    <li class="nav-dropdown-item" role="none">
                        <a class="nav-dropdown-main" href="{{ route('church-families') }}" role="menuitem">
                            <span>Church Families</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="nav-item-dropdown {{ request()->routeIs('media') ? 'is-open' : '' }}" id="mediaNavDropdown">
                <button
                    id="mediaDropdownTrigger"
                    class="nav-dropdown-trigger {{ request()->routeIs('media') ? 'active' : '' }}"
                    type="button"
                    aria-haspopup="true"
                    aria-expanded="false"
                    aria-controls="mediaDropdownPanel"
                >
                    Media <span class="nav-dropdown-caret" aria-hidden="true">▾</span>
                </button>
                <ul id="mediaDropdownPanel" class="nav-dropdown-panel" role="menu" aria-label="Media links">
                    <li class="nav-dropdown-head" role="presentation">Media Platforms</li>
                    <li class="nav-dropdown-item" role="none">
                        <a class="nav-dropdown-main" href="https://www.primeradio.co.ug/" target="_blank" rel="noopener" role="menuitem">
                            <span>Prime Radio</span>
                        </a>
                    </li>
                    <li class="nav-dropdown-item" role="none">
                        <a class="nav-dropdown-main" href="https://www.hopetv.org/" target="_blank" rel="noopener" role="menuitem">
                            <span>Hope Channel</span>
                        </a>
                    </li>
                    <li class="nav-dropdown-item" role="none">
                        <a class="nav-dropdown-main" href="https://adventist.news/" target="_blank" rel="noopener" role="menuitem">
                            <span>ANN</span>
                        </a>
                    </li>
                    <li class="nav-dropdown-item" role="none">
                        <a class="nav-dropdown-main" href="https://www.youtube.com/results?search_query=Hope+Channel+Uganda" target="_blank" rel="noopener" role="menuitem">
                            <span>Hope Channel Uganda</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="nav-item-dropdown {{ request()->routeIs('events') ? 'is-open' : '' }}" id="eventsNavDropdown">
                <button
                    id="eventsDropdownTrigger"
                    class="nav-dropdown-trigger {{ request()->routeIs('events') ? 'active' : '' }}"
                    type="button"
                    aria-haspopup="true"
                    aria-expanded="false"
                    aria-controls="eventsDropdownPanel"
                >
                    Events <span class="nav-dropdown-caret" aria-hidden="true">▾</span>
                </button>
                <ul id="eventsDropdownPanel" class="nav-dropdown-panel" role="menu" aria-label="Events categories">
                    <li class="nav-dropdown-head" role="presentation">Events Menu</li>
                    @php
                        $adminContent = \App\Support\AdminContentStore::get();
                        $defaultEventCategories = collect([
                            ['label' => 'Evangelism Campaign', 'slug' => 'evangelism-campaign'],
                            ['label' => 'Community Outreach', 'slug' => 'community-outreach'],
                            ['label' => 'Social Events', 'slug' => 'social-events'],
                        ]);

                        $eventCategories = collect($adminContent['event_media'] ?? [])
                            ->map(function ($item) {
                                $slug = trim(str_replace(['_', ' '], '-', strtolower((string) ($item['category'] ?? ''))));

                                if ($slug === '') {
                                    return null;
                                }

                                return [
                                    'label' => ucwords(str_replace('-', ' ', $slug)),
                                    'slug' => $slug,
                                ];
                            })
                            ->filter()
                            ->merge($defaultEventCategories)
                            ->unique('slug')
                            ->values();

                        $eventSubLinks = [
                            ['label' => 'Videos', 'slug' => 'videos'],
                            ['label' => 'Story', 'slug' => 'story'],
                            ['label' => 'Gallery', 'slug' => 'gallery'],
                        ];
                    @endphp

                    @foreach($eventCategories as $category)
                        <li class="nav-dropdown-item" role="none">
                            <button
                                class="nav-dropdown-main"
                                role="menuitem"
                                type="button"
                                aria-haspopup="true"
                                aria-expanded="false"
                            >
                                <span>{{ $category['label'] }}</span>
                                <span class="nav-submenu-caret" aria-hidden="true">▸</span>
                            </button>

                            <ul class="nav-submenu" role="menu" aria-label="{{ $category['label'] }} links">
                                @foreach($eventSubLinks as $subLink)
                                    <li role="none">
                                        <a
                                            class="nav-submenu-link"
                                            href="{{ route('events', ['category' => $category['slug'], 'section' => $subLink['slug']]) }}"
                                            role="menuitem"
                                        >
                                            {{ $subLink['label'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="nav-item-dropdown {{ request()->routeIs('our-journey') || request()->routeIs('our-beliefs') || request()->routeIs('about-us') || request()->routeIs('other-resources') ? 'is-open' : '' }}" id="aboutNavDropdown">
                <button
                    id="aboutDropdownTrigger"
                    class="nav-dropdown-trigger {{ request()->routeIs('our-journey') || request()->routeIs('our-beliefs') || request()->routeIs('about-us') || request()->routeIs('other-resources') ? 'active' : '' }}"
                    type="button"
                    aria-haspopup="true"
                    aria-expanded="false"
                    aria-controls="aboutDropdownPanel"
                >
                    About Us <span class="nav-dropdown-caret" aria-hidden="true">▾</span>
                </button>
                <ul id="aboutDropdownPanel" class="nav-dropdown-panel" role="menu" aria-label="About links">
                    <li class="nav-dropdown-head" role="presentation">About Menu</li>
                    <li class="nav-dropdown-item" role="none">
                        <a class="nav-dropdown-main {{ request()->routeIs('our-beliefs') ? 'is-active' : '' }}" href="{{ route('our-beliefs') }}" role="menuitem">
                            <span>Our Beliefs</span>
                        </a>
                    </li>
                    <li class="nav-dropdown-item" role="none">
                        <a class="nav-dropdown-main {{ request()->routeIs('our-journey') || request()->routeIs('about-us') ? 'is-active' : '' }}" href="{{ route('our-journey') }}" role="menuitem">
                            <span>Our Journey</span>
                        </a>
                    </li>
                    <li class="nav-dropdown-item" role="none">
                        <a class="nav-dropdown-main {{ request()->routeIs('other-resources') ? 'is-active' : '' }}" href="{{ route('other-resources') }}" role="menuitem">
                            <span>Other Resources</span>
                        </a>
                    </li>
                </ul>
            </div>
            <a href="{{ route('updates') }}" class="{{ request()->routeIs('updates') ? 'active' : '' }}">Updates</a>
            @if($registeredUserInitial === '')
                <a href="{{ route('database') }}" class="{{ request()->routeIs('database') ? 'active' : '' }}">Register</a>
            @endif
            @if($isAdminDashboardSession && $registeredUserName !== '')
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">{{ \Illuminate\Support\Str::limit($registeredUserName, 26) }}</a>
            @endif
            <a href="{{ route('bible-quiz') }}" class="{{ request()->routeIs('bible-quiz') ? 'active' : '' }}">Bible Quiz</a>
            </div>
        </nav>
    </div>
</header>

@if($registeredUserInitial !== '' && !$isAdminDashboardSession)
<style>
    #memberDetailsModal {
        position: fixed;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(8, 21, 44, 0.6);
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity 0.18s ease, visibility 0.18s ease;
        z-index: 9999;
    }

    #memberDetailsModal.is-open {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }

    .member-panel {
        background: #ffffff;
        border-radius: 12px;
        padding: 1rem;
        width: min(520px, calc(100vw - 40px));
        box-shadow: 0 22px 48px rgba(15, 43, 85, 0.28);
        position: relative;
    }

    .member-panel .member-avatar {
        width: 64px;
        height: 64px;
        border-radius: 999px;
        background: #e36d2f;
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        font-size: 1.4rem;
        margin-right: 0.75rem;
    }

    .member-panel h3 { margin: 0 0 0.25rem 0; }
    .member-panel p { margin: 0.15rem 0; color: #223a5a; }

    .member-panel .member-actions { margin-top: 0.85rem; display:flex; gap:0.6rem; justify-content:flex-end; }
    .member-panel .member-actions .quick-action-btn { padding: 0.48rem 0.85rem; border-radius: 10px; font-weight:700; border:1px solid #1f4a8a; background:#1f4a8a; color:#fff; text-decoration:none; }
    .member-panel .member-actions form button { padding: 0.48rem 0.85rem; border-radius:10px; font-weight:700; border:1px solid #b12; background:#b12; color:#fff; cursor:pointer; }

    .member-panel .close-btn { position:absolute; top:8px; right:10px; background:transparent; border:0; font-size:1.2rem; cursor:pointer; }

    /* Remove the unwanted square border around the member badge button */
    #memberBadgeBtn {
        border: 0;
        background: transparent;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    #memberBadgeBtn .quick-user-badge {
        border: 0 !important;
        box-shadow: none !important;
        outline: none !important;
        background-clip: padding-box;
    }

    /* Keep an accessible focus ring but avoid a visible border box */
    #memberBadgeBtn:focus-visible {
        outline: none;
        box-shadow: 0 0 0 3px rgba(31,74,138,0.12);
        border-radius: 999px;
    }
</style>

<div id="memberDetailsModal" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="member-panel" role="document">
        <button class="close-btn" id="memberModalClose" type="button" aria-label="Close">×</button>
        <div style="display:flex; align-items:center; gap:0.6rem;">
            <div class="member-avatar">{{ $registeredUserInitial }}</div>
            <div>
                <h3>{{ $registeredUserName ?: 'Member' }}</h3>
                <p style="font-size:0.92rem; color:#4b5f7b;">{{ $registeredUserData['email'] ?? '' }}</p>
            </div>
        </div>

        <div style="margin-top:0.8rem; display:grid; gap:0.24rem;">
            <p><strong>Phone:</strong> {{ $registeredUserData['phone'] ?? 'N/A' }}</p>
            <p><strong>Category:</strong> {{ $registeredUserData['category'] ?? 'N/A' }}</p>
            <p><strong>Family:</strong> {{ $registeredUserData['family'] ?? 'N/A' }}</p>
        </div>

        <div class="member-actions">
            <a href="{{ route('database.edit') }}" class="quick-action-btn">Update</a>
            <button type="button" id="memberModalExitBtn" class="quick-action-btn" style="border:0; cursor:pointer;">Exit</button>
        </div>
    </div>
</div>
<script>
    (function () {
        var badge = document.getElementById('memberBadgeBtn');
        var modal = document.getElementById('memberDetailsModal');
        var closeBtn = document.getElementById('memberModalClose');
        var exitBtn = document.getElementById('memberModalExitBtn');

        if (!badge || !modal) return;

        function openModal() {
            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
            badge.setAttribute('aria-expanded', 'true');
        }

        function closeModal() {
            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
            badge.setAttribute('aria-expanded', 'false');
        }

        badge.addEventListener('click', function () {
            if (modal.classList.contains('is-open')) {
                closeModal();
            } else {
                openModal();
            }
        });

        if (closeBtn) closeBtn.addEventListener('click', closeModal);
        if (exitBtn) exitBtn.addEventListener('click', closeModal);

        // Close when clicking outside panel
        modal.addEventListener('click', function (ev) {
            if (ev.target === modal) closeModal();
        });
    })();
</script>
@endif

@php
    $content = App\Support\AdminContentStore::get();
    $heroSlides = $content['hero_slides'] ?? [];
    $showHomeHero = request()->routeIs('home') && is_array($heroSlides) && count($heroSlides) > 0;
@endphp

<main class="{{ $showHomeHero ? 'has-home-hero' : '' }}">
    @if($showHomeHero)
        <section class="homepage-hero" aria-label="Homepage featured messages">
            <div id="heroSlider" class="hero-slider" aria-label="Featured messages">
                    @foreach($heroSlides as $i => $slide)
                        @php
                            $img = trim((string) ($slide['image'] ?? '')) ?: '';
                            $img = str_replace('\\', '/', $img);
                            $img = preg_replace('#^\./#', '', $img);
                            $img = preg_replace('#^public/#i', '', $img);
                            $img = ltrim($img, '/');

                            if (preg_match('/^https?:\/\//i', $img)) {
                                $imgUrl = $img;
                            } elseif ($img !== '' && strpos($img, '..') === false) {
                                // Assume admin-provided paths are relative to /public.
                                // Avoid file_exists() gates so uploads also work behind symlinks/CDNs.
                                $imgUrl = asset($img);
                            } else {
                                $imgUrl = null;
                            }

                            $title = trim((string) ($slide['title'] ?? ''));
                            $subtitle = trim((string) ($slide['subtitle'] ?? ''));
                            $textColor = trim((string) ($slide['text_color'] ?? '#ffffff')) ?: '#ffffff';
                        @endphp
                        <div class="hero-slide{{ $i === 0 ? ' active' : '' }}" style="background-image: url('{{ $imgUrl ?? asset('sda.png') }}')" data-text-color="{{ $textColor }}">
                            <div class="hero-slide-overlay"></div>
                            <div class="hero-slide-content">
                                <h2 style="color: {{ $textColor }}">{{ $title }}</h2>
                                @if($subtitle !== '')<p style="color: {{ $textColor }}">{{ $subtitle }}</p>@endif
                            </div>
                        </div>
                    @endforeach
                    <button class="hero-slider-nav prev" aria-label="Previous slide">&#10094;</button>
                    <button class="hero-slider-nav next" aria-label="Next slide">&#10095;</button>
                    <div class="hero-slider-dots" aria-label="Slide indicators">
                        @foreach($heroSlides as $i => $slide)
                            <button class="dot{{ $i === 0 ? ' active' : '' }}" aria-label="Go to slide {{ $i + 1 }}"></button>
                        @endforeach
                    </div>
            </div>
        </section>
    @endif

    <div class="container">
        <nav class="site-breadcrumb" aria-label="Page path">
            <ol class="site-breadcrumb-list">
                @foreach($breadcrumbItems as $index => $crumb)
                    <li class="site-breadcrumb-item {{ !empty($crumb['isCurrent']) ? 'current' : '' }}">
                        @if(!empty($crumb['href']) && empty($crumb['isCurrent']))
                            <a href="{{ $crumb['href'] }}">{{ $crumb['label'] }}</a>
                        @else
                            <span>{{ $crumb['label'] }}</span>
                        @endif
                    </li>
                    @if($index < count($breadcrumbItems) - 1)
                        <li class="site-breadcrumb-sep" aria-hidden="true">›</li>
                    @endif
                @endforeach
            </ol>
        </nav>
        @yield('content')
    </div>
</main>
<style>
.has-home-hero { padding-top: 0 !important; }
.homepage-hero { margin: 0 0 1rem; width: 100%; }
.hero-slider { position: relative; width: 100%; min-height: clamp(420px, 72vh, 860px); overflow: hidden; border-radius: 0; box-shadow: 0 12px 26px rgba(15,43,85,0.16); }
.hero-slide { position: absolute; inset: 0; background-size: cover; background-position: center center; background-repeat: no-repeat; opacity: 0; transition: opacity 0.6s ease; display:flex; align-items:center; justify-content:center; }
.hero-slide.active { opacity: 1; z-index:2; }
.hero-slide-overlay { position:absolute; inset:0; background: linear-gradient(180deg, rgba(6,20,40,0.3), rgba(6,20,40,0.06)); z-index:1; }
.hero-slide-content { position:relative; z-index:2; text-align:center; max-width:940px; padding: 1.25rem 1.4rem; background: rgba(0,0,0,0.2); border-radius:12px; }
.hero-slide-content h2 { margin:0; font-size: clamp(1.25rem, 3.8vw, 2.25rem); font-weight:800; text-transform: none; }
.hero-slide-content p { margin:0.6rem 0 0; font-weight:600; }
.hero-slider-nav { position:absolute; top:50%; transform:translateY(-50%); width:44px;height:44px;border-radius:50%;border:0;background:rgba(255,255,255,0.12);color:#fff;font-size:1.6rem;display:flex;align-items:center;justify-content:center;z-index:3; }
.hero-slider-nav.prev { left:12px; }
.hero-slider-nav.next { right:12px; }
.hero-slider-dots { position:absolute; left:50%; bottom:12px; transform:translateX(-50%); display:flex;gap:8px; z-index:4; }
.hero-slider-dots .dot { width:11px;height:11px;border-radius:999px;background:rgba(255,255,255,0.8);opacity:0.6;border:2px solid rgba(0,0,0,0.15); }
.hero-slider-dots .dot.active { opacity:1;background:#fff;border-color:#1f4a8a; }
@media (max-width:720px){ .hero-slide-content{ padding:0.8rem; } .hero-slider{ min-height:320px; } .hero-slider-nav{ width:36px;height:36px;font-size:1.1rem; } }
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const slider = document.getElementById('heroSlider');
    if (!slider) return;
    const slides = Array.from(slider.querySelectorAll('.hero-slide'));
    const dots = Array.from(slider.querySelectorAll('.hero-slider-dots .dot'));
    const prev = slider.querySelector('.hero-slider-nav.prev');
    const next = slider.querySelector('.hero-slider-nav.next');
    let idx = 0;
    function go(i){
        slides.forEach((s, j)=> s.classList.toggle('active', j===i));
        dots.forEach((d, j)=> d.classList.toggle('active', j===i));
        idx = i;
    }
    prev && prev.addEventListener('click', ()=> go((idx-1+slides.length)%slides.length));
    next && next.addEventListener('click', ()=> go((idx+1)%slides.length));
    dots.forEach((d,i)=> d.addEventListener('click', ()=> go(i)));
    setInterval(()=> go((idx+1)%slides.length), 8000);
});
</script>

<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <section>
                <p class="footer-note">This is the official website of SDA CHURCH MUBS.</p>
            </section>

            <section>
                <ul class="footer-links">
                    <li><span class="footer-icon" aria-hidden="true">≡</span><a href="https://www.adventist.org/" target="_blank" rel="noopener">General Conference</a></li>
                    <li><span class="footer-icon" aria-hidden="true">≡</span><a href="https://ecd.adventist.org/" target="_blank" rel="noopener">East and Central African Division</a></li>
                    <li><span class="footer-icon" aria-hidden="true">≡</span><a href="https://uu-adventist.org/" target="_blank" rel="noopener">Uganda Union</a></li>
                </ul>
            </section>

            <section>
                <ul class="footer-links">
                    <li><span class="footer-icon" aria-hidden="true">≡</span><a href="https://www.cucsda.org/" target="_blank" rel="noopener">Central Uganda Conference</a></li>
                    <li><span class="footer-icon" aria-hidden="true">≡</span><a href="{{ route('forum') }}">SDA MUBS FORUM</a></li>
                    <li><span class="footer-icon" aria-hidden="true">≡</span><a href="{{ route('contact') }}">Contact</a></li>
                </ul>
            </section>
        </div>

        <div class="footer-bottom">
            <span>© {{ date('Y') }} SDA CHURCH MUBS</span>
        </div>
    </div>
</footer>

<!-- Custom Modal System -->
<div id="customModalOverlay" class="custom-modal-overlay" style="display: none;">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h2 id="customModalTitle" class="custom-modal-title">Confirm</h2>
        </div>
        <div class="custom-modal-body">
            <p id="customModalMessage" class="custom-modal-message">Are you sure?</p>
        </div>
        <div class="custom-modal-footer">
            <button id="customModalCancel" class="custom-modal-btn custom-modal-btn-secondary" type="button">Cancel</button>
            <button id="customModalConfirm" class="custom-modal-btn custom-modal-btn-primary" type="button">Confirm</button>
        </div>
    </div>
</div>

<style>
.custom-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(15, 43, 85, 0.5);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.2s ease;
    backdrop-filter: blur(2px);
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from {
        transform: translateY(20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.custom-modal-content {
    background: #ffffff;
    border-radius: 14px;
    box-shadow: 0 20px 60px rgba(15, 43, 85, 0.2);
    max-width: 420px;
    width: 90%;
    overflow: hidden;
    animation: slideUp 0.3s ease;
    border: 1px solid #e6eef8;
}

.custom-modal-header {
    padding: 24px 24px 16px;
    border-bottom: 1px solid #e6eef8;
    background: linear-gradient(135deg, #f8fbff 0%, #f0f6ff 100%);
}

.custom-modal-title {
    margin: 0;
    font-size: 20px;
    font-weight: 700;
    color: #102a52;
    letter-spacing: -0.3px;
}

.custom-modal-body {
    padding: 20px 24px;
}

.custom-modal-message {
    margin: 0;
    font-size: 15px;
    color: #314a69;
    line-height: 1.6;
    word-wrap: break-word;
}

.custom-modal-footer {
    padding: 16px 24px 24px;
    display: flex;
    gap: 12px;
    justify-content: flex-end;
}

.custom-modal-footer .custom-modal-btn:only-child {
    margin-left: auto;
}

.custom-modal-btn {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    letter-spacing: 0.3px;
    text-transform: uppercase;
}

.custom-modal-btn-secondary {
    background: #e6eef8;
    color: #102a52;
    border: 1px solid #d0dff0;
}

.custom-modal-btn-secondary:hover {
    background: #d9e3f0;
    border-color: #c0cfe8;
    transform: translateY(-1px);
}

.custom-modal-btn-secondary:active {
    transform: translateY(0);
    opacity: 0.9;
}

.custom-modal-btn-primary {
    background: linear-gradient(135deg, #1f4a8a 0%, #102a52 100%);
    color: #ffffff;
}

.custom-modal-btn-primary:hover {
    background: linear-gradient(135deg, #102a52 0%, #0a1929 100%);
    box-shadow: 0 8px 20px rgba(31, 74, 138, 0.3);
    transform: translateY(-1px);
}

.custom-modal-btn-primary:active {
    transform: translateY(0);
    box-shadow: 0 4px 12px rgba(31, 74, 138, 0.2);
}

.custom-modal-btn-danger {
    background: linear-gradient(135deg, #d32f2f 0%, #c62828 100%) !important;
    color: #ffffff !important;
}

.custom-modal-btn-danger:hover {
    background: linear-gradient(135deg, #c62828 0%, #b71c1c 100%) !important;
    box-shadow: 0 8px 20px rgba(211, 47, 47, 0.3);
    transform: translateY(-1px);
}

.custom-modal-btn-danger:active {
    transform: translateY(0);
    box-shadow: 0 4px 12px rgba(211, 47, 47, 0.2);
}

@media (max-width: 480px) {
    .custom-modal-content {
        width: 95%;
        margin: 20px;
    }
    
    .custom-modal-header {
        padding: 20px;
    }
    
    .custom-modal-title {
        font-size: 18px;
    }
    
    .custom-modal-body {
        padding: 16px 20px;
    }
    
    .custom-modal-footer {
        padding: 12px 20px 16px;
        flex-direction: column;
    }
    
    .custom-modal-btn {
        width: 100%;
    }
}
</style>

<button id="askPastorToggle" class="ask-pastor-float" type="button" aria-expanded="false" aria-controls="askPastorOptions">
    @if($pastorPhotoUrl)
        <img class="ask-pastor-avatar" src="{{ $pastorPhotoUrl }}" alt="{{ $pastorName }}">
    @else
        <span class="ask-pastor-avatar-fallback" aria-hidden="true">{{ $pastorInitial }}</span>
    @endif
    <span class="ask-pastor-text">
        <span class="ask-pastor-title">Ask Pastor</span>
        <span class="ask-pastor-name">{{ $pastorName }}</span>
    </span>
</button>

<div id="askPastorOptions" class="ask-pastor-options" aria-hidden="true">
    <div class="ask-pastor-card-head">
        @if($pastorPhotoUrl)
            <img class="ask-pastor-head-avatar" src="{{ $pastorPhotoUrl }}" alt="{{ $pastorName }}">
        @else
            <span class="ask-pastor-head-fallback" aria-hidden="true">{{ $pastorInitial }}</span>
        @endif
        <div>
            <p class="ask-pastor-head-title">Choose support option</p>
            <p class="ask-pastor-head-name">{{ $pastorName }}</p>
        </div>
    </div>
    <div id="askSupportStep" class="ask-pastor-step">
        @foreach($askPastorSupportOptions as $option)
            <button
                class="ask-pastor-option ask-topic-option"
                type="button"
                data-topic-label="{{ $option['label'] }}"
                data-topic-message="{{ $option['message'] }}"
            >
                <span class="ask-pastor-option-icon" aria-hidden="true">{{ $option['icon'] }}</span>
                <span class="ask-pastor-option-text">
                    <span class="ask-pastor-option-label">{{ $option['label'] }}</span>
                    <span class="ask-pastor-option-hint">{{ $option['hint'] }}</span>
                </span>
            </button>
        @endforeach
    </div>

    <div id="askCommunicationStep" class="ask-pastor-step hidden">
        <button id="askPastorBack" class="ask-pastor-back" type="button">← Back to support options</button>
        <p id="askPastorStepTitle" class="ask-pastor-step-title">Choose communication method</p>

        <a id="askMethodCall" class="ask-pastor-option {{ $pastorCallUrl === '#' ? 'disabled' : '' }}" href="{{ $pastorCallUrl }}">
            <span class="ask-pastor-option-icon" aria-hidden="true">📞</span>
            <span class="ask-pastor-option-text">
                <span class="ask-pastor-option-label">Direct Call</span>
                <span class="ask-pastor-option-hint">{{ $pastorPhone ?: 'Phone not set' }}</span>
            </span>
        </a>

        <a id="askMethodWhatsapp" class="ask-pastor-option {{ $pastorWhatsappUrl === '#' ? 'disabled' : '' }}" href="{{ $pastorWhatsappUrl }}" @if(str_starts_with($pastorWhatsappUrl, 'http')) target="_blank" rel="noopener" @endif>
            <span class="ask-pastor-option-icon" aria-hidden="true">💬</span>
            <span class="ask-pastor-option-text">
                <span class="ask-pastor-option-label">WhatsApp</span>
                <span class="ask-pastor-option-hint">{{ $pastorWhatsapp ?: 'WhatsApp not set' }}</span>
            </span>
        </a>

        <a id="askMethodEmail" class="ask-pastor-option {{ $pastorEmailUrl === '#' ? 'disabled' : '' }}" href="{{ $pastorEmailUrl }}">
            <span class="ask-pastor-option-icon" aria-hidden="true">✉️</span>
            <span class="ask-pastor-option-text">
                <span class="ask-pastor-option-label">Email</span>
                <span class="ask-pastor-option-hint">{{ $pastorEmail ?: 'Email not set' }}</span>
            </span>
        </a>
    </div>
</div>

<script>
    // Custom Modal Utility System
    window.CustomModal = (function () {
        const overlay = document.getElementById('customModalOverlay');
        const title = document.getElementById('customModalTitle');
        const message = document.getElementById('customModalMessage');
        const confirmBtn = document.getElementById('customModalConfirm');
        const cancelBtn = document.getElementById('customModalCancel');
        
        let resolveCallback = null;
        let isVisible = false;

        function show(options) {
            return new Promise((resolve) => {
                if (!overlay) return resolve(false);
                
                const {
                    title: modalTitle = 'Confirm',
                    message: modalMessage = 'Are you sure?',
                    confirmText = 'Confirm',
                    cancelText = 'Cancel',
                    isDangerous = false
                } = options || {};
                
                // Set content
                if (title) title.textContent = modalTitle;
                if (message) message.textContent = modalMessage;
                if (confirmBtn) confirmBtn.textContent = confirmText;
                if (cancelBtn) {
                    cancelBtn.textContent = cancelText || 'Cancel';
                    // Hide cancel button if cancelText is null or empty
                    cancelBtn.style.display = cancelText ? 'block' : 'none';
                }
                
                // Handle dangerous actions (red button)
                if (confirmBtn) {
                    confirmBtn.classList.toggle('custom-modal-btn-danger', isDangerous);
                    if (isDangerous) {
                        confirmBtn.classList.remove('custom-modal-btn-primary');
                    } else {
                        confirmBtn.classList.add('custom-modal-btn-primary');
                    }
                }
                
                resolveCallback = (result) => {
                    hide();
                    resolve(result);
                };
                
                overlay.style.display = 'flex';
                isVisible = true;
                
                // Focus confirm button
                if (confirmBtn) setTimeout(() => confirmBtn.focus(), 100);
            });
        }

        function hide() {
            if (!overlay) return;
            overlay.style.display = 'none';
            isVisible = false;
        }

        function attachEventListeners() {
            if (confirmBtn) {
                confirmBtn.addEventListener('click', () => {
                    if (resolveCallback) resolveCallback(true);
                });
            }
            
            if (cancelBtn) {
                cancelBtn.addEventListener('click', () => {
                    if (resolveCallback) resolveCallback(false);
                });
            }
            
            if (overlay) {
                overlay.addEventListener('click', (e) => {
                    // Close if clicking on overlay background (not the modal content)
                    if (e.target === overlay && resolveCallback) {
                        resolveCallback(false);
                    }
                });
            }
            
            // Handle keyboard
            document.addEventListener('keydown', (e) => {
                if (!isVisible || !resolveCallback) return;
                
                if (e.key === 'Enter') {
                    e.preventDefault();
                    resolveCallback(true);
                } else if (e.key === 'Escape') {
                    e.preventDefault();
                    resolveCallback(false);
                }
            });
        }

        attachEventListeners();
        
        return { show, hide };
    })();

    (function () {
        const askPastorToggle = document.getElementById('askPastorToggle');
        const askPastorOptions = document.getElementById('askPastorOptions');
        const askSupportStep = document.getElementById('askSupportStep');
        const askCommunicationStep = document.getElementById('askCommunicationStep');
        const askPastorBack = document.getElementById('askPastorBack');
        const askPastorStepTitle = document.getElementById('askPastorStepTitle');
        const askTopicOptions = Array.from(document.querySelectorAll('.ask-topic-option'));
        const askMethodCall = document.getElementById('askMethodCall');
        const askMethodWhatsapp = document.getElementById('askMethodWhatsapp');
        const askMethodEmail = document.getElementById('askMethodEmail');
        const mobileNavToggle = document.getElementById('mobileNavToggle');
        const siteMainNav = document.getElementById('siteMainNav');
        const ministryDropdown = document.getElementById('ministryNavDropdown');
        const ministryDropdownTrigger = document.getElementById('ministryDropdownTrigger');
        const mediaDropdown = document.getElementById('mediaNavDropdown');
        const mediaDropdownTrigger = document.getElementById('mediaDropdownTrigger');
        const eventsDropdown = document.getElementById('eventsNavDropdown');
        const eventsDropdownTrigger = document.getElementById('eventsDropdownTrigger');
        const aboutDropdown = document.getElementById('aboutNavDropdown');
        const aboutDropdownTrigger = document.getElementById('aboutDropdownTrigger');
        const eventsDropdownItems = Array.from(document.querySelectorAll('#eventsDropdownPanel .nav-dropdown-item'));
        const sdaLogoBtn = document.getElementById('sdaLogoBtn');
        const sdaLogoTextPanel = document.getElementById('sdaLogoTextPanel');
        const sdaRightBar = document.querySelector('.sda-right-bar');
        const notificationToggle = document.getElementById('notificationToggle');
        const notificationMenu = document.getElementById('notificationMenu');
        const notificationMenuTitle = document.getElementById('notificationMenuTitle');
        const notificationMenuList = document.getElementById('notificationMenuList');
        const notificationMenuEmpty = document.getElementById('notificationMenuEmpty');
        const clearNotificationsBtn = document.getElementById('clearNotificationsBtn');
        const adminUpdateBadge = document.getElementById('adminUpdateBadge');
        const adminUpdateSeenStorageKey = 'mubs_admin_updates_seen_at';
        const adminUpdateClearedStorageKey = 'mubs_admin_updates_cleared_at';
        const cookieConsent = document.getElementById('cookieConsent');
        const cookieRejectBtn = document.getElementById('cookieRejectBtn');
        const cookieAcceptBtn = document.getElementById('cookieAcceptBtn');
        const cookieSettingsBtn = document.getElementById('cookieSettingsBtn');
        const cookieSettingsModal = document.getElementById('cookieSettingsModal');
        const cookieSettingsClose = document.getElementById('cookieSettingsClose');
        const cookieSaveBtn = document.getElementById('cookieSaveBtn');
        const cookiePreferenceToggle = document.getElementById('cookiePreferenceToggle');
        const cookieAnalyticsToggle = document.getElementById('cookieAnalyticsToggle');
        const cookieMarketingToggle = document.getElementById('cookieMarketingToggle');
        const cookieConsentStorageKey = 'mubs_cookie_consent_v1';
        const cookieBannerDelayMs = 10000;
        let lastScrollY = window.scrollY || window.pageYOffset || 0;
        let lastAskPastorToggleAt = 0;

        function isAskPastorReadingMobileViewport() {
            return window.matchMedia('(max-width: 900px)').matches;
        }

        function setSdaTextPanelOpen(isOpen) {
            if (!sdaLogoBtn || !sdaLogoTextPanel) {
                return;
            }

            sdaLogoTextPanel.classList.toggle('show', isOpen);
            sdaLogoTextPanel.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
            sdaLogoBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        }

        function applyCookiePreferences(preferences) {
            if (!preferences || typeof preferences !== 'object') {
                return;
            }

            document.documentElement.dataset.cookiePreference = preferences.preference ? 'granted' : 'denied';
            document.documentElement.dataset.cookieAnalytics = preferences.analytics ? 'granted' : 'denied';
            document.documentElement.dataset.cookieMarketing = preferences.marketing ? 'granted' : 'denied';

            try {
                window.dispatchEvent(new CustomEvent('cookie-consent-updated', { detail: preferences }));
            } catch (error) {
                // Ignore dispatch failures in older browsers.
            }
        }

        function closeCookieSettings() {
            if (!cookieSettingsModal) {
                return;
            }

            cookieSettingsModal.classList.remove('show');
            cookieSettingsModal.setAttribute('aria-hidden', 'true');
        }

        function openCookieSettings() {
            if (!cookieSettingsModal) {
                return;
            }

            cookieSettingsModal.classList.add('show');
            cookieSettingsModal.setAttribute('aria-hidden', 'false');
        }

        function saveCookiePreferences(preferences) {
            const payload = {
                essential: true,
                preference: !!preferences.preference,
                analytics: !!preferences.analytics,
                marketing: !!preferences.marketing,
                updated_at: new Date().toISOString(),
            };

            localStorage.setItem(cookieConsentStorageKey, JSON.stringify(payload));
            applyCookiePreferences(payload);

            if (cookieConsent) {
                cookieConsent.classList.remove('show');
            }

            closeCookieSettings();
        }

        function syncCookieTogglesFromSaved() {
            let saved = null;

            try {
                saved = JSON.parse(localStorage.getItem(cookieConsentStorageKey) || 'null');
            } catch (error) {
                saved = null;
            }

            const hasSaved = !!(saved && typeof saved === 'object');

            if (cookiePreferenceToggle) {
                cookiePreferenceToggle.checked = hasSaved ? !!saved.preference : false;
            }

            if (cookieAnalyticsToggle) {
                cookieAnalyticsToggle.checked = hasSaved ? !!saved.analytics : false;
            }

            if (cookieMarketingToggle) {
                cookieMarketingToggle.checked = hasSaved ? !!saved.marketing : false;
            }

            if (hasSaved) {
                applyCookiePreferences(saved);
            }

            if (cookieConsent && hasSaved) {
                cookieConsent.classList.remove('show');
            } else if (cookieConsent && !hasSaved) {
                setTimeout(function () {
                    if (cookieConsent) {
                        cookieConsent.classList.add('show');
                    }
                }, cookieBannerDelayMs);
            }
        }


        const pastorWhatsappBase = @json($pastorWhatsappUrl);
        const pastorEmailBase = @json($pastorEmailUrl);

        function closeEventsDropdown() {
            if (!eventsDropdown || !eventsDropdownTrigger) {
                return;
            }

            eventsDropdown.classList.remove('is-open');
            eventsDropdownTrigger.setAttribute('aria-expanded', 'false');
        }

        function closeMinistryDropdown() {
            if (!ministryDropdown || !ministryDropdownTrigger) {
                return;
            }

            ministryDropdown.classList.remove('is-open');
            ministryDropdownTrigger.setAttribute('aria-expanded', 'false');
        }

        function closeMediaDropdown() {
            if (!mediaDropdown || !mediaDropdownTrigger) {
                return;
            }

            mediaDropdown.classList.remove('is-open');
            mediaDropdownTrigger.setAttribute('aria-expanded', 'false');
        }

        function openMinistryDropdown() {
            if (!ministryDropdown || !ministryDropdownTrigger) {
                return;
            }

            ministryDropdown.classList.add('is-open');
            ministryDropdownTrigger.setAttribute('aria-expanded', 'true');
        }

        function openEventsDropdown() {
            if (!eventsDropdown || !eventsDropdownTrigger) {
                return;
            }

            eventsDropdown.classList.add('is-open');
            eventsDropdownTrigger.setAttribute('aria-expanded', 'true');
        }

        function openMediaDropdown() {
            if (!mediaDropdown || !mediaDropdownTrigger) {
                return;
            }

            mediaDropdown.classList.add('is-open');
            mediaDropdownTrigger.setAttribute('aria-expanded', 'true');
        }

        function closeAboutDropdown() {
            if (!aboutDropdown || !aboutDropdownTrigger) {
                return;
            }

            aboutDropdown.classList.remove('is-open');
            aboutDropdownTrigger.setAttribute('aria-expanded', 'false');
        }

        function openAboutDropdown() {
            if (!aboutDropdown || !aboutDropdownTrigger) {
                return;
            }

            aboutDropdown.classList.add('is-open');
            aboutDropdownTrigger.setAttribute('aria-expanded', 'true');
        }

        function closeEventSubmenus() {
            eventsDropdownItems.forEach(function (item) {
                item.classList.remove('is-open');
                const triggerLink = item.querySelector('.nav-dropdown-main');
                if (triggerLink) {
                    triggerLink.classList.remove('is-active');
                }
            });
        }

        function isMobileViewport() {
            return window.matchMedia('(max-width: 900px)').matches;
        }

        function supportsHover() {
            return window.matchMedia('(hover: hover) and (pointer: fine)').matches;
        }

        function setMobileNavOpen(isOpen) {
            if (!mobileNavToggle || !siteMainNav) {
                return;
            }

            siteMainNav.classList.toggle('is-open', isOpen);
            mobileNavToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        }

        function closeMobileNav() {
            setMobileNavOpen(false);
        }

        function closeNotificationMenu() {
            if (!notificationToggle || !notificationMenu) {
                return;
            }

            notificationMenu.classList.remove('is-open');
            notificationMenu.setAttribute('aria-hidden', 'true');
            notificationToggle.setAttribute('aria-expanded', 'false');
        }

        function markAdminUpdatesSeen() {
            if (!notificationToggle) {
                return;
            }

            const updatedAt = notificationToggle.getAttribute('data-admin-updated-at') || '';

            if (updatedAt === '') {
                return;
            }

            localStorage.setItem(adminUpdateSeenStorageKey, updatedAt);
        }

        function hasUnreadAdminUpdates() {
            if (!notificationToggle) {
                return false;
            }

            if (!canShowNotificationCounts()) {
                return false;
            }

            if (hasClearedCurrentAdminUpdates()) {
                return false;
            }

            const updatedAt = notificationToggle.getAttribute('data-admin-updated-at') || '';

            if (updatedAt === '') {
                return false;
            }

            const updatedAtMs = Date.parse(updatedAt);
            const seenAtMs = Date.parse(localStorage.getItem(adminUpdateSeenStorageKey) || '');

            if (!Number.isFinite(updatedAtMs)) {
                return false;
            }

            if (!Number.isFinite(seenAtMs)) {
                return true;
            }

            return seenAtMs < updatedAtMs;
        }

        function getAvailableAdminUpdatesCount() {
            if (!notificationToggle) {
                return 0;
            }

            if (!canShowNotificationCounts()) {
                return 0;
            }

            if (hasClearedCurrentAdminUpdates()) {
                return 0;
            }

            const rawCount = Number(notificationToggle.getAttribute('data-admin-updates-count') || '0');

            if (!Number.isFinite(rawCount) || rawCount < 0) {
                return 0;
            }

            return Math.floor(rawCount);
        }

        function formatAdminBadgeCount(count) {
            if (count > 99) {
                return '99+';
            }

            return String(count);
        }

        function canShowNotificationCounts() {
            if (!notificationToggle) {
                return false;
            }

            return notificationToggle.getAttribute('data-can-show-counts') === '1';
        }

        function buildNotificationMenuTitle() {
            if (!canShowNotificationCounts()) {
                return 'SDA MUBS Updates';
            }

            return 'SDA MUBS Updates (' + getAvailableAdminUpdatesCount() + ' available)';
        }

        function formatUpdateMeta(update) {
            const meta = String((update && update.meta) || '').trim();
            if (meta) {
                return meta;
            }

            const department = String((update && update.department) || '').trim();
            const dateRange = String((update && update.date_range) || '').trim();
            const month = String((update && update.month) || '').trim();

            if (department && dateRange) {
                return department + ' - ' + dateRange;
            }

            if (department) {
                return department;
            }

            if (dateRange) {
                return dateRange;
            }

            return month;
        }

        function renderNotificationItems(updates) {
            if (!notificationMenuList) {
                return;
            }

            notificationMenuList.innerHTML = '';

            if (!Array.isArray(updates) || updates.length === 0) {
                notificationMenuList.style.display = 'none';
                if (notificationMenuEmpty) {
                    notificationMenuEmpty.style.display = 'block';
                }
                return;
            }

            notificationMenuList.style.display = 'grid';
            if (notificationMenuEmpty) {
                notificationMenuEmpty.style.display = 'none';
            }

            updates.forEach(function (update) {
                const li = document.createElement('li');
                const link = document.createElement('a');
                link.href = String((update && update.url) || "{{ route('updates') }}").trim() || "{{ route('updates') }}";

                const title = document.createElement('span');
                title.className = 'notification-item-title';
                title.textContent = String((update && update.title) || 'Church update').trim() || 'Church update';
                link.appendChild(title);

                const metaText = formatUpdateMeta(update);
                if (metaText) {
                    const meta = document.createElement('span');
                    meta.className = 'notification-item-meta';
                    meta.textContent = metaText;
                    link.appendChild(meta);
                }

                li.appendChild(link);
                notificationMenuList.appendChild(li);
            });
        }

        function refreshLiveAdminUpdates() {
            if (!notificationToggle) {
                return;
            }

            const feedUrl = notificationToggle.getAttribute('data-updates-feed-url') || '';
            if (!feedUrl) {
                return;
            }

            fetch(feedUrl, {
                method: 'GET',
                headers: { 'Accept': 'application/json' },
                cache: 'no-store',
            }).then(function (response) {
                if (!response.ok) {
                    throw new Error('Failed to load updates feed');
                }

                return response.json();
            }).then(function (payload) {
                const count = Number(payload && payload.count ? payload.count : 0);
                const safeCount = Number.isFinite(count) && count >= 0 ? Math.floor(count) : 0;
                const updatedAt = String((payload && payload.updated_at) || '').trim();
                const updates = Array.isArray(payload && payload.updates) ? payload.updates : [];

                notificationToggle.setAttribute('data-admin-updates-count', String(safeCount));

                if (updatedAt) {
                    notificationToggle.setAttribute('data-admin-updated-at', updatedAt);
                }

                if (notificationMenuTitle) {
                    notificationMenuTitle.textContent = buildNotificationMenuTitle();
                }

                renderNotificationItems(hasClearedCurrentAdminUpdates() ? [] : updates);
                syncAdminUpdateBadge();
            }).catch(function () {
                // Keep existing values if live refresh fails.
            });
        }

        function hasClearedCurrentAdminUpdates() {
            if (!notificationToggle) {
                return false;
            }

            const updatedAt = notificationToggle.getAttribute('data-admin-updated-at') || '';
            const clearedAt = localStorage.getItem(adminUpdateClearedStorageKey) || '';
            const updatedAtMs = Date.parse(updatedAt);
            const clearedAtMs = Date.parse(clearedAt);

            if (!Number.isFinite(updatedAtMs) || !Number.isFinite(clearedAtMs)) {
                return false;
            }

            return clearedAtMs >= updatedAtMs;
        }

        function clearCurrentNotifications() {
            if (!notificationToggle) {
                return;
            }

            const updatedAt = notificationToggle.getAttribute('data-admin-updated-at') || '';
            const clearAt = updatedAt !== '' ? updatedAt : new Date().toISOString();
            localStorage.setItem(adminUpdateClearedStorageKey, clearAt);
            markAdminUpdatesSeen();

            if (notificationMenuTitle) {
                notificationMenuTitle.textContent = buildNotificationMenuTitle();
            }

            renderNotificationItems([]);
            syncAdminUpdateBadge();
        }

        function syncAdminUpdateBadge() {
            if (!adminUpdateBadge) {
                return;
            }

            const unreadCount = hasUnreadAdminUpdates() ? getAvailableAdminUpdatesCount() : 0;
            const isVisible = unreadCount > 0;

            adminUpdateBadge.textContent = isVisible ? formatAdminBadgeCount(unreadCount) : '';
            adminUpdateBadge.classList.toggle('is-visible', isVisible);
        }

        function openNotificationMenu() {
            if (!notificationToggle || !notificationMenu) {
                return;
            }

            notificationMenu.classList.add('is-open');
            notificationMenu.setAttribute('aria-hidden', 'false');
            notificationToggle.setAttribute('aria-expanded', 'true');
            markAdminUpdatesSeen();
            syncAdminUpdateBadge();
        }

        function updateRightBarThemeByScroll() {
            if (!sdaRightBar) {
                return;
            }

            const scrollY = window.scrollY || window.pageYOffset || 0;
            const nearFooter = scrollY > 180 && (window.innerHeight + scrollY >= document.documentElement.scrollHeight - 90);
            const inContentZone = scrollY > 120 && !nearFooter;

            sdaRightBar.classList.toggle('is-footer', nearFooter);
            sdaRightBar.classList.toggle('is-background', inContentZone);
            sdaRightBar.classList.toggle('on-light', inContentZone);
        }

        function closeAskPastorOptions() {
            if (!askPastorToggle || !askPastorOptions) {
                return;
            }

            askPastorOptions.classList.remove('show');
            askPastorToggle.setAttribute('aria-expanded', 'false');
            askPastorOptions.setAttribute('aria-hidden', 'true');
            askPastorToggle.classList.remove('is-open');
            showSupportStep();
            lastAskPastorToggleAt = Date.now();
        }

        function syncAskPastorReadingMode() {
            if (!askPastorToggle || !askPastorOptions) {
                return;
            }

            if (!isAskPastorReadingMobileViewport()) {
                askPastorToggle.classList.remove('is-reading');
                lastScrollY = window.scrollY || window.pageYOffset || 0;
                return;
            }

            const now = Date.now();
            const currentY = window.scrollY || window.pageYOffset || 0;
            const delta = currentY - lastScrollY;
            const optionsOpen = askPastorOptions.classList.contains('show');

            if (optionsOpen) {
                askPastorToggle.classList.remove('is-reading');
                lastScrollY = currentY;
                return;
            }

            if (currentY < 120) {
                askPastorToggle.classList.remove('is-reading');
                lastScrollY = currentY;
                return;
            }

            if (now - lastAskPastorToggleAt < 900) {
                lastScrollY = currentY;
                return;
            }

            if (delta > 8) {
                askPastorToggle.classList.add('is-reading');
            } else if (delta < -8) {
                askPastorToggle.classList.remove('is-reading');
            }

            lastScrollY = currentY;
        }

        function showSupportStep() {
            if (!askSupportStep || !askCommunicationStep) {
                return;
            }

            askSupportStep.classList.remove('hidden');
            askCommunicationStep.classList.add('hidden');
        }

        function showCommunicationStep(topicLabel, topicMessage) {
            if (!askSupportStep || !askCommunicationStep) {
                return;
            }

            askSupportStep.classList.add('hidden');
            askCommunicationStep.classList.remove('hidden');

            if (askPastorStepTitle) {
                askPastorStepTitle.textContent = topicLabel + ' - Choose communication method';
            }

            if (askMethodWhatsapp && pastorWhatsappBase !== '#') {
                const whatsappText = 'Hello Pastor, ' + topicMessage;
                askMethodWhatsapp.href = pastorWhatsappBase + '?text=' + encodeURIComponent(whatsappText);
            }

            if (askMethodEmail && pastorEmailBase !== '#') {
                const subject = topicLabel + ' - SDA CHURCH MUBS';
                const body = 'Hello Pastor,%0A%0A' + encodeURIComponent(topicMessage) + '%0A%0AThank you.';
                askMethodEmail.href = pastorEmailBase + '?subject=' + encodeURIComponent(subject) + '&body=' + body;
            }
        }

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeAskPastorOptions();
                closeMobileNav();
                closeMinistryDropdown();
                closeMediaDropdown();
                closeEventsDropdown();
                closeAboutDropdown();
                closeNotificationMenu();
                setSdaTextPanelOpen(false);
            }
        });

        if (ministryDropdown && ministryDropdownTrigger) {
            ministryDropdownTrigger.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();
                const shouldOpen = !ministryDropdown.classList.contains('is-open');

                if (shouldOpen) {
                    closeMediaDropdown();
                    closeEventsDropdown();
                    closeAboutDropdown();
                    openMinistryDropdown();
                    return;
                }

                closeMinistryDropdown();
            });

            if (supportsHover()) {
                ministryDropdownTrigger.addEventListener('mouseenter', function () {
                    openMinistryDropdown();
                });

                ministryDropdown.addEventListener('mouseleave', function () {
                    closeMinistryDropdown();
                });
            }

            document.addEventListener('click', function (event) {
                if (!ministryDropdown.contains(event.target)) {
                    closeMinistryDropdown();
                }
            });

            // Close dropdown when a link inside is clicked
            const ministryLinks = ministryDropdown.querySelectorAll('.nav-dropdown-panel a');
            ministryLinks.forEach(function (link) {
                link.addEventListener('click', function () {
                    closeMinistryDropdown();
                });
            });
        }

        if (eventsDropdown && eventsDropdownTrigger) {
            eventsDropdownTrigger.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();
                const shouldOpen = !eventsDropdown.classList.contains('is-open');

                if (shouldOpen) {
                    closeMinistryDropdown();
                    closeMediaDropdown();
                    closeAboutDropdown();
                    openEventsDropdown();
                    return;
                }

                closeEventsDropdown();
                closeEventSubmenus();
            });

            if (supportsHover()) {
                eventsDropdownTrigger.addEventListener('mouseenter', function () {
                    openEventsDropdown();
                });

                eventsDropdown.addEventListener('mouseleave', function () {
                    closeEventsDropdown();
                    closeEventSubmenus();
                });
            }

            eventsDropdownItems.forEach(function (item) {
                const triggerLink = item.querySelector('.nav-dropdown-main');

                if (!triggerLink) {
                    return;
                }

                if (supportsHover()) {
                    item.addEventListener('mouseenter', function () {
                        closeEventSubmenus();
                        item.classList.add('is-open');
                        triggerLink.classList.add('is-active');
                    });

                    item.addEventListener('mouseleave', function () {
                        item.classList.remove('is-open');
                        triggerLink.classList.remove('is-active');
                    });
                }

                triggerLink.addEventListener('click', function (event) {
                    if (supportsHover()) {
                        return;
                    }

                    event.preventDefault();
                    event.stopPropagation();

                    const shouldOpen = !item.classList.contains('is-open');
                    closeEventSubmenus();

                    if (shouldOpen) {
                        item.classList.add('is-open');
                        triggerLink.classList.add('is-active');
                    }
                });
            });

            document.addEventListener('click', function (event) {
                if (!eventsDropdown.contains(event.target)) {
                    closeEventsDropdown();
                    closeEventSubmenus();
                }
            });

            // Close dropdown when a link inside is clicked
            const eventsLinks = eventsDropdown.querySelectorAll('.nav-dropdown-panel a');
            eventsLinks.forEach(function (link) {
                link.addEventListener('click', function () {
                    closeEventsDropdown();
                    closeEventSubmenus();
                });
            });
        }

        if (mediaDropdown && mediaDropdownTrigger) {
            mediaDropdownTrigger.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();
                const shouldOpen = !mediaDropdown.classList.contains('is-open');

                if (shouldOpen) {
                    closeMinistryDropdown();
                    closeEventsDropdown();
                    closeAboutDropdown();
                    openMediaDropdown();
                    return;
                }

                closeMediaDropdown();
            });

            if (supportsHover()) {
                mediaDropdownTrigger.addEventListener('mouseenter', function () {
                    openMediaDropdown();
                });

                mediaDropdown.addEventListener('mouseleave', function () {
                    closeMediaDropdown();
                });
            }

            document.addEventListener('click', function (event) {
                if (!mediaDropdown.contains(event.target)) {
                    closeMediaDropdown();
                }
            });

            // Close dropdown when a link inside is clicked
            const mediaLinks = mediaDropdown.querySelectorAll('.nav-dropdown-panel a');
            mediaLinks.forEach(function (link) {
                link.addEventListener('click', function () {
                    closeMediaDropdown();
                });
            });
        }

        if (aboutDropdown && aboutDropdownTrigger) {
            aboutDropdownTrigger.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();
                const shouldOpen = !aboutDropdown.classList.contains('is-open');

                if (shouldOpen) {
                    closeMinistryDropdown();
                    closeEventsDropdown();
                    closeMediaDropdown();
                    openAboutDropdown();
                    return;
                }

                closeAboutDropdown();
            });

            if (supportsHover()) {
                aboutDropdownTrigger.addEventListener('mouseenter', function () {
                    openAboutDropdown();
                });

                aboutDropdown.addEventListener('mouseleave', function () {
                    closeAboutDropdown();
                });
            }

            document.addEventListener('click', function (event) {
                if (!aboutDropdown.contains(event.target)) {
                    closeAboutDropdown();
                }
            });

            // Close dropdown when a link inside is clicked
            const aboutLinks = aboutDropdown.querySelectorAll('.nav-dropdown-panel a');
            aboutLinks.forEach(function (link) {
                link.addEventListener('click', function () {
                    closeAboutDropdown();
                });
            });
        }

        if (notificationToggle && notificationMenu) {
            notificationToggle.addEventListener('click', function () {
                const shouldOpen = !notificationMenu.classList.contains('is-open');

                if (!shouldOpen) {
                    closeNotificationMenu();
                    return;
                }

                openNotificationMenu();
            });

            document.addEventListener('click', function (event) {
                if (!notificationMenu.contains(event.target) && !notificationToggle.contains(event.target)) {
                    closeNotificationMenu();
                }
            });

            if (clearNotificationsBtn) {
                clearNotificationsBtn.addEventListener('click', function () {
                    clearCurrentNotifications();
                });
            }

            syncAdminUpdateBadge();
            refreshLiveAdminUpdates();
            window.setInterval(refreshLiveAdminUpdates, 20000);
        }

        if (askPastorToggle && askPastorOptions) {
            askPastorToggle.addEventListener('click', function () {
                const shouldOpen = !askPastorOptions.classList.contains('show');

                if (!shouldOpen) {
                    closeAskPastorOptions();
                    return;
                }

                askPastorOptions.classList.add('show');
                askPastorToggle.setAttribute('aria-expanded', 'true');
                askPastorOptions.setAttribute('aria-hidden', 'false');
                askPastorToggle.classList.add('is-open');
                askPastorToggle.classList.remove('is-reading');
                lastAskPastorToggleAt = Date.now();
                showSupportStep();
            });

            document.addEventListener('click', function (event) {
                if (!askPastorOptions.contains(event.target) && !askPastorToggle.contains(event.target)) {
                    closeAskPastorOptions();
                }
            });

            askPastorToggle.addEventListener('mouseenter', function () {
                askPastorToggle.classList.remove('is-reading');
            });

            askPastorToggle.addEventListener('focus', function () {
                askPastorToggle.classList.remove('is-reading');
            });
        }

        if (sdaLogoBtn && sdaLogoTextPanel) {
            sdaLogoBtn.addEventListener('click', function () {
                const shouldOpen = !sdaLogoTextPanel.classList.contains('show');

                if (!shouldOpen) {
                    setSdaTextPanelOpen(false);
                    return;
                }

                setSdaTextPanelOpen(true);
            });

            document.addEventListener('click', function (event) {
                if (!sdaLogoTextPanel.contains(event.target) && !sdaLogoBtn.contains(event.target)) {
                    setSdaTextPanelOpen(false);
                }
            });
        }

        if (mobileNavToggle && siteMainNav) {
            mobileNavToggle.addEventListener('click', function () {
                const shouldOpen = !siteMainNav.classList.contains('is-open');
                setMobileNavOpen(shouldOpen);
            });

            siteMainNav.addEventListener('click', function (event) {
                if (!isMobileViewport()) {
                    return;
                }

                const target = event.target;
                if (!(target instanceof Element)) {
                    return;
                }

                if (target.closest('a')) {
                    closeMobileNav();
                }
            });

            window.addEventListener('resize', function () {
                if (!isMobileViewport()) {
                    setMobileNavOpen(false);
                }
            });
        }

        // Zoom overlay for D LOGO image
        const sdaLogoMainImg = document.getElementById('sdaLogoMainImg');
        const sdaLogoLens = document.getElementById('sdaLogoLens');
        const sdaLogoZoomOverlay = document.getElementById('sdaLogoZoomOverlay');
        const sdaZoomClose = document.getElementById('sdaZoomClose');

        function openLogoZoom() {
            if (sdaLogoZoomOverlay) {
                sdaLogoZoomOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeLogoZoom() {
            if (sdaLogoZoomOverlay) {
                sdaLogoZoomOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        }

        if (sdaLogoMainImg) {
            sdaLogoMainImg.addEventListener('click', openLogoZoom);
        }

        if (sdaLogoMainImg && sdaLogoLens) {
            const lensZoom = 2.5;

            const hideLens = function () {
                sdaLogoLens.classList.remove('show');
            };

            const moveLens = function (clientX, clientY) {
                const rect = sdaLogoMainImg.getBoundingClientRect();
                const x = Math.max(0, Math.min(clientX - rect.left, rect.width));
                const y = Math.max(0, Math.min(clientY - rect.top, rect.height));
                const lensRadius = sdaLogoLens.offsetWidth / 2;

                sdaLogoLens.style.left = x + 'px';
                sdaLogoLens.style.top = y + 'px';
                sdaLogoLens.style.backgroundImage = 'url("' + sdaLogoMainImg.currentSrc + '")';
                sdaLogoLens.style.backgroundSize = (rect.width * lensZoom) + 'px ' + (rect.height * lensZoom) + 'px';
                sdaLogoLens.style.backgroundPosition =
                    '-' + ((x * lensZoom) - lensRadius) + 'px ' +
                    '-' + ((y * lensZoom) - lensRadius) + 'px';
            };

            sdaLogoMainImg.addEventListener('mouseenter', function (event) {
                if (window.matchMedia('(hover: hover)').matches) {
                    sdaLogoLens.classList.add('show');
                    moveLens(event.clientX, event.clientY);
                }
            });

            sdaLogoMainImg.addEventListener('mousemove', function (event) {
                moveLens(event.clientX, event.clientY);
            });

            sdaLogoMainImg.addEventListener('mouseleave', hideLens);
            sdaLogoMainImg.addEventListener('touchstart', hideLens, { passive: true });
            document.addEventListener('scroll', hideLens, { passive: true });
            window.addEventListener('resize', hideLens);
        }

        if (sdaLogoZoomOverlay) {
            sdaLogoZoomOverlay.addEventListener('click', function (e) {
                if (e.target === sdaLogoZoomOverlay || e.target === sdaZoomClose) {
                    closeLogoZoom();
                }
            });
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') { closeLogoZoom(); }
        });

        if (cookieAcceptBtn) {
            cookieAcceptBtn.addEventListener('click', function () {
                saveCookiePreferences({
                    preference: true,
                    analytics: true,
                    marketing: true,
                });
            });
        }

        if (cookieRejectBtn) {
            cookieRejectBtn.addEventListener('click', function () {
                saveCookiePreferences({
                    preference: false,
                    analytics: false,
                    marketing: false,
                });
            });
        }

        if (cookieSettingsBtn) {
            cookieSettingsBtn.addEventListener('click', function () {
                syncCookieTogglesFromSaved();
                openCookieSettings();
            });
        }

        if (cookieSettingsClose) {
            cookieSettingsClose.addEventListener('click', closeCookieSettings);
        }

        if (cookieSaveBtn) {
            cookieSaveBtn.addEventListener('click', function () {
                saveCookiePreferences({
                    preference: !!(cookiePreferenceToggle && cookiePreferenceToggle.checked),
                    analytics: !!(cookieAnalyticsToggle && cookieAnalyticsToggle.checked),
                    marketing: !!(cookieMarketingToggle && cookieMarketingToggle.checked),
                });
            });
        }

        if (cookieSettingsModal) {
            cookieSettingsModal.addEventListener('click', function (event) {
                if (event.target === cookieSettingsModal) {
                    closeCookieSettings();
                }
            });
        }

        askTopicOptions.forEach(function (topicButton) {
            topicButton.addEventListener('click', function () {
                const topicLabel = topicButton.getAttribute('data-topic-label') || 'Support';
                const topicMessage = topicButton.getAttribute('data-topic-message') || 'I need support.';
                showCommunicationStep(topicLabel, topicMessage);
            });
        });

        if (askPastorBack) {
            askPastorBack.addEventListener('click', showSupportStep);
        }


        function closeAllDropdowns() {
            closeEventsDropdown();
            closeMinistryDropdown();
            closeMediaDropdown();
            closeAboutDropdown();
            closeNotificationMenu();
            closeEventSubmenus();
        }

        // Initialize fellowship accordions
        function initFellowshipAccordions() {
            const accordions = document.querySelectorAll('.fellowship-accordion');

            accordions.forEach(function (accordion) {
                const items = accordion.querySelectorAll('.fellowship-item');

                items.forEach(function (item) {
                    const trigger = item.querySelector('.fellowship-trigger');

                    if (trigger) {
                        trigger.addEventListener('click', function () {
                            const isAlreadyActive = item.classList.contains('active');

                            // Close all items in this accordion
                            items.forEach(function (otherItem) {
                                otherItem.classList.remove('active');
                                const btn = otherItem.querySelector('.fellowship-trigger');
                                if (btn) btn.setAttribute('aria-expanded', 'false');
                            });

                            // Open this item if it wasn't already open
                            if (!isAlreadyActive) {
                                item.classList.add('active');
                                trigger.setAttribute('aria-expanded', 'true');
                            }
                        });
                    }
                });
            });
        }

        initFellowshipAccordions();

        window.addEventListener('scroll', function () {
            if (!isMobileViewport()) {
                closeAllDropdowns();
            }
            syncAskPastorReadingMode();
        }, { passive: true });

        document.addEventListener('click', function (event) {
            const isClickInsideDropdown =
                (siteMainNav && siteMainNav.contains(event.target)) ||
                (mobileNavToggle && mobileNavToggle.contains(event.target)) ||
                (eventsDropdown && eventsDropdown.contains(event.target)) ||
                (ministryDropdown && ministryDropdown.contains(event.target)) ||
                (mediaDropdown && mediaDropdown.contains(event.target)) ||
                (aboutDropdown && aboutDropdown.contains(event.target)) ||
                (notificationMenu && notificationMenu.contains(event.target)) ||
                (eventsDropdownTrigger && eventsDropdownTrigger.contains(event.target)) ||
                (ministryDropdownTrigger && ministryDropdownTrigger.contains(event.target)) ||
                (mediaDropdownTrigger && mediaDropdownTrigger.contains(event.target)) ||
                (aboutDropdownTrigger && aboutDropdownTrigger.contains(event.target)) ||
                (notificationToggle && notificationToggle.contains(event.target));

            if (!isClickInsideDropdown) {
                closeAllDropdowns();
                if (isMobileViewport()) {
                    closeMobileNav();
                }
            }
        });
        
        window.addEventListener('scroll', updateRightBarThemeByScroll, { passive: true });
        window.addEventListener('resize', updateRightBarThemeByScroll);
        window.addEventListener('resize', syncAskPastorReadingMode);
        updateRightBarThemeByScroll();
        syncAskPastorReadingMode();
        syncCookieTogglesFromSaved();
    })();
</script>
</body>
</html>

