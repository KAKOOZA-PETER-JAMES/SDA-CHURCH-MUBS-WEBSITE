<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>SDA MUBS Update</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; color:#213a5a; margin:0; padding:0; background:#f5f7fb; }
        .container { max-width:600px; margin:0 auto; padding:20px; }
        .card { background:#fff; border-radius:12px; padding:24px; border:1px solid #d8e3f6; box-shadow:0 4px 12px rgba(15,43,85,0.08); }
        .brand-strip { border: 2px solid #d90000; border-radius: 0; background: #ffffff; padding: 10px 12px; margin: 0 0 14px 0; }
        .brand-strip table { width: 100%; border-collapse: collapse; }
        .brand-logo-cell { width: 52px; vertical-align: middle; }
        .brand-text-cell { vertical-align: middle; }
        .brand-logo { width: 44px; height: 44px; display: block; }
        .brand-title { margin: 0; color: #0f2b55; font-size: 22px; font-weight: 900; letter-spacing: 0.02em; }
        .header { font-weight:800; color:#102a52; font-size:20px; margin:0 0 12px 0; }
        .subheader { color:#516b86; font-size:14px; margin:0 0 20px 0; }
        .muted { color:#516b86; line-height:1.6; }
        .item { margin-bottom:16px; padding:12px; background:#f8fafc; border-left:4px solid #1f4a8a; border-radius:4px; }
        .item-title { font-weight:700; color:#102a52; margin:0 0 4px 0; }
        .item-meta { font-size:12px; color:#516b86; margin:0 0 6px 0; }
        .item-text { color:#213a5a; line-height:1.5; margin:0; }
        .btn { display:inline-block; padding:12px 24px; background:linear-gradient(135deg, #1f4a8a 0%, #102a52 100%); color:#fff; border-radius:8px; text-decoration:none; font-weight:700; border:0; cursor:pointer; }
        .address-footer { margin-top: 28px; padding-top: 20px; border-top: 2px solid #e6eef8; background: #f8fafc; padding: 16px; border-radius: 8px; font-size: 13px; color: #516b86; line-height: 1.7; }
        .address-footer strong { color: #0f2b55; font-weight: 700; display: block; margin-bottom: 8px; }
        .btn:hover { background:linear-gradient(135deg, #102a52 0%, #0a1929 100%); }
        .social-wrap { margin-top: 24px; padding-top: 20px; border-top: 2px solid #e6eef8; }
        .social-title { margin: 0 0 14px; font-size: 12px; font-weight: 800; letter-spacing: 0.08em; color: #102a52; text-transform: uppercase; }
        .social-list { list-style: none; margin: 0; padding: 0; display: flex; gap: 12px; flex-wrap: wrap; align-items: center; }
        .social-item { display: inline-block; }
        .social-link { display: inline-flex; align-items: center; justify-content: center; width: auto; min-width: 24px; height: auto; border-radius: 0; text-decoration: none; font-weight: 800; font-size: 20px; line-height: 1; transition: transform 0.2s ease, opacity 0.2s ease; box-shadow: none; background: transparent; padding: 0; }
        .social-link:hover { transform: translateY(-1px); opacity: 0.82; }
        .social-link.facebook { color: #1877f2; }
        .social-link.twitter { color: #111111; }
        .social-link.tiktok { color: #111111; }
        .social-link.youtube { color: #ff0000; }
        @media (max-width: 480px) {
            .social-list { gap: 10px; }
            .social-link { font-size: 18px; }
            .brand-title { font-size: 18px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="brand-strip" role="presentation" aria-label="SDA CHURCH MUBS identity">
                <table role="presentation">
                    <tr>
                        <td class="brand-logo-cell">
                            <img class="brand-logo" src="http://mubssdachurch2026.great-site.net/6.png" alt="SDA CHURCH MUBS logo">
                        </td>
                        <td class="brand-text-cell">
                            <p class="brand-title">SDA CHURCH MUBS - KIREKA HILL DISTRICT</p>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="header">SDA MUBS - Latest Updates</div>
            <p class="subheader">Hello! We have exciting updates to share. Stay informed about what's happening at SDA CHURCH MUBS.</p>

            @if(!empty($content['updates']))
                @foreach($content['updates'] as $upd)
                    <div class="item">
                        <p class="item-title">{{ $upd['title'] ?? 'Update' }}</p>
                        @php
                            $meta = trim(implode(' • ', array_filter([
                                $upd['month'] ?? null,
                                $upd['date_range'] ?? null,
                                $upd['department'] ?? null,
                            ])));
                        @endphp
                        @if($meta !== '')
                            <p class="item-meta">{{ $meta }}</p>
                        @endif
                        @if(!empty($upd['details']))
                            <p class="item-text">{{ $upd['details'] }}</p>
                        @endif
                    </div>
                @endforeach
            @else
                <p class="muted">There are general updates at the church. Visit the site for more details.</p>
            @endif

            <p style="margin-top:20px;"><a class="btn" href="http://mubssdachurch2026.great-site.net/">Open SDA CHURCH MUBS</a></p>

            <div class="address-footer">
                <strong>📍 Our Location</strong>
                Makerere University Business School (MUBS) - Nakawa<br>
                Old Portbell Road, P.O. Box 7062, Kampala<br>
                Kamya II (next to Entrepreneurship Innovation Hub & MUBS Police Station)<br>
                Kireka Hill District | Central Uganda Conference<br>
                <br>
                <strong>📞 Contact Us</strong><br>
                Phone: +256 709 061 019<br>
                Email: info@mubssdachurch.org
            </div>

            <div class="social-wrap">
                <p class="social-title">Follow Us on Social Media</p>
                <ul class="social-list">
                    <li class="social-item">
                        <a class="social-link facebook" href="https://www.facebook.com/Musdaabs" target="_blank" rel="noopener noreferrer" title="Facebook">f</a>
                    </li>
                    <li class="social-item">
                        <a class="social-link twitter" href="https://x.com/Musdaa_Mubs?s=09" target="_blank" rel="noopener noreferrer" title="X (Twitter)">𝕏</a>
                    </li>
                    <li class="social-item">
                        <a class="social-link tiktok" href="https://www.tiktok.com/@musdaamubs1?_r=1&_t=ZM-91OWrIpRGUm" target="_blank" rel="noopener noreferrer" title="TikTok" aria-label="TikTok" style="color:#000000 !important;">♪</a>
                    </li>
                    <li class="social-item">
                        <a class="social-link youtube" href="https://youtube.com/@musdaa-mubs5019?si=Krf5rbOxB7XJ3_x6" target="_blank" rel="noopener noreferrer" title="YouTube">▶</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>

