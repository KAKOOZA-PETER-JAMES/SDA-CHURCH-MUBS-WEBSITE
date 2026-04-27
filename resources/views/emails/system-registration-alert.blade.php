<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>New Member Registration information</title>
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
        .row { margin: 6px 0; }
        .muted { color: #516b86; }
        .card { background:#fff; border-radius:12px; padding:24px; border:1px solid #d8e3f6; box-shadow:0 4px 12px rgba(15,43,85,0.08); }
        .address-footer { margin-top: 28px; padding-top: 20px; border-top: 2px solid #e6eef8; background: #f8fafc; padding: 16px; border-radius: 8px; font-size: 13px; color: #516b86; line-height: 1.7; }
        .address-footer strong { color: #0f2b55; font-weight: 700; display: block; margin-bottom: 8px; }
        @media (max-width: 480px) {
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

            <div class="header">New member has registered</div>
            <p class="muted">A new registration was submitted on the SDA CHURCH MUBS database.</p>

            <div class="row"><strong>Full Name:</strong> {{ $data['full_name'] ?? 'N/A' }}</div>
            <div class="row"><strong>Email:</strong> {{ $data['email'] ?? 'N/A' }}</div>
            <div class="row"><strong>Phone:</strong> {{ $data['phone'] ?? 'N/A' }}</div>
            <div class="row"><strong>Category:</strong> {{ $data['category'] ?? 'N/A' }}</div>
            <div class="row"><strong>Family:</strong> {{ $data['family'] ?? 'N/A' }}</div>
            <div class="row"><strong>Receives updates:</strong> {{ !empty($data['wants_updates']) ? 'Yes' : 'No' }}</div>

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
        </div>
    </div>
</body>
</html>

