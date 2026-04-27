@extends('layouts.site')

@section('title', 'SDA CHURCH MUBS | Forum')

@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700;800&family=Space+Grotesk:wght@500;700&display=swap');

        .forum-page {
            --forum-bg: #eef4ff;
            --forum-surface: #ffffff;
            --forum-surface-soft: #f5f8ff;
            --forum-primary: #0f2b55;
            --forum-secondary: #1f4a8a;
            --forum-accent: #f04e37;
            --forum-text: #0f2038;
            --forum-muted: #4d607d;
            --forum-border: #c9d8ee;
            --forum-shadow: 0 20px 40px rgba(15, 43, 85, 0.12);
            --forum-radius: 16px;
            --forum-font: 'DM Sans', 'Segoe UI', sans-serif;
            --forum-wallpaper: none;
            --forum-wallpaper-overlay: linear-gradient(135deg, rgba(255, 255, 255, 0.78) 0%, rgba(238, 244, 255, 0.62) 48%, rgba(245, 249, 255, 0.72) 100%);
            display: grid;
            gap: 1rem;
            padding: clamp(1rem, 2vw, 1.5rem);
            border-radius: 28px;
            color: var(--forum-text);
            font-family: var(--forum-font);
            background-color: var(--forum-bg);
            background-image: var(--forum-wallpaper-overlay), var(--forum-wallpaper);
            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;
            position: relative;
            overflow: hidden;
        }

        .forum-page::before {
            content: '';
            position: absolute;
            inset: 0;
            pointer-events: none;
            background:
                radial-gradient(circle at top right, rgba(31, 74, 138, 0.12), transparent 32%),
                radial-gradient(circle at left bottom, rgba(240, 78, 55, 0.08), transparent 26%);
        }

        .forum-page > * {
            position: relative;
            z-index: 1;
        }

        .forum-intro {
            display: grid;
            gap: 0.4rem;
            padding: 1rem 1.1rem;
            border: 1px solid rgba(201, 216, 238, 0.9);
            border-radius: calc(var(--forum-radius) + 8px);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.92), rgba(241, 246, 255, 0.76));
            box-shadow: var(--forum-shadow);
            backdrop-filter: blur(14px);
        }

        .forum-intro-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--forum-border);
        }

        .forum-intro-logo {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--forum-primary), var(--forum-secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-weight: 900;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .forum-intro-brand {
            display: grid;
            gap: 2px;
        }

        .forum-intro-brand-title {
            margin: 0;
            color: var(--forum-primary);
            font-size: 0.95rem;
            font-weight: 900;
            letter-spacing: 0.02em;
            text-transform: uppercase;
        }

        .forum-intro-brand-subtitle {
            margin: 0;
            color: var(--forum-muted);
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.03em;
        }

        .forum-heading {
            margin: 0;
            color: #ffffff;
            background: linear-gradient(135deg, var(--forum-primary), var(--forum-secondary));
            border-radius: calc(var(--forum-radius) - 6px);
            padding: 0.8rem 1rem;
            font-size: clamp(1.4rem, 3vw, 2rem);
            line-height: 1.2;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            box-shadow: 0 12px 22px rgba(15, 43, 85, 0.16);
        }

        .forum-lead { margin: 0.4rem 0 0; color: var(--forum-muted); line-height: 1.6; max-width: 920px; font-size: 0.95rem; }

        .forum-icon-btn {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, 0.36);
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            cursor: pointer;
            transition: transform 0.18s ease, background 0.18s ease, border-color 0.18s ease;
        }

        .forum-icon-btn:hover {
            transform: translateY(-1px);
            background: rgba(255, 255, 255, 0.22);
            border-color: rgba(255, 255, 255, 0.62);
        }

        .forum-icon-btn svg {
            width: 1rem;
            height: 1rem;
            display: block;
        }

        .forum-media-toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            align-items: center;
        }

        .forum-media-btn {
            border: 1px solid var(--forum-border);
            background: #ffffff;
            color: var(--forum-secondary);
            border-radius: 10px;
            width: 2.5rem;
            height: 2.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: transform 0.18s ease, border-color 0.18s ease, background 0.18s ease;
        }

        .forum-media-btn:hover:not(:disabled) {
            transform: translateY(-1px);
            border-color: var(--forum-secondary);
            background: rgba(31, 74, 138, 0.08);
        }

        .forum-media-btn.active {
            color: #ffffff;
            border-color: var(--forum-secondary);
            background: linear-gradient(135deg, var(--forum-primary), var(--forum-secondary));
        }

        .forum-media-btn.stop {
            color: #8f1d1d;
            border-color: rgba(143, 29, 29, 0.25);
            background: rgba(143, 29, 29, 0.08);
        }

        .forum-media-btn:disabled {
            opacity: 0.55;
            cursor: not-allowed;
        }

        .forum-media-btn svg {
            width: 1.1rem;
            height: 1.1rem;
            display: block;
        }

        .forum-media-status {
            margin: 0;
            color: var(--forum-muted);
            font-size: 0.8rem;
            font-weight: 700;
        }

        .forum-media-preview {
            width: 100%;
            max-width: 420px;
            border-radius: 10px;
            border: 1px solid var(--forum-border);
            background: #0b1320;
        }

        .forum-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 280px;
            gap: 0.9rem;
            align-items: start;
        }

        .forum-room {
            border: 1px solid var(--forum-border);
            border-radius: var(--forum-radius);
            background: rgba(255, 255, 255, 0.86);
            overflow: hidden;
            box-shadow: var(--forum-shadow);
            backdrop-filter: blur(10px);
        }

        .forum-room-head {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 0.55rem;
            align-items: center;
            padding: 0.65rem 0.8rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            background: linear-gradient(135deg, var(--forum-primary) 0%, var(--forum-secondary) 100%);
        }

        .forum-room-head h2 { margin: 0; color: #ffffff; font-size: 1rem; text-transform: uppercase; }
        .forum-head-actions { display: flex; gap: 0.4rem; align-items: center; }

        .forum-btn {
            border: 1px solid rgba(255, 255, 255, 0.42);
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            border-radius: 6px;
            padding: 0.32rem 0.55rem;
            font-weight: 700;
            cursor: pointer;
            font-size: 0.82rem;
        }

        .forum-btn:hover {
            background: rgba(255, 255, 255, 0.28);
            border-color: rgba(255, 255, 255, 0.8);
        }

        .forum-btn.danger {
            border-color: rgba(255, 255, 255, 0.22);
            background: rgba(255, 255, 255, 0.9);
            color: var(--forum-primary);
        }

        .forum-btn.danger:hover {
            border-color: rgba(255, 255, 255, 0.38);
            background: rgba(255, 255, 255, 1);
        }

        .forum-status {
            margin: 0;
            padding: 0.45rem 0.8rem 0;
            color: var(--forum-secondary);
            font-size: 0.82rem;
            font-weight: 700;
        }

        .forum-typing {
            margin: 0;
            padding: 0.3rem 0.8rem 0;
            color: var(--forum-muted);
            font-size: 0.8rem;
            font-weight: 700;
            min-height: 1.2rem;
        }

        .forum-search-wrap {
            padding: 0.6rem 0.8rem 0;
        }

        .forum-search {
            width: 100%;
            border: 1px solid var(--forum-border);
            border-radius: 8px;
            padding: 0.48rem 0.55rem;
            font: inherit;
            color: var(--forum-text);
            background: #ffffff;
        }

        .forum-messages {
            list-style: none;
            margin: 0;
            padding: 0.75rem;
            display: grid;
            gap: 0.6rem;
            max-height: 460px;
            overflow: auto;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.96) 0%, rgba(243, 248, 255, 0.95) 100%);
        }

        .forum-message {
            display: flex;
            gap: 0.5rem;
            align-items: flex-start;
            padding: 0.4rem 0;
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .forum-message-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--forum-primary) 0%, var(--forum-secondary) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.75rem;
            flex-shrink: 0;
        }

        .forum-message.mine .forum-message-avatar {
            background: linear-gradient(135deg, var(--forum-accent) 0%, var(--forum-secondary) 100%);
        }

        .forum-message-bubble {
            display: grid;
            gap: 0.25rem;
            flex: 1;
            background: var(--forum-surface-soft);
            border-radius: calc(var(--forum-radius) - 2px);
            padding: 0.5rem 0.65rem;
            border: 1px solid var(--forum-border);
        }

        .forum-message.mine .forum-message-bubble {
            background: linear-gradient(135deg, var(--forum-primary), var(--forum-secondary));
            border: 1px solid var(--forum-primary);
        }

        .forum-message-meta {
            margin: 0;
            color: var(--forum-secondary);
            font-size: 0.7rem;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            gap: 0.5rem;
        }

        .forum-message.mine .forum-message-meta {
            color: rgba(255, 255, 255, 0.86);
        }

        .forum-message-timestamp {
            font-size: 0.65rem;
            color: var(--forum-muted);
        }

        .forum-message.mine .forum-message-timestamp {
            color: rgba(255, 255, 255, 0.72);
        }

        .forum-message-reply {
            border-left: 3px solid var(--forum-accent);
            padding-left: 0.5rem;
            color: var(--forum-muted);
            font-size: 0.79rem;
            line-height: 1.4;
        }

        .forum-message-body {
            margin: 0;
            color: var(--forum-text);
            line-height: 1.55;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .forum-message.mine .forum-message-body {
            color: #ffffff;
        }

        .forum-message-body a {
            color: var(--forum-secondary);
            text-decoration: underline;
            text-underline-offset: 2px;
            overflow-wrap: anywhere;
        }

        .forum-message.mine .forum-message-body a {
            color: #ffffff;
        }

        .forum-message-body a:hover {
            color: var(--forum-primary);
        }

        .forum-attachment {
            margin-top: 0.32rem;
            border: 1px solid var(--forum-border);
            border-radius: calc(var(--forum-radius) - 2px);
            background: #ffffff;
            padding: 0.45rem;
            display: grid;
            gap: 0.45rem;
            max-width: min(100%, 420px);
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(15, 43, 85, 0.08);
        }

        .forum-message.mine .forum-attachment {
            border-color: rgba(255, 255, 255, 0.34);
            background: rgba(255, 255, 255, 0.92);
        }

        .forum-attachment-preview {
            position: relative;
            border: 1px solid var(--forum-border);
            border-radius: 10px;
            background: linear-gradient(180deg, rgba(244, 248, 255, 0.95) 0%, rgba(234, 241, 255, 0.98) 100%);
            min-height: 110px;
            max-height: 180px;
            overflow: hidden;
            display: grid;
            place-items: center;
        }

        .forum-attachment-preview img,
        .forum-attachment-preview video,
        .forum-attachment-preview iframe {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
            border: 0;
        }

        .forum-attachment-preview audio {
            width: calc(100% - 0.7rem);
            margin: 0.35rem;
        }

        .forum-attachment-generic {
            display: grid;
            place-items: center;
            width: 100%;
            min-height: 108px;
            color: #334f74;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 0.82rem;
            text-align: center;
            padding: 0.7rem;
        }

        .forum-attachment-corner {
            position: absolute;
            right: 0;
            bottom: 0;
            width: 0;
            height: 0;
            border-left: 22px solid transparent;
            border-top: 22px solid var(--forum-accent);
            opacity: 0.9;
        }

        .forum-attachment-meta {
            display: flex;
            align-items: center;
            gap: 0.48rem;
            min-width: 0;
        }

        .forum-attachment-badge {
            border-radius: 4px;
            padding: 0.14rem 0.34rem;
            background: var(--forum-accent);
            color: #ffffff;
            font-size: 0.62rem;
            font-weight: 900;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            flex-shrink: 0;
        }

        .forum-attachment-name {
            margin: 0;
            color: var(--forum-primary);
            font-size: 0.84rem;
            font-weight: 800;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .forum-message.mine .forum-attachment-name {
            color: var(--forum-primary);
        }

        .forum-attachment-actions {
            display: flex;
            gap: 0.4rem;
            flex-wrap: wrap;
        }

        .forum-attachment-link {
            border: 1px solid var(--forum-border);
            background: #ffffff;
            color: var(--forum-secondary);
            border-radius: 999px;
            padding: 0.22rem 0.56rem;
            font-weight: 800;
            font-size: 0.73rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .forum-attachment-link:hover {
            background: rgba(31, 74, 138, 0.08);
            border-color: rgba(31, 74, 138, 0.38);
        }

        .forum-message.mine .forum-attachment-link {
            border-color: rgba(31, 74, 138, 0.2);
            color: var(--forum-primary);
        }

        .forum-edit-box {
            display: grid;
            gap: 0.35rem;
        }

        .forum-edit-input {
            width: 100%;
            border: 1px solid var(--forum-border);
            border-radius: 8px;
            padding: 0.48rem 0.54rem;
            font: inherit;
            color: var(--forum-text);
            background: #ffffff;
            min-height: 88px;
            resize: vertical;
        }

        .forum-edit-actions {
            display: flex;
            gap: 0.35rem;
            flex-wrap: wrap;
        }

        .forum-message-actions {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 0.4rem;
            flex-wrap: wrap;
            padding-top: 0.3rem;
            margin-left: 40px;
        }

        .forum-message.mine .forum-message-actions {
            justify-content: flex-end;
            margin-left: 0;
            margin-right: 40px;
        }

        .forum-inline-actions,
        .forum-reactions {
            display: flex;
            gap: 0.35rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .forum-inline-btn,
        .forum-react-btn {
            border: 1px solid var(--forum-border);
            background: #ffffff;
            color: var(--forum-secondary);
            border-radius: 999px;
            padding: 0.22rem 0.52rem;
            font-size: 0.75rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .forum-inline-btn:hover,
        .forum-react-btn:hover {
            background: rgba(31, 74, 138, 0.08);
            border-color: var(--forum-secondary);
            transform: scale(1.05);
        }

        .forum-react-btn.active {
            background: var(--forum-secondary);
            color: #ffffff;
            border-color: var(--forum-secondary);
            transform: scale(1.1);
        }

        .forum-empty {
            margin: 0;
            color: var(--forum-muted);
            border: 1px dashed var(--forum-border);
            border-radius: 8px;
            padding: 0.7rem;
            background: rgba(247, 250, 255, 0.85);
        }

        .forum-form {
            display: grid;
            gap: 0.58rem;
            border-top: 1px solid var(--forum-border);
            padding: 0.8rem;
            background: #ffffff;
        }

        .forum-form-row { display: grid; grid-template-columns: 170px 1fr; gap: 0.55rem; }

        .forum-input,
        .forum-textarea {
            width: 100%;
            border: 1px solid var(--forum-border);
            border-radius: 8px;
            padding: 0.52rem 0.58rem;
            font: inherit;
            color: var(--forum-text);
            background: #ffffff;
        }

        .forum-compose-row {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 0.45rem;
            align-items: end;
        }

        .forum-textarea { min-height: 120px; resize: vertical; }

        .forum-reply-pill {
            display: none;
            align-items: center;
            justify-content: space-between;
            gap: 0.4rem;
            border: 1px solid var(--forum-border);
            background: rgba(242, 247, 255, 0.92);
            color: var(--forum-secondary);
            border-radius: 8px;
            padding: 0.4rem 0.52rem;
            font-size: 0.82rem;
            font-weight: 700;
        }

        .forum-reply-pill.show { display: flex; }

        .forum-send {
            justify-self: start;
            border: 1px solid var(--forum-accent);
            background: linear-gradient(135deg, var(--forum-primary), var(--forum-accent));
            color: #ffffff;
            border-radius: 8px;
            padding: 0.45rem 0.88rem;
            font-weight: 700;
            cursor: pointer;
        }

        .forum-note { margin: 0; font-size: 0.8rem; color: var(--forum-muted); }

        .forum-side {
            border: 1px solid var(--forum-border);
            border-radius: var(--forum-radius);
            background: rgba(255, 255, 255, 0.86);
            padding: 0.75rem;
            display: grid;
            gap: 0.65rem;
            box-shadow: var(--forum-shadow);
            backdrop-filter: blur(10px);
        }

        .forum-side h3 { margin: 0; color: var(--forum-primary); text-transform: uppercase; font-size: 0.95rem; }
        .forum-online-count { margin: 0; color: var(--forum-secondary); font-weight: 700; font-size: 0.85rem; }

        .forum-online-list {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            gap: 0.35rem;
            max-height: 250px;
            overflow: auto;
        }

        .forum-online-list li {
            border: 1px solid var(--forum-border);
            background: rgba(248, 251, 255, 0.92);
            border-radius: 8px;
            padding: 0.35rem 0.48rem;
            color: var(--forum-primary);
            font-weight: 700;
            font-size: 0.82rem;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        .forum-online-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #3ca54a;
            display: inline-block;
            flex-shrink: 0;
        }

        @media (max-width: 980px) {
            .forum-layout { grid-template-columns: 1fr; }
            .forum-side { max-height: 200px; }
        }

        @media (max-width: 760px) {
            .forum-room-head {
                grid-template-columns: 1fr;
                align-items: stretch;
            }

            .forum-head-actions {
                justify-content: flex-start;
                flex-wrap: wrap;
                gap: 0.3rem;
            }

            .forum-btn {
                position: relative;
                z-index: 1;
            }

            .forum-form-row { grid-template-columns: 1fr; }
            .forum-compose-row { grid-template-columns: 1fr; }
            .forum-media-toolbar { justify-content: flex-start; }
            .forum-messages { max-height: 380px; font-size: 0.85rem; }
            .forum-message-avatar { width: 28px; height: 28px; font-size: 0.65rem; }
            .forum-message-bubble { border-radius: 10px; padding: 0.4rem 0.55rem; }
            .forum-react-btn { padding: 0.16rem 0.44rem; font-size: 0.65rem; }
            .forum-inline-btn { padding: 0.16rem 0.44rem; font-size: 0.65rem; }
            .forum-form-row { border-bottom: 1px solid #e8ecf5; padding-bottom: 0.5rem; margin-bottom: 0.3rem; }
        }

        @media (max-width: 560px) {
            .forum-intro-header {
                gap: 10px;
            }

            .forum-intro-logo {
                width: 36px;
                height: 36px;
                font-size: 1rem;
            }

            .forum-intro-brand-title {
                font-size: 0.88rem;
            }

            .forum-intro-brand-subtitle {
                font-size: 0.7rem;
            }

            .forum-head-actions {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                width: 100%;
            }

            .forum-btn {
                width: 100%;
                text-align: center;
                white-space: nowrap;
                padding: 0.34rem 0.45rem;
                font-size: 0.74rem;
            }

            .forum-messages { max-height: 340px; }
            .forum-message-meta { font-size: 0.65rem; }
            .forum-message-avatar { width: 24px; height: 24px; font-size: 0.55rem; }
            .forum-heading { font-size: clamp(1.1rem, 2vw, 1.6rem); }
            .forum-lead { font-size: 0.9rem; }
        }
    </style>

    <section class="forum-page" aria-label="Church forum">
        <section class="forum-intro">
            <div class="forum-intro-header">
                <div class="forum-intro-logo">🙏</div>
                <div class="forum-intro-brand">
                    <p class="forum-intro-brand-title">SDA MUBS</p>
                    <p class="forum-intro-brand-subtitle">Kireka Hill District</p>
                </div>
            </div>
            <h1 class="forum-heading">SDA Church MUBS Live Chat</h1>
            <p class="forum-lead">Live community discussion with reactions, replies, editing, deleting, typing indicators, and online presence.</p>
        </section>

        <section class="forum-layout">
            <section class="forum-room" aria-label="Forum room">
                <div class="forum-room-head">
                    <h2>Community Discussion</h2>
                    <div class="forum-head-actions">
                        <button id="forumRefreshBtn" class="forum-btn" type="button">Refresh</button>
                        <button id="forumDeleteAllBtn" class="forum-btn danger" type="button">Delete All My Messages</button>
                    </div>
                </div>

                <p id="forumStatus" class="forum-status">Connecting...</p>
                <p id="forumTyping" class="forum-typing"></p>

                <div class="forum-search-wrap">
                    <input id="forumSearch" class="forum-search" type="text" placeholder="Search messages by name/topic/text">
                </div>

                <ul id="forumMessages" class="forum-messages" aria-live="polite">
                    <li id="forumEmpty" class="forum-empty">No messages yet. Start the conversation.</li>
                </ul>

                <form id="forumForm" class="forum-form" novalidate>
                    <div id="forumReplyPill" class="forum-reply-pill">
                        <span id="forumReplyText"></span>
                        <button id="forumReplyClear" type="button" class="forum-inline-btn">Cancel</button>
                    </div>
                    <div class="forum-form-row">
                        <input id="forumName" class="forum-input" type="text" maxlength="60" placeholder="Your name" required>
                        <input id="forumTopic" class="forum-input" type="text" maxlength="100" placeholder="Topic">
                    </div>
                    <div class="forum-compose-row">
                        <textarea id="forumMessage" class="forum-textarea" maxlength="1200" placeholder="Type your message..." required></textarea>
                        <div class="forum-media-toolbar" aria-label="Voice and video tools">
                            <button id="forumVoiceNoteBtn" class="forum-media-btn icon" type="button" title="Record voice note" aria-label="Record voice note">
                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M12 3.2a3 3 0 0 0-3 3v5.6a3 3 0 0 0 6 0V6.2a3 3 0 0 0-3-3Z" stroke="currentColor" stroke-width="1.8"/>
                                    <path d="M6.8 10.8v.8a5.2 5.2 0 0 0 10.4 0v-.8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M12 16.9v3.2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M9.6 20.1h4.8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                </svg>
                            </button>
                            <button id="forumVideoNoteBtn" class="forum-media-btn icon" type="button" title="Record video note" aria-label="Record video note">
                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <rect x="3.3" y="6.4" width="12.4" height="11.2" rx="2.2" stroke="currentColor" stroke-width="1.8"/>
                                    <path d="M15.7 10.1 20.7 7.9v8.2l-5-2.2v-3.8Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                </svg>
                            </button>
                            <button id="forumStopMediaBtn" class="forum-media-btn icon stop" type="button" title="Stop recording" aria-label="Stop recording" disabled>
                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <rect x="7" y="7" width="10" height="10" rx="2" fill="currentColor"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <p id="forumMediaStatus" class="forum-media-status">Ready for voice or video notes.</p>
                    <video id="forumMediaPreview" class="forum-media-preview" autoplay muted playsinline hidden></video>
                    <input id="forumAttachment" class="forum-input" type="file" aria-label="Attach file">
                    <button class="forum-send" type="submit">Post Message</button>

                </form>
            </section>

            <aside class="forum-side" aria-label="Forum sidebar">
                <h3>Online Users</h3>
                <p id="forumOnlineCount" class="forum-online-count">Online: 0</p>
                <ul id="forumOnlineList" class="forum-online-list"></ul>
            </aside>
        </section>
    </section>

    <script>
        (function () {
            const routes = {
                stream: '{{ route('forum.stream') }}',
                list: '{{ route('forum.messages') }}',
                post: '{{ route('forum.messages.store') }}',
                deleteAll: '{{ route('forum.messages.destroy_all') }}',
                updateBase: '{{ url('/forum/messages') }}',
                reactBase: '{{ url('/forum/messages') }}',
                presencePing: '{{ route('forum.presence.heartbeat') }}',
                presenceList: '{{ route('forum.presence') }}',
                typingPing: '{{ route('forum.typing') }}',
                typingList: '{{ route('forum.typing.users') }}',
            };

            const csrf = '{{ csrf_token() }}';
            const form = document.getElementById('forumForm');
            const nameInput = document.getElementById('forumName');
            const topicInput = document.getElementById('forumTopic');
            const messageInput = document.getElementById('forumMessage');
            const attachmentInput = document.getElementById('forumAttachment');
            const refreshBtn = document.getElementById('forumRefreshBtn');
            const deleteAllBtn = document.getElementById('forumDeleteAllBtn');
            const statusText = document.getElementById('forumStatus');
            const typingText = document.getElementById('forumTyping');
            const searchInput = document.getElementById('forumSearch');
            const list = document.getElementById('forumMessages');
            const empty = document.getElementById('forumEmpty');
            const onlineCount = document.getElementById('forumOnlineCount');
            const onlineList = document.getElementById('forumOnlineList');
            const replyPill = document.getElementById('forumReplyPill');
            const replyText = document.getElementById('forumReplyText');
            const replyClear = document.getElementById('forumReplyClear');
            const voiceNoteBtn = document.getElementById('forumVoiceNoteBtn');
            const videoNoteBtn = document.getElementById('forumVideoNoteBtn');
            const stopMediaBtn = document.getElementById('forumStopMediaBtn');
            const mediaStatus = document.getElementById('forumMediaStatus');
            const mediaPreview = document.getElementById('forumMediaPreview');

            if (!form || !nameInput || !topicInput || !messageInput || !attachmentInput || !refreshBtn || !deleteAllBtn || !statusText || !typingText || !searchInput || !list || !empty || !onlineCount || !onlineList || !replyPill || !replyText || !replyClear || !voiceNoteBtn || !videoNoteBtn || !stopMediaBtn || !mediaStatus || !mediaPreview) {
                return;
            }

            const reactionDefs = [
                { key: 'like', label: 'Like' },
                { key: 'amen', label: 'Amen' },
                { key: 'pray', label: 'Pray' },
                { key: 'love', label: 'Love' },
            ];

            const storage = {
                name: 'mubs_forum_name',
                token: 'mubs_forum_token',
            };
            const registeredName = @json(trim((string) session('registered_user_name', '')));

            const state = {
                messages: [],
                search: '',
                replyingTo: null,
                stream: null,
                activePoll: null,
                activePresence: null,
                activeTypingPull: null,
                typingDebounce: null,
                unread: 0,
            };

            const randomToken = function () {
                const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                let out = '';
                for (let i = 0; i < 40; i += 1) {
                    out += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                return out;
            };

            const getToken = function () {
                let token = window.localStorage.getItem(storage.token);
                if (!token) {
                    token = randomToken();
                    window.localStorage.setItem(storage.token, token);
                }
                return token;
            };

            const chatToken = getToken();

            const setName = function (value) {
                window.localStorage.setItem(storage.name, value);
            };

            const storedName = (window.localStorage.getItem(storage.name) || '').trim();
            if (storedName) {
                nameInput.value = storedName;
            }

            if (registeredName) {
                nameInput.value = registeredName;
                setName(registeredName);
            }

            const baseHeaders = function (includeJson) {
                const headers = {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'X-Chat-Token': chatToken,
                };

                if (includeJson !== false) {
                    headers['Content-Type'] = 'application/json';
                }

                return headers;
            };

            const isLikelyUrl = function (value) {
                return /^https?:\/\//i.test(String(value || '').trim());
            };

            const appendMessageWithLinks = function (target, text) {
                const source = String(text || '');
                const regex = /(https?:\/\/[^\s<]+)/gi;
                let lastIndex = 0;
                let match;

                while ((match = regex.exec(source)) !== null) {
                    const url = match[0];
                    const start = match.index;

                    if (start > lastIndex) {
                        target.appendChild(document.createTextNode(source.slice(lastIndex, start)));
                    }

                    const link = document.createElement('a');
                    link.href = url;
                    link.target = '_blank';
                    link.rel = 'noopener';
                    link.textContent = url;
                    target.appendChild(link);

                    lastIndex = start + url.length;
                }

                if (lastIndex < source.length) {
                    target.appendChild(document.createTextNode(source.slice(lastIndex)));
                }
            };

            const withJson = function (response) {
                if (!response.ok) {
                    return response.text().then(function (text) {
                        throw new Error(text || ('Request failed (' + response.status + ')'));
                    });
                }
                return response.json();
            };

            const formatTime = function (value) {
                const date = new Date(value);
                if (Number.isNaN(date.getTime())) {
                    return '';
                }
                return date.toLocaleString();
            };

            const fileExtension = function (fileName) {
                const value = String(fileName || '').trim();
                const parts = value.split('.');
                if (parts.length < 2) {
                    return 'file';
                }
                return String(parts.pop() || 'file').toLowerCase();
            };

            const attachmentKind = function (msg) {
                const mime = String(msg.attachment_mime || '').toLowerCase();
                const ext = fileExtension(msg.attachment_name);

                if (mime.indexOf('image/') === 0 || ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'].indexOf(ext) >= 0) {
                    return 'image';
                }
                if (mime.indexOf('video/') === 0 || ['mp4', 'webm', 'mov', 'm4v', 'avi', 'mkv'].indexOf(ext) >= 0) {
                    return 'video';
                }
                if (mime.indexOf('audio/') === 0 || ['mp3', 'wav', 'ogg', 'm4a', 'aac', 'flac'].indexOf(ext) >= 0) {
                    return 'audio';
                }
                if (mime.indexOf('pdf') >= 0 || ext === 'pdf') {
                    return 'pdf';
                }
                if (mime.indexOf('text/') === 0 || ['txt', 'csv', 'json', 'xml', 'log', 'md', 'html', 'css', 'js', 'ts', 'php', 'py'].indexOf(ext) >= 0) {
                    return 'text';
                }
                return 'generic';
            };

            const buildAttachmentPreview = function (msg) {
                const preview = document.createElement('div');
                preview.className = 'forum-attachment-preview';

                const kind = attachmentKind(msg);
                const src = msg.attachment_url || '';

                if (kind === 'image') {
                    const img = document.createElement('img');
                    img.src = src;
                    img.alt = msg.attachment_name || 'Image attachment';
                    preview.appendChild(img);
                } else if (kind === 'video') {
                    const video = document.createElement('video');
                    video.src = src;
                    video.controls = true;
                    video.preload = 'metadata';
                    preview.appendChild(video);
                } else if (kind === 'audio') {
                    const audio = document.createElement('audio');
                    audio.src = src;
                    audio.controls = true;
                    audio.preload = 'metadata';
                    preview.appendChild(audio);
                } else if (kind === 'pdf' || kind === 'text') {
                    const frame = document.createElement('iframe');
                    frame.src = src;
                    frame.title = msg.attachment_name || 'Attachment preview';
                    frame.loading = 'lazy';
                    preview.appendChild(frame);
                } else {
                    const generic = document.createElement('div');
                    generic.className = 'forum-attachment-generic';
                    generic.textContent = fileExtension(msg.attachment_name).toUpperCase() + ' FILE';
                    preview.appendChild(generic);
                }

                const corner = document.createElement('span');
                corner.className = 'forum-attachment-corner';
                corner.setAttribute('aria-hidden', 'true');
                preview.appendChild(corner);

                return preview;
            };

            const setReply = function (msg) {
                state.replyingTo = msg;
                replyText.textContent = 'Replying to ' + msg.name + ': ' + (msg.message || '').slice(0, 70);
                replyPill.classList.add('show');
            };

            const clearReply = function () {
                state.replyingTo = null;
                replyPill.classList.remove('show');
            };

            const renderOnline = function (payload) {
                const users = Array.isArray(payload.online) ? payload.online : [];
                onlineCount.textContent = 'Online: ' + (payload.count || users.length || 0);
                onlineList.innerHTML = '';

                if (!users.length) {
                    const li = document.createElement('li');
                    li.textContent = 'No active users yet.';
                    onlineList.appendChild(li);
                    return;
                }

                users.forEach(function (user) {
                    const li = document.createElement('li');
                    const dot = document.createElement('span');
                    dot.className = 'forum-online-dot';
                    li.appendChild(dot);
                    li.appendChild(document.createTextNode(user.name || 'Guest'));
                    onlineList.appendChild(li);
                });
            };

            const renderTyping = function (payload) {
                const typing = Array.isArray(payload.typing) ? payload.typing : [];
                if (!typing.length) {
                    typingText.textContent = '';
                    return;
                }

                const names = typing
                    .map(function (item) { return (item.name || 'Guest').trim(); })
                    .filter(function (name) { return name !== ''; });
                const uniqueNames = Array.from(new Set(names));
                typingText.textContent = uniqueNames.join(', ') + (uniqueNames.length > 1 ? ' are typing...' : ' is typing...');
            };

            const applyIncomingMessages = function (incoming) {
                if (state.messages.length && incoming.length > state.messages.length) {
                    bumpUnread();
                }
                state.messages = incoming;
                renderMessages();
            };

            const matchesSearch = function (msg) {
                if (!state.search) {
                    return true;
                }

                const hay = [msg.name, msg.topic, msg.message].join(' ').toLowerCase();
                return hay.indexOf(state.search) !== -1;
            };

            const bumpUnread = function () {
                if (document.hidden) {
                    state.unread += 1;
                    document.title = '(' + state.unread + ') Forum | SDA Church MUBS';
                }
            };

            const renderMessages = function () {
                list.innerHTML = '';
                const visible = state.messages.filter(matchesSearch);

                if (!visible.length) {
                    list.appendChild(empty);
                    return;
                }

                visible.forEach(function (msg) {
                    const li = document.createElement('li');
                    li.className = 'forum-message' + (msg.is_mine ? ' mine' : '');

                    const avatar = document.createElement('div');
                    avatar.className = 'forum-message-avatar';
                    const initials = (msg.name || 'Guest').split(/\s+/).slice(0, 2).map(function (part) { return part.charAt(0).toUpperCase(); }).join('');
                    avatar.textContent = initials || 'U';
                    li.appendChild(avatar);

                    const bubble = document.createElement('div');
                    bubble.className = 'forum-message-bubble';

                    const meta = document.createElement('p');
                    meta.className = 'forum-message-meta';
                    const metaName = document.createElement('span');
                    metaName.textContent = (msg.name || 'Guest');
                    meta.appendChild(metaName);
                    const metaTime = document.createElement('span');
                    metaTime.className = 'forum-message-timestamp';
                    metaTime.textContent = formatTime(msg.created_at).split(',')[1] || '';
                    meta.appendChild(metaTime);
                    bubble.appendChild(meta);

                    if (msg.parent && msg.parent.message) {
                        const reply = document.createElement('div');
                        reply.className = 'forum-message-reply';
                        reply.textContent = 'Reply to ' + (msg.parent.name || 'User') + ': ' + String(msg.parent.message).slice(0, 80);
                        bubble.appendChild(reply);
                    }

                    const body = document.createElement('p');
                    body.className = 'forum-message-body';
                    appendMessageWithLinks(body, msg.message || '');
                    bubble.appendChild(body);

                    if (msg.attachment_url) {
                        const attachment = document.createElement('div');
                        attachment.className = 'forum-attachment';

                        attachment.appendChild(buildAttachmentPreview(msg));

                        const metaRow = document.createElement('div');
                        metaRow.className = 'forum-attachment-meta';

                        const badge = document.createElement('span');
                        badge.className = 'forum-attachment-badge';
                        badge.textContent = fileExtension(msg.attachment_name).toUpperCase();
                        metaRow.appendChild(badge);

                        const fileName = document.createElement('p');
                        fileName.className = 'forum-attachment-name';
                        fileName.textContent = msg.attachment_name || 'Attached file';
                        metaRow.appendChild(fileName);

                        attachment.appendChild(metaRow);

                        const attachmentActions = document.createElement('div');
                        attachmentActions.className = 'forum-attachment-actions';

                        const openLink = document.createElement('a');
                        openLink.className = 'forum-attachment-link';
                        openLink.href = msg.attachment_url;
                        openLink.target = '_blank';
                        openLink.rel = 'noopener';
                        openLink.textContent = 'Open File';
                        attachmentActions.appendChild(openLink);

                        const downloadLink = document.createElement('a');
                        downloadLink.className = 'forum-attachment-link';
                        downloadLink.href = msg.attachment_download_url || msg.attachment_url;
                        downloadLink.target = '_blank';
                        downloadLink.rel = 'noopener';
                        downloadLink.textContent = 'Download';
                        attachmentActions.appendChild(downloadLink);

                        attachment.appendChild(attachmentActions);

                        bubble.appendChild(attachment);
                    }

                    const reactions = document.createElement('div');
                    reactions.className = 'forum-reactions';
                    reactionDefs.forEach(function (def) {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'forum-react-btn' + ((msg.my_reactions || []).indexOf(def.key) >= 0 ? ' active' : '');
                        const count = Number((msg.reactions || {})[def.key] || 0);
                        btn.textContent = def.label + (count ? ' ' + count : '');
                        btn.addEventListener('click', function () {
                            fetch(routes.reactBase + '/' + msg.id + '/reactions', {
                                method: 'POST',
                                headers: baseHeaders(),
                                body: JSON.stringify({ reaction: def.key }),
                            }).then(withJson).then(fetchMessages);
                        });
                        reactions.appendChild(btn);
                    });
                    if (reactions.children.length) {
                        bubble.appendChild(reactions);
                    }

                    li.appendChild(bubble);

                    const actions = document.createElement('div');
                    actions.className = 'forum-message-actions';

                    const left = document.createElement('div');
                    left.className = 'forum-inline-actions';

                    const replyBtn = document.createElement('button');
                    replyBtn.type = 'button';
                    replyBtn.className = 'forum-inline-btn';
                    replyBtn.textContent = 'Reply';
                    replyBtn.addEventListener('click', function () { setReply(msg); });
                    left.appendChild(replyBtn);

                    if (msg.is_mine && !msg.is_deleted) {
                        const editBtn = document.createElement('button');
                        editBtn.type = 'button';
                        editBtn.className = 'forum-inline-btn';
                        editBtn.textContent = 'Edit';
                        editBtn.addEventListener('click', function () {
                            if (li.classList.contains('is-editing')) {
                                return;
                            }

                            li.classList.add('is-editing');

                            const editBox = document.createElement('div');
                            editBox.className = 'forum-edit-box';

                            const editInput = document.createElement('textarea');
                            editInput.className = 'forum-edit-input';
                            editInput.value = msg.message || '';
                            editBox.appendChild(editInput);

                            const editActions = document.createElement('div');
                            editActions.className = 'forum-edit-actions';

                            const saveBtn = document.createElement('button');
                            saveBtn.type = 'button';
                            saveBtn.className = 'forum-inline-btn';
                            saveBtn.textContent = 'Save';

                            const cancelBtn = document.createElement('button');
                            cancelBtn.type = 'button';
                            cancelBtn.className = 'forum-inline-btn';
                            cancelBtn.textContent = 'Cancel';

                            editActions.appendChild(saveBtn);
                            editActions.appendChild(cancelBtn);
                            editBox.appendChild(editActions);

                            body.hidden = true;
                            bubble.insertBefore(editBox, body.nextSibling);
                            editInput.focus();

                            const endEditMode = function () {
                                li.classList.remove('is-editing');
                                body.hidden = false;
                                editBox.remove();
                            };

                            cancelBtn.addEventListener('click', endEditMode);
                            saveBtn.addEventListener('click', function () {
                                const value = editInput.value.trim();
                                if (!value) {
                                    return;
                                }

                                fetch(routes.updateBase + '/' + msg.id, {
                                    method: 'PUT',
                                    headers: baseHeaders(),
                                    body: JSON.stringify({
                                        topic: msg.topic || '',
                                        message: value,
                                    }),
                                }).then(withJson).then(fetchMessages);
                            });
                        });
                        left.appendChild(editBtn);

                        const deleteBtn = document.createElement('button');
                        deleteBtn.type = 'button';
                        deleteBtn.className = 'forum-inline-btn';
                        deleteBtn.textContent = 'Delete';
                        deleteBtn.addEventListener('click', async function () {
                            const shouldDelete = await CustomModal.show({
                                title: 'Delete Message',
                                message: 'Delete this message? This action cannot be undone.',
                                confirmText: 'Delete',
                                cancelText: 'Cancel',
                                isDangerous: true
                            });
                            
                            if (!shouldDelete) {
                                return;
                            }

                            fetch(routes.updateBase + '/' + msg.id + '/delete', {
                                method: 'POST',
                                headers: baseHeaders(),
                                body: JSON.stringify({}),
                            }).then(withJson).then(fetchMessages).catch(function () {
                                statusText.textContent = 'Could not delete this message right now.';
                            });
                        });
                        left.appendChild(deleteBtn);
                    }

                    actions.appendChild(left);
                    li.appendChild(actions);

                    list.appendChild(li);
                });

                list.scrollTop = list.scrollHeight;
            };

            const fetchMessages = function () {
                return fetch(routes.list, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Chat-Token': chatToken,
                    },
                    cache: 'no-store',
                }).then(withJson).then(function (payload) {
                    const incoming = Array.isArray(payload.messages) ? payload.messages : [];
                    applyIncomingMessages(incoming);
                    statusText.textContent = 'Connected. Last update: ' + new Date().toLocaleTimeString();
                }).catch(function () {
                    statusText.textContent = 'Sync error. Retrying...';
                });
            };

            const clearFallbackIntervals = function () {
                if (state.activePoll) {
                    clearInterval(state.activePoll);
                    state.activePoll = null;
                }
                if (state.activeTypingPull) {
                    clearInterval(state.activeTypingPull);
                    state.activeTypingPull = null;
                }
            };

            const enableFallbackPolling = function () {
                if (!state.activePoll) {
                    state.activePoll = window.setInterval(fetchMessages, 5000);
                }
                if (!state.activeTypingPull) {
                    state.activeTypingPull = window.setInterval(pullTyping, 3000);
                }
            };

            const connectStream = function () {
                if (typeof window.EventSource !== 'function') {
                    enableFallbackPolling();
                    return;
                }

                if (state.stream) {
                    state.stream.close();
                    state.stream = null;
                }

                const streamUrl = routes.stream + '?token=' + encodeURIComponent(chatToken);
                const es = new EventSource(streamUrl);
                state.stream = es;

                es.addEventListener('sync', function (event) {
                    try {
                        const payload = JSON.parse(event.data || '{}');
                        const incoming = Array.isArray(payload.messages) ? payload.messages : [];
                        applyIncomingMessages(incoming);

                        const presence = payload.presence || {};
                        renderOnline({
                            online: Array.isArray(presence.online) ? presence.online : [],
                            count: Number(presence.count || 0),
                        });

                        renderTyping({
                            typing: Array.isArray(payload.typing) ? payload.typing : [],
                        });

                        statusText.textContent = 'Live stream connected. Last update: ' + new Date().toLocaleTimeString();
                        clearFallbackIntervals();
                    } catch (error) {
                        statusText.textContent = 'Live stream payload error. Falling back...';
                        enableFallbackPolling();
                    }
                });

                es.addEventListener('error', function () {
                    statusText.textContent = 'Live stream reconnecting...';
                    enableFallbackPolling();
                });
            };

            const pingPresence = function () {
                const name = nameInput.value.trim() || 'Guest';
                return fetch(routes.presencePing, {
                    method: 'POST',
                    headers: baseHeaders(),
                    body: JSON.stringify({ name: name }),
                }).then(function () {
                    return fetch(routes.presenceList, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Chat-Token': chatToken,
                        },
                        cache: 'no-store',
                    });
                }).then(withJson).then(renderOnline).catch(function () {
                    onlineCount.textContent = 'Online: --';
                });
            };

            const pingTyping = function () {
                const name = nameInput.value.trim() || 'Guest';
                fetch(routes.typingPing, {
                    method: 'POST',
                    headers: baseHeaders(),
                    body: JSON.stringify({ name: name }),
                }).catch(function () {});
            };

            const pullTyping = function () {
                fetch(routes.typingList, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Chat-Token': chatToken,
                    },
                    cache: 'no-store',
                }).then(withJson).then(renderTyping).catch(function () {
                    typingText.textContent = '';
                });
            };

            const mediaState = {
                stream: null,
                recorder: null,
                chunks: [],
                mode: '',
            };

            const updateMediaButtons = function (activeMode) {
                voiceNoteBtn.classList.toggle('active', activeMode === 'audio');
                videoNoteBtn.classList.toggle('active', activeMode === 'video');
                stopMediaBtn.disabled = !activeMode;
            };

            const cleanupMediaPreview = function () {
                if (mediaPreview.srcObject) {
                    mediaPreview.srcObject = null;
                }
                mediaPreview.hidden = true;
            };

            const stopMediaTracks = function () {
                if (mediaState.stream) {
                    mediaState.stream.getTracks().forEach(function (track) {
                        track.stop();
                    });
                    mediaState.stream = null;
                }
                cleanupMediaPreview();
            };

            const pickRecorderMimeType = function (mode) {
                if (typeof window.MediaRecorder !== 'function' || typeof window.MediaRecorder.isTypeSupported !== 'function') {
                    return '';
                }

                const audioCandidates = ['audio/webm;codecs=opus', 'audio/webm', 'audio/ogg;codecs=opus'];
                const videoCandidates = ['video/webm;codecs=vp9,opus', 'video/webm;codecs=vp8,opus', 'video/webm'];
                const candidates = mode === 'video' ? videoCandidates : audioCandidates;

                for (let i = 0; i < candidates.length; i += 1) {
                    if (window.MediaRecorder.isTypeSupported(candidates[i])) {
                        return candidates[i];
                    }
                }

                return '';
            };

            const attachRecordedFile = function (blob, mode) {
                const extension = mode === 'video' ? 'webm' : 'webm';
                const fileType = blob.type || (mode === 'video' ? 'video/webm' : 'audio/webm');
                const fileName = (mode === 'video' ? 'video-note-' : 'voice-note-') + Date.now() + '.' + extension;
                const file = new File([blob], fileName, { type: fileType });

                if (typeof DataTransfer !== 'function') {
                    mediaStatus.textContent = 'Recording finished. Please attach the file manually on this browser.';
                    return;
                }

                const transfer = new DataTransfer();
                transfer.items.add(file);
                attachmentInput.files = transfer.files;
                attachmentInput.dispatchEvent(new Event('change', { bubbles: true }));
                mediaStatus.textContent = (mode === 'video' ? 'Video' : 'Voice') + ' note attached. You can now post it.';
            };

            const stopMediaRecording = function (statusMessage) {
                if (mediaState.recorder && mediaState.recorder.state !== 'inactive') {
                    mediaState.recorder.stop();
                } else {
                    stopMediaTracks();
                }

                if (statusMessage) {
                    mediaStatus.textContent = statusMessage;
                }

                mediaState.mode = '';
                updateMediaButtons('');
            };

            const startMediaRecording = function (mode) {
                if (!navigator.mediaDevices || typeof navigator.mediaDevices.getUserMedia !== 'function' || typeof window.MediaRecorder !== 'function') {
                    mediaStatus.textContent = 'Your browser does not support voice/video recording.';
                    return;
                }

                if (mediaState.recorder && mediaState.recorder.state !== 'inactive') {
                    stopMediaRecording('Previous recording stopped.');
                }

                const constraints = mode === 'video' ? { audio: true, video: true } : { audio: true, video: false };
                const mimeType = pickRecorderMimeType(mode);

                navigator.mediaDevices.getUserMedia(constraints).then(function (stream) {
                    mediaState.stream = stream;
                    mediaState.chunks = [];
                    mediaState.mode = mode;

                    if (mode === 'video') {
                        mediaPreview.srcObject = stream;
                        mediaPreview.hidden = false;
                    } else {
                        cleanupMediaPreview();
                    }

                    const recorderOptions = mimeType ? { mimeType: mimeType } : undefined;
                    const recorder = recorderOptions ? new MediaRecorder(stream, recorderOptions) : new MediaRecorder(stream);
                    mediaState.recorder = recorder;

                    recorder.addEventListener('dataavailable', function (event) {
                        if (event.data && event.data.size > 0) {
                            mediaState.chunks.push(event.data);
                        }
                    });

                    recorder.addEventListener('stop', function () {
                        const chunks = mediaState.chunks.slice();
                        const activeMode = mediaState.mode || mode;

                        mediaState.recorder = null;
                        mediaState.chunks = [];
                        stopMediaTracks();

                        if (chunks.length) {
                            const blobType = mimeType || (activeMode === 'video' ? 'video/webm' : 'audio/webm');
                            const blob = new Blob(chunks, { type: blobType });
                            attachRecordedFile(blob, activeMode);
                        }
                    });

                    recorder.start();
                    updateMediaButtons(mode);
                    mediaStatus.textContent = mode === 'video' ? 'Recording video note... click stop when done.' : 'Recording voice note... click stop when done.';
                }).catch(function () {
                    mediaState.mode = '';
                    updateMediaButtons('');
                    stopMediaTracks();
                    mediaStatus.textContent = 'Could not access microphone/camera. Check permissions and try again.';
                });
            };

            form.addEventListener('submit', function (event) {
                event.preventDefault();

                const name = nameInput.value.trim();
                const topic = topicInput.value.trim();
                const message = messageInput.value.trim();
                const attachment = attachmentInput.files && attachmentInput.files[0] ? attachmentInput.files[0] : null;

                if (!name) {
                    statusText.textContent = 'Enter your name before posting a message.';
                    nameInput.focus();
                    return;
                }

                if (!message && !attachment) {
                    statusText.textContent = 'Type a message or attach a file before posting.';
                    return;
                }

                setName(name);
                statusText.textContent = 'Posting...';

                const payload = new FormData();
                payload.append('name', name);
                payload.append('topic', topic);
                payload.append('message', message);
                if (state.replyingTo && state.replyingTo.id) {
                    payload.append('parent_id', String(state.replyingTo.id));
                }

                if (attachment) {
                    payload.append('attachment', attachment);
                }

                fetch(routes.post, {
                    method: 'POST',
                    headers: baseHeaders(false),
                    body: payload,
                }).then(withJson).then(function () {
                    messageInput.value = '';
                    attachmentInput.value = '';
                    clearReply();
                    return fetchMessages();
                }).catch(function (error) {
                    statusText.textContent = 'Could not post now. Please wait and try again.';
                });
            });

            deleteAllBtn.addEventListener('click', async function () {
                const shouldDelete = await CustomModal.show({
                    title: 'Delete All Messages',
                    message: 'Delete all your chat messages? This action cannot be undone.',
                    confirmText: 'Delete All',
                    cancelText: 'Cancel',
                    isDangerous: true
                });
                
                if (!shouldDelete) {
                    return;
                }

                fetch(routes.deleteAll, {
                    method: 'DELETE',
                    headers: baseHeaders(),
                }).then(withJson).then(function () {
                    return fetchMessages();
                }).catch(function () {
                    statusText.textContent = 'Could not delete all messages right now.';
                });
            });

            replyClear.addEventListener('click', clearReply);

            searchInput.addEventListener('input', function () {
                state.search = searchInput.value.trim().toLowerCase();
                renderMessages();
            });

            messageInput.addEventListener('input', function () {
                if (state.typingDebounce) {
                    clearTimeout(state.typingDebounce);
                }
                state.typingDebounce = setTimeout(pingTyping, 250);
            });

            topicInput.addEventListener('input', function () {
                if (state.typingDebounce) {
                    clearTimeout(state.typingDebounce);
                }
                state.typingDebounce = setTimeout(pingTyping, 250);
            });

            nameInput.addEventListener('change', function () {
                pingPresence();
                pingTyping();
            });

            const syncAttachmentValidation = function () {
                const hasAttachment = !!(attachmentInput.files && attachmentInput.files[0]);
                messageInput.required = !hasAttachment;
            };

            attachmentInput.addEventListener('change', syncAttachmentValidation);

            refreshBtn.addEventListener('click', function () {
                fetchMessages();
                pingPresence();
                pullTyping();
                connectStream();
            });

            document.addEventListener('visibilitychange', function () {
                if (!document.hidden) {
                    state.unread = 0;
                    document.title = 'Forum | SDA Church MUBS';
                }
            });

            voiceNoteBtn.addEventListener('click', function () {
                startMediaRecording('audio');
            });

            videoNoteBtn.addEventListener('click', function () {
                startMediaRecording('video');
            });

            stopMediaBtn.addEventListener('click', function () {
                stopMediaRecording('Recording stopped.');
            });

            fetchMessages();
            pingPresence();
            pullTyping();
            connectStream();
            syncAttachmentValidation();

            state.activePresence = window.setInterval(pingPresence, 12000);
            enableFallbackPolling();

            window.addEventListener('beforeunload', function () {
                if (state.stream) { state.stream.close(); }
                if (state.activePoll) { clearInterval(state.activePoll); }
                if (state.activePresence) { clearInterval(state.activePresence); }
                if (state.activeTypingPull) { clearInterval(state.activeTypingPull); }
                stopMediaRecording('');
            });
        })();
    </script>
@endsection

