@extends('layouts.site')

@section('title', 'SDA CHURCH MUBS | Contact')

@section('content')
    <style>
        .contact-page {
            display: grid;
            gap: 1rem;
        }

        .contact-section {
            background: transparent;
            border: 0;
            border-radius: 0;
            padding: 0;
        }

        .contact-heading {
            margin: 0 0 0.85rem;
            font-size: 2rem;
            line-height: 1.08;
            color: #0f2b55;
            font-weight: 800;
            border-left: 5px solid #0f2b55;
            padding-left: 0.65rem;
            text-transform: uppercase;
        }

        .contact-heading strong {
            color: #3e8391;
        }

        .contact-intro-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 1rem;
        }

        .contact-copy,
        .contact-meta {
            color: #0f2038;
            line-height: 1.55;
        }


        .contact-meta p {
            margin: 0 0 0.5rem;
        }

        .contact-meta strong {
            color: #0f2b55;
        }

        .contact-main-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
            align-items: start;
            border-top: 1px solid #d9e0ec;
            padding-top: 0.9rem;
        }

        .contact-subtitle {
            margin: 0 0 0.8rem;
            color: #0f2b55;
            font-size: 1.6rem;
            line-height: 1.15;
            font-weight: 800;
            border-left: 4px solid #3e8391;
            padding-left: 0.55rem;
            text-transform: uppercase;
        }

        .contact-map-wrap {
            border: 1px solid #d9e0ec;
            border-radius: 10px;
            overflow: hidden;
            background: #ffffff;
        }

        .contact-map {
            width: 100%;
            height: 460px;
            border: 0;
            display: block;
        }

        .contact-find-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-top: 0.9rem;
            border: 1px solid #0f2b55;
            background: #0f2b55;
            color: #ffffff;
            border-radius: 999px;
            padding: 0.58rem 1.1rem;
            font-weight: 700;
            text-decoration: none;
        }

        .contact-find-btn:hover {
            background: #153665;
            border-color: #153665;
        }

        .contact-forum {
            border-top: 1px solid #d9e0ec;
            padding-top: 0.95rem;
        }

        .contact-forum h2 {
            margin: 0;
            color: #0f2b55;
            font-size: 1.4rem;
            text-transform: uppercase;
        }

        .contact-forum p {
            margin: 0.55rem 0 0;
            color: #0f2038;
            line-height: 1.6;
            max-width: 860px;
        }

        .contact-forum-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-top: 0.8rem;
            border: 1px solid #0f2b55;
            background: #0f2b55;
            color: #ffffff;
            border-radius: 8px;
            padding: 0.55rem 0.95rem;
            font-weight: 700;
            text-decoration: none;
        }

        .contact-forum-link:hover {
            background: #153665;
            border-color: #153665;
        }

        .contact-fellowship-calendar {
            margin-top: 1.1rem;
            border: 0;
            border-radius: 16px;
            background: linear-gradient(150deg, #f4f8ff 0%, #eaf2ff 55%, #f8fbff 100%);
            overflow: hidden;
            box-shadow: 0 14px 30px rgba(12, 38, 74, 0.14);
        }

        .contact-fellowship-calendar h2 {
            margin: 0;
            padding: 1rem 1.1rem;
            background: linear-gradient(135deg, #0f2b55 0%, #1a4578 100%);
            color: #ffffff;
            font-size: 1.04rem;
            letter-spacing: 0.7px;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 0.45rem;
        }

        .contact-fellowship-calendar h2::before {
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
            .contact-intro-grid,
            .contact-main-grid {
                grid-template-columns: 1fr;
            }
            
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

    <section class="contact-page" aria-label="Contact page">
        <article class="contact-section">
            <h1 class="contact-heading">Contact<strong>.</strong></h1>
            <div class="contact-intro-grid">
                <div class="contact-copy">
                    <p>SDA CHURCH MUBS Kireka Hill District is a vibrant and Holy Spirit-filled congregation, firmly grounded in Jesus Christ, committed to evangelism, discipleship, and preparing people for His second coming.</p>
                </div>
  <div class="address-footer">
                <strong>📍 Our Location</strong>
                Makerere University Business School (MUBS) - Nakawa<br>
                Old Portbell Road, P.O. Box 7062, Kampala<br>
                Kamya II (next to Entrepreneurship Innovation Hub & MUBS Police Station)<br>
                Kireka Hill District | Central Uganda Conference<br>
                <br>
                <strong>📞 Contact Us</strong><br>
                Phone: +256 709 061 019<br>
                Email: mubssdachurch@gmail.com
            </div>

                <div class="contact-meta">
                    <p><strong>Opening Hours:</strong></p>
                    <p>Thursday: 5:00am - 7:30pm (Fellowship)</p>
                    <p>Friday: 5:00am - 7:30pm (Sabbath Welcoming)</p>
                    <p>Saturday: Sabbath Worship 8:00am - 6:00pm</p>
                    <p>Sun: Closed</p>
                </div>
            </div>

            <section class="contact-fellowship-calendar" aria-label="Fellowship program calendar">
                <h2>FELLOWSHIP PROGRAM</h2>
                <div class="fellowship-accordion" id="contact-fellowship-accordion">
                    <div class="fellowship-item">
                        <button class="fellowship-trigger" aria-expanded="false" data-day="Monday">
                            <span class="fellowship-trigger-day">Monday (Second Day)</span>
                            <span class="fellowship-trigger-icon">⏱️</span>
                        </button>
                        <div class="fellowship-content">
                            <div class="fellowship-details">
                                <div class="fellowship-detail-row">
                                    <span class="fellowship-detail-label">Area:</span>
                                    <span class="fellowship-detail-value">Mwanga</span>
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
                            <span class="fellowship-trigger-icon">⏱️</span>
                        </button>
                        <div class="fellowship-content">
                            <div class="fellowship-details">
                                <div class="fellowship-detail-row">
                                    <span class="fellowship-detail-label">Area:</span>
                                    <span class="fellowship-detail-value">Kataza</span>
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
                            <span class="fellowship-trigger-icon">⏱️</span>
                        </button>
                        <div class="fellowship-content">
                            <div class="fellowship-details">
                                <div class="fellowship-detail-row">
                                    <span class="fellowship-detail-label">Area:</span>
                                    <span class="fellowship-detail-value">Small Gate</span>
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
                            <span class="fellowship-trigger-icon">⏱️</span>
                        </button>
                        <div class="fellowship-content">
                            <div class="fellowship-details">
                                <div class="fellowship-detail-row">
                                    <span class="fellowship-detail-label">Area:</span>
                                    <span class="fellowship-detail-value">Berlin Common Room</span>
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
                            <span class="fellowship-trigger-icon">⏱️</span>
                        </button>
                        <div class="fellowship-content">
                            <div class="fellowship-details">
                                <div class="fellowship-detail-row">
                                    <span class="fellowship-detail-label">Area:</span>
                                    <span class="fellowship-detail-value">Berlin Common Room</span>
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
                            <span class="fellowship-trigger-icon">⏱️</span>
                        </button>
                        <div class="fellowship-content">
                            <div class="fellowship-details">
                                <div class="fellowship-detail-row">
                                    <span class="fellowship-detail-label">Area:</span>
                                    <span class="fellowship-detail-value">Berlin Common Room</span>
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

        </article>

        <article class="contact-section">
            <div class="contact-main-grid">
                <div>
                    <h2 class="contact-subtitle">Locate Your Church<strong>.</strong></h2>
                    <div class="contact-map-wrap" aria-label="Map direction to Berlin Common Room">
                        <iframe
                            class="contact-map"
                            src="https://www.google.com/maps?q=Berlin%20Common%20Room%20MUBS%20Nakawa%20Kampala&output=embed"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            allowfullscreen
                            title="Map showing direction to Berlin Common Room"
                        ></iframe>
                    </div>
                    <a class="contact-find-btn" href="https://www.adventistlocator.org/" target="_blank" rel="noopener">Find here</a>
                </div>

                <section class="contact-forum" aria-label="Church forum link">
                    <h2>Church Forum</h2>
                    <p>Join our discussion room to ask questions, share testimonies, post announcements, and interact with other church members in one place.</p>
                    <a class="contact-forum-link" href="{{ route('forum') }}">Start</a>
                </section>
            </div>
        </article>
    </section>
@endsection

