@extends('layouts.site')

@section('title', 'SDA MUBS | Our Beliefs')

@section('content')
    @php
        $beliefs = [
            ['number' => 1, 'title' => 'The Holy Scriptures', 'subtitle' => 'God speaks through the Bible as the authoritative revelation for faith and practice.', 'link' => 'https://adventist.org/beliefs/official/holy-scriptures'],
            ['number' => 2, 'title' => 'The Trinity', 'subtitle' => 'There is one God: Father, Son, and Holy Spirit, united in divine purpose.', 'link' => 'https://adventist.org/beliefs/official/trinity'],
            ['number' => 3, 'title' => 'God the Father', 'subtitle' => 'The Father is the Creator, Sustainer, and sovereign of all creation.', 'link' => 'https://adventist.org/beliefs/official/father'],
            ['number' => 4, 'title' => 'God the Son', 'subtitle' => 'Jesus Christ reveals God, saves humanity, and ministers in heaven on our behalf.', 'link' => 'https://adventist.org/beliefs/official/son'],
            ['number' => 5, 'title' => 'God the Holy Spirit', 'subtitle' => 'The Spirit inspires Scripture, convicts hearts, and empowers the church.', 'link' => 'https://adventist.org/beliefs/official/holy-spirit'],
            ['number' => 6, 'title' => 'Creation', 'subtitle' => 'God created the world in six days and rested on the seventh day.', 'link' => 'https://adventist.org/beliefs/official/creation'],
            ['number' => 7, 'title' => 'Nature of Humanity', 'subtitle' => 'Humanity was made in God\'s image and needs restoration after the fall.', 'link' => 'https://adventist.org/beliefs/official/nature-of-humanity'],
            ['number' => 8, 'title' => 'The Great Controversy', 'subtitle' => 'A conflict between Christ and Satan explains the brokenness of the world.', 'link' => 'https://adventist.org/beliefs/official/the-great-controversy'],
            ['number' => 9, 'title' => 'The Life, Death and Resurrection of Christ', 'subtitle' => 'Christ\'s atoning death and resurrection are the basis of salvation and hope.', 'link' => 'https://adventist.org/beliefs/official/life-death-and-resurrection-of-christ'],
            ['number' => 10, 'title' => 'The Experience of Salvation', 'subtitle' => 'Through grace, faith, repentance, and the Spirit, believers are made new.', 'link' => 'https://adventist.org/beliefs/official/experience-of-salvation'],
            ['number' => 11, 'title' => 'Growing in Christ', 'subtitle' => 'The Spirit transforms believers into mature disciples who reflect Jesus.', 'link' => 'https://adventist.org/beliefs/official/growing-in-christ'],
            ['number' => 12, 'title' => 'The Church', 'subtitle' => 'The church is the body of Christ called to worship, fellowship, and mission.', 'link' => 'https://adventist.org/beliefs/official/church'],
            ['number' => 13, 'title' => 'The Remnant and its Mission', 'subtitle' => 'God calls a remnant people to proclaim the final gospel message.', 'link' => 'https://adventist.org/beliefs/official/remnant-and-its-mission'],
            ['number' => 14, 'title' => 'Unity in the Body of Christ', 'subtitle' => 'Believers from every nation are one in Christ and one in mission.', 'link' => 'https://adventist.org/beliefs/official/unity-in-the-body-of-christ'],
            ['number' => 15, 'title' => 'Baptism', 'subtitle' => 'Baptism by immersion marks faith in Christ and new life in Him.', 'link' => 'https://adventist.org/beliefs/official/baptism'],
            ['number' => 16, 'title' => "The Lord's Supper", 'subtitle' => 'Communion remembers Christ\'s sacrifice and invites humble fellowship.', 'link' => 'https://adventist.org/beliefs/official/the-lords-supper'],
            ['number' => 17, 'title' => 'Spiritual Gifts and Ministries', 'subtitle' => 'The Spirit gives gifts to equip the church for service and witness.', 'link' => 'https://adventist.org/beliefs/official/spiritual-gifts-and-ministries'],
            ['number' => 18, 'title' => 'The Gift of Prophecy', 'subtitle' => 'Prophecy remains a gift of the Spirit and points back to Scripture.', 'link' => 'https://adventist.org/beliefs/official/gift-of-prophecy'],
            ['number' => 19, 'title' => 'The Law of God', 'subtitle' => 'God\'s law expresses His character and guides holy living.', 'link' => 'https://adventist.org/beliefs/official/the-law-of-god'],
            ['number' => 20, 'title' => 'The Sabbath', 'subtitle' => 'The seventh-day Sabbath is God\'s sign of creation and redemption.', 'link' => 'https://adventist.org/beliefs/official/the-sabbath'],
            ['number' => 21, 'title' => 'Stewardship', 'subtitle' => 'All we have belongs to God and is entrusted to our care.', 'link' => 'https://adventist.org/beliefs/official/stewardship'],
            ['number' => 22, 'title' => 'Christian Behavior', 'subtitle' => 'Believers live in harmony with Scripture in daily conduct and health.', 'link' => 'https://adventist.org/beliefs/official/christian-behavior'],
            ['number' => 23, 'title' => 'Marriage and the Family', 'subtitle' => 'Marriage and family are God\'s gifts for love, nurture, and faithfulness.', 'link' => 'https://adventist.org/beliefs/official/marriage-and-the-family'],
            ['number' => 24, 'title' => "Christ's Ministry in the Heavenly Sanctuary", 'subtitle' => 'Christ ministers for us in the heavenly sanctuary until His return.', 'link' => 'https://adventist.org/beliefs/official/christs-ministry-in-the-heavenly-sanctuary'],
            ['number' => 25, 'title' => 'The Second Coming of Christ', 'subtitle' => 'Jesus will return visibly, personally, and gloriously to end history.', 'link' => 'https://adventist.org/beliefs/official/second-coming'],
            ['number' => 26, 'title' => 'Death and Resurrection', 'subtitle' => 'Death is an unconscious sleep until the resurrection at Christ\'s coming.', 'link' => 'https://adventist.org/beliefs/official/death-and-resurrection'],
            ['number' => 27, 'title' => 'The Millennium and the End of Sin', 'subtitle' => 'The millennium ends with the final destruction of sin and evil.', 'link' => 'https://adventist.org/beliefs/official/millennium-and-the-end-of-sin'],
            ['number' => 28, 'title' => 'The New Earth', 'subtitle' => 'God will make all things new and dwell forever with His people.', 'link' => 'https://adventist.org/beliefs/official/the-new-earth'],
        ];
    @endphp

    <style>
        .beliefs-page { display: grid; gap: 1rem; }
        .beliefs-shell { display: grid; gap: 0; }
        .beliefs-hero {
            position: relative;
            overflow: hidden;
            border-radius: 0;
            padding: 3rem 2rem;
            min-height: 380px;
            display: grid;
            align-content: center;
            justify-items: center;
            text-align: center;
            background:
                linear-gradient(180deg, rgba(15, 43, 85, 0.75) 0%, rgba(62, 131, 145, 0.7) 100%),
                url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 800"><defs><pattern id="book" x="0" y="0" width="100" height="100" patternUnits="userSpaceOnUse"><text x="50" y="50" font-size="40" fill="rgba(255,255,255,0.05)" text-anchor="middle" dy=".3em">📖</text></pattern></defs><rect fill="url(%23book)" width="1200" height="800"/></svg>'),
                linear-gradient(180deg, rgba(0,0,0,0.3), rgba(0,0,0,0.15));
            background-size: cover, cover, cover;
            background-position: center, center, center;
            background-attachment: fixed;
        }
        .beliefs-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.03) 0, rgba(255, 255, 255, 0) 42%);
            pointer-events: none;
        }
        .beliefs-hero::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 60px;
            background: url('data:image/svg+xml;utf8,<svg viewBox="0 0 1200 120" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none"><path d="M0,40 Q300,10 600,40 T1200,40 L1200,120 L0,120 Z" fill="white"/></svg>');
            background-size: cover;
            background-position: bottom;
        }
        .beliefs-hero-inner { position: relative; z-index: 1; display: grid; gap: 0.7rem; max-width: 900px; }
        .beliefs-kicker { margin: 0; color: #dbe9ff; text-transform: uppercase; letter-spacing: 0.14em; font-size: 0.78rem; font-weight: 800; }
        .beliefs-hero h1 { margin: 0; color: #fff; line-height: 1.1; font-size: clamp(2.4rem, 6vw, 3.8rem); font-weight: 800; }
        .beliefs-hero > .beliefs-hero-inner > p:first-of-type { margin: 0; color: #eef5ff; max-width: 940px; line-height: 1.62; font-size: 1.05rem; }
        .beliefs-pill-row { display: flex; gap: 0.55rem; flex-wrap: wrap; margin-top: 0.3rem; justify-content: center; }
        .beliefs-pill {
            display: inline-flex; align-items: center; border: 1px solid rgba(255, 255, 255, 0.24);
            background: rgba(255, 255, 255, 0.1); color: #fff; border-radius: 999px; padding: 0.34rem 0.72rem;
            font-size: 0.82rem; font-weight: 700;
        }
        .beliefs-section { background: #fff; border: 1px solid #d9e0ec; border-radius: 12px; padding: 1rem; }
        .beliefs-intro { 
            background: #fff;
            border: none;
            border-radius: 0;
            padding: 2.5rem 2rem;
            display: grid; 
            grid-template-columns: minmax(0, 1.2fr) minmax(0, 0.8fr); 
            gap: 2rem; 
            align-items: start;
            margin-top: -1px;
        }
        .beliefs-intro h2 { margin: 0; color: #0f2b55; line-height: 1.24; font-size: clamp(1.35rem, 2.9vw, 2.1rem); }
        .beliefs-intro p { margin: 0; color: #33435b; line-height: 1.68; text-align: left; }
        .beliefs-note { border-left: 4px solid #c19434; background: #fbf7ee; color: #4b3a16; border-radius: 10px; padding: 0.9rem 1rem; line-height: 1.62; }
        .beliefs-note strong { color: #2f2411; }
        .beliefs-summary { background: #0f2b55; border-radius: 12px; padding: 1rem; color: #fff; }
        .beliefs-summary-head { display: flex; justify-content: space-between; align-items: end; gap: 0.75rem; flex-wrap: wrap; margin-bottom: 0.85rem; }
        .beliefs-summary h2 { margin: 0; color: #fff; text-transform: uppercase; font-size: clamp(1.25rem, 2.4vw, 1.85rem); letter-spacing: 0.03em; }
        .beliefs-summary p { margin: 0.18rem 0 0; color: #d9e7ff; line-height: 1.56; }
        .beliefs-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); 
            gap: 1.2rem;
            padding: 0;
            min-height: 600px;
        }

        .skeleton-card {
            background: rgba(15, 43, 85, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 14px;
            padding: 1.4rem;
            display: grid;
            gap: 0.8rem;
            position: relative;
            overflow: hidden;
            min-height: 180px;
            justify-content: space-between;
            animation: skeletonPulse 1.5s ease-in-out infinite;
        }

        @keyframes skeletonPulse {
            0%, 100% {
                background: rgba(15, 43, 85, 0.3);
                border-color: rgba(255, 255, 255, 0.08);
            }
            50% {
                background: rgba(15, 43, 85, 0.5);
                border-color: rgba(255, 255, 255, 0.15);
            }
        }

        .skeleton-line {
            height: 12px;
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.2) 50%, rgba(255, 255, 255, 0.1) 100%);
            border-radius: 6px;
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }

        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        .skeleton-number {
            height: 14px;
            width: 30px;
            border-radius: 4px;
        }

        .skeleton-title {
            height: 18px;
            width: 70%;
            border-radius: 6px;
            margin-top: 0.4rem;
        }

        .skeleton-text {
            height: 14px;
            border-radius: 6px;
            margin-top: 0.2rem;
        }

        .skeleton-text:nth-child(2) {
            width: 85%;
        }

        .skeleton-text:nth-child(3) {
            width: 65%;
        }

        .skeleton-button {
            height: 32px;
            width: 90px;
            border-radius: 8px;
            margin-top: 0.4rem;
        }

        .beliefs-card { 
            background: rgba(15, 43, 85, 0.4); 
            border: 1px solid rgba(255, 255, 255, 0.12); 
            border-radius: 14px; 
            padding: 1.4rem; 
            display: grid; 
            gap: 0.8rem;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            cursor: pointer;
            position: relative;
            overflow: hidden;
            min-height: 180px;
            justify-content: space-between;
            animation: fadeInCard 0.5s ease;
        }

        @keyframes fadeInCard {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .beliefs-card.hidden {
            display: none;
        }

        .beliefs-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.04) 0%, transparent 60%);
            pointer-events: none;
        }

        .beliefs-card:hover {
            background: rgba(15, 43, 85, 0.55);
            border-color: rgba(255, 255, 255, 0.24);
            transform: translateY(-6px);
            box-shadow: 0 16px 40px rgba(15, 43, 85, 0.4), inset 0 1px 1px rgba(255, 255, 255, 0.08);
        }

        .beliefs-card-inner {
            position: relative;
            z-index: 1;
            display: grid;
            gap: 0.8rem;
        }

        .beliefs-pagination {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .pagination-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .pagination-btn:hover:not(:disabled) {
            background: rgba(255, 255, 255, 0.16);
            border-color: rgba(255, 255, 255, 0.32);
            transform: scale(1.05);
        }

        .pagination-btn.active {
            background: linear-gradient(135deg, #3e8391 0%, #2a5f6a 100%);
            border-color: rgba(62, 131, 145, 0.8);
            box-shadow: 0 4px 12px rgba(62, 131, 145, 0.3);
        }

        .pagination-btn:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        .pagination-info {
            color: #d9e7ff;
            font-size: 0.9rem;
            font-weight: 600;
            margin: 0 0.5rem;
        }

        .beliefs-card h3 { 
            margin: 0; 
            color: #ffffff; 
            font-size: 1.06rem; 
            line-height: 1.32;
            font-weight: 800;
            letter-spacing: 0.01em;
        }

        .beliefs-card .beliefs-number { 
            margin: 0; 
            color: #8ce4f0; 
            font-size: 0.76rem; 
            font-weight: 900; 
            text-transform: uppercase; 
            letter-spacing: 0.12em; 
        }

        .beliefs-card p { 
            margin: 0; 
            color: #d9e7ff; 
            line-height: 1.56; 
            font-size: 0.9rem;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .beliefs-card .beliefs-read-more {
            display: inline-flex; 
            align-items: center;
            width: fit-content; 
            text-decoration: none; 
            border: 1.5px solid rgba(255, 255, 255, 0.6);
            color: #ffffff; 
            border-radius: 8px; 
            padding: 0.48rem 0.9rem; 
            font-size: 0.8rem; 
            font-weight: 700; 
            background: rgba(255, 255, 255, 0.08);
            transition: all 0.25s ease;
            margin-top: auto;
        }

        .beliefs-card .beliefs-read-more:hover { 
            background: rgba(255, 255, 255, 0.16);
            border-color: rgba(255, 255, 255, 1);
            transform: scale(1.08);
        }

        .beliefs-card .beliefs-read-more::after {
            content: '→';
            margin-left: 0.4rem;
            transition: transform 0.2s ease;
        }

        .beliefs-card:hover .beliefs-read-more::after {
            transform: translateX(2px);
        }

        .beliefs-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(8, 21, 44, 0.92);
            backdrop-filter: blur(8px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9998;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .beliefs-modal-overlay.active {
            display: flex;
            opacity: 1;
            visibility: visible;
        }

        .beliefs-modal {
            background: linear-gradient(135deg, #0f2b55 0%, #1a4578 100%);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 18px;
            padding: 2.4rem;
            max-width: 620px;
            width: 90%;
            max-height: 85vh;
            overflow-y: auto;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.6);
            transform: scale(0.9);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            animation: slideUp 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes slideUp {
            from {
                transform: scale(0.88) translateY(30px);
                opacity: 0;
            }
            to {
                transform: scale(1) translateY(0);
                opacity: 1;
            }
        }

        .beliefs-modal-overlay.active .beliefs-modal {
            opacity: 1;
            transform: scale(1);
        }

        .beliefs-modal::-webkit-scrollbar {
            width: 8px;
        }

        .beliefs-modal::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.04);
            border-radius: 10px;
        }

        .beliefs-modal::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.18);
            border-radius: 10px;
        }

        .beliefs-modal::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.28);
        }

        .beliefs-modal-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.6rem;
            padding-bottom: 1.2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        }

        .beliefs-modal-number {
            color: #8ce4f0;
            font-size: 2.2rem;
            font-weight: 900;
            line-height: 1;
            min-width: 50px;
        }

        .beliefs-modal-title {
            margin: 0;
            color: #ffffff;
            font-size: 1.8rem;
            line-height: 1.3;
            font-weight: 800;
            flex: 1;
        }

        .beliefs-modal-close {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.18);
            color: #ffffff;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-size: 1.6rem;
            line-height: 1;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .beliefs-modal-close:hover {
            background: rgba(255, 255, 255, 0.22);
            transform: scale(1.1);
        }

        .beliefs-modal-content {
            margin: 0;
            color: #d9e7ff;
            line-height: 1.8;
            font-size: 1rem;
        }

        .beliefs-modal-footer {
            margin-top: 1.8rem;
            padding-top: 1.2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.12);
            display: flex;
            gap: 0.8rem;
            flex-wrap: wrap;
        }

        .beliefs-external-link {
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            border: 1.5px solid rgba(255, 255, 255, 0.4);
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
            border-radius: 10px;
            padding: 0.6rem 1.2rem;
            font-size: 0.9rem;
            font-weight: 700;
            transition: all 0.2s ease;
        }

        .beliefs-external-link:hover {
            background: rgba(62, 131, 145, 0.3);
            border-color: rgba(255, 255, 255, 0.6);
            transform: translateY(-2px);
        }

        .beliefs-external-link::after {
            content: '↗';
            margin-left: 0.5rem;
        }

        .beliefs-cta { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.9rem; }
        .beliefs-cta-item {
            background: #f6f8fc; border: 1px solid #d9e0ec; border-radius: 12px; padding: 1rem; text-align: center;
            display: grid; justify-items: center; gap: 0.65rem;
        }
        .beliefs-cta-item img { width: 140px; height: 140px; object-fit: cover; border-radius: 18px; border: 1px solid #cfd8e7; background: #ffffff; padding: 0.2rem; box-shadow: 0 8px 20px rgba(15, 43, 85, 0.12); }
        .beliefs-cta-item p { margin: 0; color: #33435b; line-height: 1.58; }
        .beliefs-btn { display: inline-block; text-decoration: none; border: 1px solid #0f2b55; background: #0f2b55; color: #fff; border-radius: 8px; padding: 0.48rem 0.82rem; font-weight: 700; font-size: 0.88rem; }
        .beliefs-btn.alt { background: #3e8391; border-color: #3e8391; }

        .beliefs-cta-item:first-child img {
            object-position: center center;
        }

        .beliefs-cta-item:last-child img {
            object-position: center center;
        }

        @media (max-width: 960px) { 
            .beliefs-intro, .beliefs-cta { grid-template-columns: 1fr; }
            .beliefs-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                min-height: 550px;
            }
        }

        @media (max-width: 640px) { 
            .beliefs-hero {
                padding: 1.15rem 0.95rem 1.35rem;
                min-height: 0;
                height: auto;
                align-content: start;
                overflow: visible;
                background-attachment: scroll;
            }
            .beliefs-hero::after {
                display: none;
            }
            .beliefs-hero h1 {
                font-size: clamp(1.95rem, 8vw, 2.45rem);
                line-height: 1.14;
            }
            .beliefs-hero > .beliefs-hero-inner > p:first-of-type {
                font-size: 0.84rem;
                line-height: 1.45;
                max-width: 100%;
            }
            .beliefs-section, .beliefs-summary { padding: 0.85rem; }
            .beliefs-grid {
                grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
                gap: 0.8rem;
                min-height: 500px;
            }
            .beliefs-card {
                padding: 1rem;
                min-height: 0;
            }
            .beliefs-card h3 {
                font-size: 0.95rem;
            }
            .beliefs-card p {
                font-size: 0.85rem;
                display: block;
                -webkit-line-clamp: unset;
                -webkit-box-orient: initial;
                overflow: visible;
                text-overflow: initial;
            }
            .beliefs-pagination {
                gap: 0.4rem;
                margin-top: 1.5rem;
            }
            .pagination-btn {
                width: 38px;
                height: 38px;
                font-size: 0.85rem;
            }
            .pagination-info {
                font-size: 0.8rem;
                margin: 0 0.3rem;
            }
            .beliefs-modal {
                width: 95%;
                padding: 1.8rem;
                border-radius: 16px;
            }
            .beliefs-modal-title {
                font-size: 1.5rem;
            }
            .beliefs-modal-number {
                font-size: 1.8rem;
            }
        }

        @media (max-width: 480px) {
            .beliefs-hero {
                padding: 1rem 0.8rem 1.2rem;
            }
            .beliefs-hero h1 {
                font-size: 1.78rem;
            }
            .beliefs-hero > .beliefs-hero-inner > p:first-of-type {
                font-size: 0.79rem;
                line-height: 1.4;
            }
            .beliefs-grid {
                grid-template-columns: 1fr;
                min-height: 450px;
            }
            .beliefs-card {
                min-height: 0;
            }
            .beliefs-cta-item img {
                width: 118px;
                height: 118px;
            }
            .pagination-btn {
                width: 36px;
                height: 36px;
                font-size: 0.8rem;
            }
            .beliefs-pagination {
                gap: 0.3rem;
            }
        }
    </style>

    <section class="beliefs-page" aria-label="Our beliefs page">
        <div class="beliefs-shell">
            <header class="beliefs-hero">
                <div class="beliefs-hero-inner">
                    <h1>What do Adventists Believe?</h1>
                    <p>Upholding the Protestant conviction of Sola Scriptura ("Bible only"), these 28 Fundamental Beliefs describe how Seventh-day Adventists interpret Scripture for daily application.</p>
                </div>
            </header>

            <section class="beliefs-section beliefs-intro" aria-label="Beliefs introduction">
                <div>
                    <h2>Seventh-day Adventists accept the Bible as their only creed and hold certain fundamental beliefs to be the teaching of the Holy Scriptures.</h2>
                    <p style="margin-top:1rem;">Revision of these statements may be expected at a quinquennial General Conference Session whenever the church is led by the Holy Spirit to a fuller understanding of Bible truth, or if better language is found to express these teachings of God's Holy Word.</p>
                </div>
                <div>
                    <p style="color: #4b7c92; font-size: 0.95rem;"><strong style="color: #2c5366;">The expression of these concepts</strong> help provide an overall picture of what this Christian denomination collectively believes and practices. Together, these teachings reveal a God who is the architect of the world. In wisdom, grace and infinite love, He is actively working to restore a relationship with humanity that will last for eternity.</p>
                </div>
            </section>

            <section class="beliefs-summary" aria-label="Beliefs cards">
                <div class="beliefs-summary-head">
                    <div>
                        <h2>Official Fundamental Beliefs</h2>
                        <p>Read the 28 beliefs in the same order and naming used by the Adventist reference page.</p>
                    </div>
                    <p>Click any card to learn more.</p>
                </div>

                <div class="beliefs-grid" id="beliefsGrid">
                    <!-- Skeleton loaders that will be shown initially -->
                    <div id="skeletonContainer" style="grid-column: 1 / -1; display: contents;">
                        @for($i = 0; $i < 8; $i++)
                            <div class="skeleton-card">
                                <div>
                                    <div class="skeleton-line skeleton-number"></div>
                                    <div class="skeleton-line skeleton-title"></div>
                                    <div class="skeleton-line skeleton-text"></div>
                                    <div class="skeleton-line skeleton-text"></div>
                                </div>
                                <div class="skeleton-line skeleton-button"></div>
                            </div>
                        @endfor
                    </div>

                    <!-- Actual belief cards (hidden initially) -->
                    <div id="cardsContainer" style="grid-column: 1 / -1; display: contents;">
                        @foreach($beliefs as $belief)
                            <article class="beliefs-card" data-belief-id="{{ $belief['number'] }}" data-belief-title="{{ $belief['title'] }}" data-belief-subtitle="{{ $belief['subtitle'] }}" data-belief-link="{{ $belief['link'] }}" data-page="{{ ceil($belief['number'] / 8) }}" style="opacity: 0; display: none;">
                                <div class="beliefs-card-inner">
                                    <p class="beliefs-number">{{ $belief['number'] }}</p>
                                    <h3>{{ $belief['title'] }}</h3>
                                    <p>{{ $belief['subtitle'] }}</p>
                                </div>
                                <a class="beliefs-read-more" href="javascript:void(0)">Learn More</a>
                            </article>
                        @endforeach
                    </div>
                </div>

                <div class="beliefs-pagination" id="beliefsPagination">
                    <button class="pagination-btn" id="prevBtn" aria-label="Previous page">← Prev</button>
                    <div class="pagination-info">
                        <span id="pageInfo">Page 1 of 4</span>
                    </div>
                    <button class="pagination-btn" id="nextBtn" aria-label="Next page">Next →</button>
                </div>

                <div class="beliefs-modal-overlay" id="beliefsModalOverlay">
                    <div class="beliefs-modal" id="beliefsModal">
                        <div class="beliefs-modal-header">
                            <div class="beliefs-modal-number" id="modalNumber"></div>
                            <h2 class="beliefs-modal-title" id="modalTitle"></h2>
                            <button class="beliefs-modal-close" id="modalClose" aria-label="Close modal">×</button>
                        </div>
                        <p class="beliefs-modal-content" id="modalContent"></p>
                        <div class="beliefs-modal-footer">
                            <a class="beliefs-external-link" id="modalLink" target="_blank" rel="noopener">Read Full Article</a>
                        </div>
                    </div>
                </div>
            </section>

            <section class="beliefs-section beliefs-cta" aria-label="Beliefs resources">
                <article class="beliefs-cta-item">
                    <img src="{{ asset('belief.jpg') }}" alt="Beliefs resource image" loading="lazy">
                    <p>Download a concise PDF of the 28 Fundamental Beliefs for offline reading and sharing.</p>
                    <a class="beliefs-btn" href="https://hope-documents.fra1.digitaloceanspaces.com/67054013a60919c92d92c959/lCF1749473762753.pdf" target="_blank" rel="noopener">Download Now</a>
                </article>

                <article class="beliefs-cta-item">
                    <img src="{{ asset('bible.jpg') }}" alt="Bible study resource image" loading="lazy">
                    <p>Explore Bible study resources that support the same Adventist foundation used on this page.</p>
                    <a class="beliefs-btn alt" href="https://adventist.org/beliefs/bible/study" target="_blank" rel="noopener">Learn More</a>
                </article>
            </section>
        </div>
    </section>

    <script>
        (function() {
            const grid = document.getElementById('beliefsGrid');
            const modalOverlay = document.getElementById('beliefsModalOverlay');
            const modalClose = document.getElementById('modalClose');
            const cards = document.querySelectorAll('.beliefs-card');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const pageInfo = document.getElementById('pageInfo');
            const skeletonContainer = document.getElementById('skeletonContainer');
            const cardsContainer = document.getElementById('cardsContainer');

            const CARDS_PER_PAGE = 8;
            const TOTAL_PAGES = Math.ceil(28 / CARDS_PER_PAGE);
            let currentPage = 1;

            // Simulate loading delay and show actual cards
            function loadCards() {
                setTimeout(function() {
                    // Hide skeleton loaders with fade out
                    const skeletons = skeletonContainer.querySelectorAll('.skeleton-card');
                    skeletons.forEach(skeleton => {
                        skeleton.style.opacity = '0';
                        skeleton.style.transition = 'opacity 0.3s ease';
                    });

                    // Show actual cards with fade in
                    cards.forEach(card => {
                        card.style.display = '';
                        card.style.opacity = '';
                        card.style.transition = 'opacity 0.4s cubic-bezier(0.34, 1.56, 0.64, 1)';
                        setTimeout(() => {
                            card.style.opacity = '1';
                        }, 10);
                    });

                    // Hide skeleton container after transition
                    setTimeout(() => {
                        skeletonContainer.style.display = 'none';
                    }, 300);

                    showPage(1);
                }, 800); // Simulate 800ms loading time
            }

            function showPage(page) {
                currentPage = page;
                
                // Hide all cards first
                cards.forEach(card => {
                    card.classList.add('hidden');
                });

                // Show cards for current page
                const startIdx = (page - 1) * CARDS_PER_PAGE;
                const endIdx = page * CARDS_PER_PAGE;
                
                for (let i = startIdx; i < endIdx && i < cards.length; i++) {
                    cards[i].classList.remove('hidden');
                }

                // Update pagination buttons
                prevBtn.disabled = page === 1;
                nextBtn.disabled = page === TOTAL_PAGES;

                // Update page info
                pageInfo.textContent = `Page ${page} of ${TOTAL_PAGES}`;

                // Scroll to top of grid
                grid.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }

            function openModal(beliefNumber, beliefTitle, beliefSubtitle, beliefLink) {
                document.getElementById('modalNumber').textContent = beliefNumber;
                document.getElementById('modalTitle').textContent = beliefTitle;
                document.getElementById('modalContent').textContent = beliefSubtitle;
                document.getElementById('modalLink').href = beliefLink;
                
                modalOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeModal() {
                modalOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }

            // Add click handlers to all cards
            cards.forEach(card => {
                card.addEventListener('click', function() {
                    const beliefId = this.dataset.beliefId;
                    const beliefTitle = this.dataset.beliefTitle;
                    const beliefSubtitle = this.dataset.beliefSubtitle;
                    const beliefLink = this.dataset.beliefLink;
                    
                    openModal(beliefId, beliefTitle, beliefSubtitle, beliefLink);
                });
            });

            // Pagination controls
            prevBtn.addEventListener('click', function() {
                if (currentPage > 1) {
                    showPage(currentPage - 1);
                }
            });

            nextBtn.addEventListener('click', function() {
                if (currentPage < TOTAL_PAGES) {
                    showPage(currentPage + 1);
                }
            });

            // Close modal handlers
            modalClose.addEventListener('click', closeModal);
            
            modalOverlay.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal();
                }
            });

            // Close modal on ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modalOverlay.classList.contains('active')) {
                    closeModal();
                }
            });

            // Initialize - load cards with skeleton animation
            loadCards();
        })();
    </script>
@endsection