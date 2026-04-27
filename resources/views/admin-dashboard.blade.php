@extends('layouts.site')

@section('title', 'SDA CHURCH MUBS | Admin Dashboard')

@section('content')
    @php
        $pastor = $content['pastor'] ?? [];
        $pastorsSinceInception = $content['pastors_since_inception'] ?? [];
        $associationPreviousExecutivesRaw = $content['association_previous_executives'] ?? [];
        $eventMedia = $content['event_media'] ?? [];
        $eventCategoryOptions = [
            'evangelism-campaign' => 'Evangelism Campaign',
            'community-outreach' => 'Community Outreach',
            'social-events' => 'Social Events',
        ];
        $familyCalendarActivities = $content['family_calendar_activities'] ?? [];
        $updates = $content['updates'] ?? [];
        $upcomingSabbathsRaw = $content['upcoming_sabbaths'] ?? [];
        $dailyCommunicationRaw = $content['daily_communication'] ?? [];
        $departments = $content['departments'] ?? [];
        $registrations = $registrations ?? collect();
        $studentRegistrationsCount = (int) ($studentRegistrationsCount ?? $registrations->where('category', 'Student')->count());
        $otherRegistrationsCount = (int) ($otherRegistrationsCount ?? $registrations->where('category', 'Other')->count());
        $forumMessagesCount = (int) ($forumMessagesCount ?? 0);
        $forumMessages = collect($forumMessages ?? []);
        $dashboardMetrics = collect([
            ['label' => 'Students', 'value' => $studentRegistrationsCount, 'tone' => 'students'],
            ['label' => 'Others', 'value' => $otherRegistrationsCount, 'tone' => 'others'],
            ['label' => 'Forum Messages', 'value' => $forumMessagesCount, 'tone' => 'forum'],
        ]);
        $dashboardMaxMetric = max(1, (int) $dashboardMetrics->max('value'));

        $boardRows = $departments['church_board'] ?? [];
        $associationRows = $departments['association'] ?? [];
        $familyRows = $departments['church_families'] ?? [];
        $boardLeaderRows = $departments['church_board_leaders'] ?? [];
        $associationLeaderRows = $departments['association_leaders'] ?? [];

        $normalizeNoticeRows = function (array $rows): array {
            $normalized = [];

            foreach ($rows as $row) {
                if (is_string($row)) {
                    $normalized[] = ['text' => $row, 'media_url' => ''];
                    continue;
                }

                if (is_array($row)) {
                    $normalized[] = [
                        'text' => (string) ($row['text'] ?? ''),
                        'media_url' => (string) ($row['media_url'] ?? ''),
                    ];
                }
            }

            return $normalized;
        };

        $padRows = function (array $rows, int $minimum, array $template) {
            $result = array_values($rows);

            while (count($result) < $minimum) {
                $result[] = $template;
            }

            return $result;
        };

        $resolvePreview = function (?string $path): ?string {
            $value = trim((string) $path);
            if ($value === '') {
                return null;
            }

            $value = str_replace('\\', '/', $value);
            $value = preg_replace('#^\./#', '', $value);
            $value = preg_replace('#^public/#i', '', $value);
            $value = ltrim($value, '/');

            if (preg_match('/^https?:\/\//i', $value)) {
                return $value;
            }

            if (strpos($value, '..') !== false) {
                return null;
            }

            // Return a public URL without requiring local file_exists() checks.
            return asset($value);
        };

        $pastorsSinceInception = $padRows($pastorsSinceInception, 14, ['name' => '', 'years' => '', 'photo' => '']);
        $eventMedia = $padRows($eventMedia, 20, ['category' => '', 'section' => 'story', 'title' => '', 'description' => '', 'media_url' => '', 'thumbnail' => '']);
        $familyCalendarActivities = $padRows($familyCalendarActivities, 16, ['date' => '', 'day' => '', 'area' => '', 'activity' => '', 'time' => '']);
        $updates = $padRows($updates, 12, ['month' => '', 'title' => '', 'date_range' => '', 'department' => '', 'details' => '']);
        $upcomingSabbaths = $padRows($normalizeNoticeRows((array) $upcomingSabbathsRaw), 10, ['text' => '', 'media_url' => '']);
        $dailyCommunication = $padRows($normalizeNoticeRows((array) $dailyCommunicationRaw), 10, ['text' => '', 'media_url' => '']);
        $deptTemplate = [
            'name' => '',
            'image' => '',
            'intro' => '',
            'department_introduction' => '',
            'department_head_name' => '',
            'department_head_photo' => '',
            'secretary_name' => '',
            'secretary_photo' => '',
            'explore_url' => '',
            'details' => '',
            'pastor_name' => '',
            'pastor_phone' => '',
            'pastor_email' => '',
            'pastor_info' => '',
            'family_head_name' => '',
            'family_secretary_name' => '',
            'family_spiritual_leader' => '',
            'family_financial_mobiliser' => '',
            'family_social_wellbeing_leader' => '',
            'family_contact' => '',
            'family_email' => '',
        ];

        $boardRows = $padRows($boardRows, 16, $deptTemplate);
        $associationRows = $padRows($associationRows, 16, $deptTemplate);
        $familyRows = $padRows($familyRows, 16, $deptTemplate);
        $leaderTemplate = ['role' => '', 'name' => '', 'message' => '', 'image' => ''];
        $boardLeaderRows = $padRows($boardLeaderRows, 6, $leaderTemplate);
        $associationLeaderRows = $padRows($associationLeaderRows, 6, $leaderTemplate);
        $heroSlides = $content['hero_slides'] ?? [];
        $heroSlides = $padRows($heroSlides, 4, ['title' => '', 'subtitle' => '', 'image' => '', 'link' => '', 'text_color' => '#ffffff']);

        $associationExecTemplate = ['role' => '', 'name' => '', 'photo' => ''];
        $associationPreviousExecutives = $padRows(
            array_values(array_map(function ($row) {
                return [
                    'years' => (string) ($row['years'] ?? ''),
                    'name' => (string) ($row['name'] ?? ''),
                    'photo' => (string) ($row['photo'] ?? ''),
                    'executives' => is_array($row['executives'] ?? null) ? array_values($row['executives']) : [],
                ];
            }, is_array($associationPreviousExecutivesRaw) ? $associationPreviousExecutivesRaw : [])),
            5,
            ['years' => '', 'name' => '', 'photo' => '', 'executives' => []]
        );

        foreach ($associationPreviousExecutives as $index => $row) {
            $associationPreviousExecutives[$index]['executives'] = $padRows(
                is_array($row['executives'] ?? null) ? $row['executives'] : [],
                8,
                $associationExecTemplate
            );
        }
    @endphp

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.8.0/build/css/intlTelInput.css">

    <style>
        .admin-wrap {
            --admin-accent: #0f2b55;
            --admin-accent-soft: #1f4a8a;
            --admin-glow: #ffd166;
            --admin-bg: #f3f7ff;
            --admin-card-bg: rgba(255, 255, 255, 0.94);
            --admin-border: #d8e3f6;
            --admin-shadow: 0 18px 42px rgba(15, 43, 85, 0.14);
            max-width: 1240px;
            margin: 0 auto;
            padding-inline: clamp(0.25rem, 1.4vw, 0.75rem);
            display: grid;
            gap: 1rem;
        }

        .admin-wrap {
            position: relative;
        }

        .admin-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.7rem;
        }

        .admin-card-title-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.7rem;
            flex-wrap: wrap;
            margin-bottom: 0.65rem;
        }

        .admin-card-note {
            margin: 0;
            color: #5b6780;
            font-size: 0.86rem;
        }

        .admin-inline-preview {
            width: 56px;
            height: 56px;
            object-fit: cover;
            border-radius: 999px;
            border: 1px solid #cfdaeb;
            background: #f2f6fd;
            display: block;
            margin-top: 0.35rem;
        }

        .admin-media-preview-box {
            margin-top: 0.4rem;
            border: 1px solid #cfdaeb;
            border-radius: 8px;
            background: #f7faff;
            padding: 0.42rem;
            min-height: 72px;
            display: grid;
            place-items: center;
            color: #5b6780;
            font-size: 0.8rem;
            text-align: center;
        }

        .admin-media-preview-box img,
        .admin-media-preview-box video {
            width: 100%;
            max-height: 130px;
            object-fit: cover;
            border-radius: 6px;
            display: block;
            background: #edf3ff;
        }

        .admin-media-preview-box a {
            color: #1f4a8a;
            font-weight: 700;
            text-decoration: none;
            word-break: break-all;
        }

        .admin-hero {
            position: relative;
            background:
                radial-gradient(circle at 88% -18%, rgba(255, 209, 102, 0.35), transparent 40%),
                linear-gradient(130deg, #0f2b55 0%, #1e4f8f 54%, #2e6cb8 100%);
            border: 1px solid rgba(255, 255, 255, 0.24);
            border-radius: 18px;
            padding: clamp(0.9rem, 2.2vw, 1.35rem);
            box-shadow: var(--admin-shadow);
            overflow: hidden;
        }

        .admin-hero h1 {
            margin: 0;
            color: #ffffff;
            letter-spacing: 0.01em;
        }

        .admin-hero p {
            margin: 0.45rem 0 0;
            color: rgba(240, 246, 255, 0.95);
            max-width: 74ch;
            line-height: 1.5;
        }

        .admin-card {
            background: var(--admin-card-bg);
            border: 1px solid var(--admin-border);
            border-radius: 16px;
            padding: clamp(0.72rem, 1.8vw, 1rem);
            overflow-x: auto;
            max-width: 100%;
            box-shadow: 0 10px 25px rgba(15, 43, 85, 0.08);
            backdrop-filter: blur(3px);
        }

        .admin-insights {
            display: grid;
            gap: 0.9rem;
            background:
                linear-gradient(180deg, #f9fbff 0%, #eff5ff 100%);
        }

        .admin-insights-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.65rem;
        }

        .admin-insight-card {
            border: 1px solid #d7e2f5;
            border-radius: 14px;
            padding: 0.7rem 0.75rem;
            background: #ffffff;
            min-width: 0;
        }

        .admin-insight-label {
            margin: 0;
            font-size: 0.76rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #4d6488;
            font-weight: 800;
        }

        .admin-insight-value {
            margin: 0.22rem 0 0;
            font-size: clamp(1.2rem, 2.6vw, 1.65rem);
            font-weight: 900;
            color: var(--admin-accent);
            line-height: 1.1;
        }

        .admin-chart {
            border: 1px solid #d9e4f8;
            border-radius: 14px;
            background: #ffffff;
            padding: 0.8rem;
            display: grid;
            gap: 0.62rem;
        }

        .admin-chart-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.65rem;
            flex-wrap: wrap;
        }

        .admin-chart-title {
            margin: 0;
            font-size: 0.95rem;
            color: #183968;
            font-weight: 800;
            letter-spacing: 0.01em;
        }

        .admin-chart-subtitle {
            margin: 0;
            font-size: 0.78rem;
            color: #526987;
            font-weight: 700;
        }

        .admin-chart-list {
            margin: 0;
            padding: 0;
            list-style: none;
            display: grid;
            gap: 0.58rem;
        }

        .admin-chart-row {
            display: grid;
            grid-template-columns: minmax(90px, 150px) minmax(0, 1fr) auto;
            align-items: center;
            gap: 0.55rem;
            min-width: 0;
        }

        .admin-chart-label {
            font-size: 0.78rem;
            color: #355174;
            font-weight: 800;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .admin-chart-track {
            height: 11px;
            width: 100%;
            border-radius: 999px;
            background: #eaf1ff;
            overflow: hidden;
        }

        .admin-chart-fill {
            height: 100%;
            border-radius: inherit;
            width: 0;
            transition: width 0.45s ease;
        }

        .admin-chart-fill.students {
            background: linear-gradient(90deg, #2470f5 0%, #6aa5ff 100%);
        }

        .admin-chart-fill.others {
            background: linear-gradient(90deg, #19a37d 0%, #52d6b3 100%);
        }

        .admin-chart-fill.forum {
            background: linear-gradient(90deg, #f0a202 0%, #ffd166 100%);
        }

        .admin-chart-value {
            color: #102d5a;
            font-size: 0.8rem;
            font-weight: 900;
            min-width: 2.2rem;
            text-align: right;
        }

        .admin-card.slim {
            padding: 0.75rem;
        }

        .admin-card h2 {
            margin: 0 0 0.65rem;
            color: #0f2b55;
            font-size: 1.2rem;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 0 !important;
            table-layout: fixed;
        }

        .admin-table th,
        .admin-table td {
            border: 1px solid #dce4ef;
            padding: 0.45rem;
            vertical-align: top;
            white-space: normal;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .admin-table th[style],
        .admin-table td[style],
        .admin-table[style] {
            width: auto !important;
            min-width: 0 !important;
        }

        .admin-table th {
            background: #f4f8ff;
            color: #1f4a8a;
            text-align: left;
            font-size: 0.85rem;
        }

        .admin-input,
        .admin-textarea {
            width: 100%;
            border: 1px solid #cfd8e7;
            border-radius: 6px;
            padding: 0.45rem 0.5rem;
            font-size: 0.9rem;
            font-family: inherit;
        }

        .iti {
            width: 100%;
        }

        .iti__country-list {
            z-index: 10000;
        }

        .admin-textarea {
            min-height: 74px;
            resize: vertical;
        }

        .admin-simple-grid {
            display: grid;
            gap: 0.55rem;
        }

        .admin-two-col {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 0.65rem;
        }

        .admin-actions {
            display: flex;
            gap: 0.6rem;
            justify-content: flex-end;
            flex-wrap: wrap;
        }

        .admin-panel-nav {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
            gap: 0.55rem;
        }

        .admin-panel-btn {
            border: 1px solid #cfdaeb;
            background: linear-gradient(180deg, #f8fbff 0%, #edf4ff 100%);
            color: #163a69;
            border-radius: 12px;
            padding: 0.62rem 0.75rem;
            font-size: 0.88rem;
            font-weight: 800;
            cursor: pointer;
            text-align: center;
            transition: transform 0.18s ease, background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease;
        }

        .admin-panel-btn:hover {
            border-color: #1f4a8a;
            background: #e5eeff;
            transform: translateY(-1px);
        }

        .admin-panel-btn.is-active {
            border-color: #123562;
            background: linear-gradient(135deg, #0f2b55 0%, #1f4a8a 100%);
            color: #ffffff;
            box-shadow: 0 10px 20px rgba(15, 43, 85, 0.2);
        }

        .admin-card.is-hidden {
            display: none;
        }

        .admin-btn {
            border: 1px solid #0f2b55;
            background: linear-gradient(135deg, #0f2b55 0%, #1f4a8a 100%);
            color: #ffffff;
            border-radius: 999px;
            padding: 0.55rem 1rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }

        .admin-btn.secondary {
            background: #ffffff;
            color: #0f2b55;
        }

        .admin-btn.small {
            padding: 0.4rem 0.78rem;
            font-size: 0.86rem;
        }

        .admin-success {
            margin: 0;
            border: 1px solid #b8e0c0;
            background: #edf8ef;
            color: #1f6b35;
            border-radius: 8px;
            padding: 0.65rem 0.75rem;
            font-size: 0.9rem;
        }

        .admin-count {
            margin: 0;
            color: #44516a;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .admin-member-totals {
            margin-top: 0.45rem;
            display: flex;
            gap: 0.45rem;
            flex-wrap: wrap;
        }

        .admin-member-total-chip {
            border: 1px solid #d1deef;
            border-radius: 999px;
            padding: 0.28rem 0.62rem;
            background: #f6f9ff;
            color: #1d3f6b;
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.02em;
        }

        .admin-forum-tools {
            margin: 0.6rem 0 0.7rem;
            border: 1px solid #d6e1f1;
            border-radius: 10px;
            background: #ffffff;
            padding: 0.6rem;
            display: grid;
            gap: 0.45rem;
        }

        .admin-forum-tools-title {
            margin: 0;
            color: #0f2b55;
            font-size: 0.9rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .admin-forum-tools-meta {
            margin: 0;
            color: #395577;
            font-size: 0.82rem;
            font-weight: 700;
        }

        .admin-forum-tools-status {
            margin: 0;
            color: #1f4a8a;
            font-size: 0.8rem;
            font-weight: 700;
            min-height: 1.1rem;
        }

        .admin-forum-chat-list {
            margin-top: 0.75rem;
            display: grid;
            gap: 0.65rem;
        }

        .admin-forum-chat-item {
            border: 1px solid #d9e3f1;
            border-radius: 10px;
            background: #fff;
            padding: 0.75rem;
            display: grid;
            gap: 0.45rem;
            box-shadow: 0 5px 14px rgba(16, 42, 82, 0.06);
        }

        .admin-forum-chat-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .admin-forum-chat-name {
            margin: 0;
            color: #0f2b55;
            font-size: 0.98rem;
            font-weight: 800;
        }

        .admin-forum-chat-meta {
            margin: 0.15rem 0 0;
            color: #5b6780;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .admin-forum-chat-message {
            margin: 0;
            color: #22334e;
            font-size: 0.9rem;
            line-height: 1.55;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
        }

        .admin-forum-chat-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
            border-top: 1px solid #edf2fb;
            padding-top: 0.55rem;
        }

        .admin-forum-chat-attachment {
            color: #1f4a8a;
            font-size: 0.82rem;
            font-weight: 700;
            text-decoration: none;
        }

        .admin-forum-chat-delete {
            border: 1px solid #e2b8b8;
            background: #fff4f4;
            color: #9c2f2f;
            border-radius: 8px;
            padding: 0.42rem 0.7rem;
            font-size: 0.82rem;
            font-weight: 800;
            cursor: pointer;
        }

        .admin-members-book {
            position: relative;
            margin-top: 0.75rem;
            border: 1px solid #d4deed;
            border-radius: 14px;
            background: linear-gradient(180deg, #f9fbff 0%, #f1f6ff 100%);
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.55), 0 14px 30px rgba(15, 43, 85, 0.11);
            overflow: hidden;
            padding: 0.9rem 0.9rem 0.95rem 1.5rem;
        }

        .admin-members-book::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            width: 18px;
            background: linear-gradient(180deg, #0f2b55 0%, #173e74 100%);
            box-shadow: inset -2px 0 5px rgba(0, 0, 0, 0.22);
        }

        .admin-members-book::after {
            content: '';
            position: absolute;
            top: 0;
            left: 18px;
            bottom: 0;
            width: 8px;
            background: linear-gradient(180deg, #e8eef9 0%, #d9e4f7 100%);
            box-shadow: inset -1px 0 0 rgba(15, 43, 85, 0.1);
        }

        .admin-members-book-meta {
            margin: 0 0 0.8rem;
            color: #2d4466;
            font-size: 0.86rem;
            font-weight: 700;
        }

        .admin-members-grid {
            display: grid;
            gap: 0.65rem;
        }

        .admin-member-card,
        .admin-member-accordion-item {
            border: 1px solid #d6e0f0;
            border-radius: 10px;
            background: #ffffff;
            box-shadow: 0 5px 14px rgba(16, 42, 82, 0.08);
        }

        .admin-member-accordion-item {
            overflow: hidden;
        }

        .admin-member-accordion-summary {
            list-style: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.65rem;
            padding: 0.72rem;
            background: linear-gradient(180deg, #ffffff 0%, #f6f9ff 100%);
        }

        .admin-member-accordion-summary::-webkit-details-marker {
            display: none;
        }

        .admin-member-accordion-summary-main {
            min-width: 0;
        }

        .admin-member-accordion-summary .admin-member-id {
            margin-bottom: 0;
        }

        .admin-member-accordion-caret {
            width: 26px;
            height: 26px;
            border-radius: 999px;
            border: 1px solid #c8d8ef;
            color: #1f4a8a;
            display: grid;
            place-items: center;
            font-size: 0.86rem;
            font-weight: 900;
            flex-shrink: 0;
            transition: transform 0.2s ease;
        }

        .admin-member-accordion-item[open] .admin-member-accordion-caret {
            transform: rotate(180deg);
        }

        .admin-member-accordion-body {
            border-top: 1px solid #e5edf9;
            padding: 0.72rem;
        }

        .admin-member-name,
        .admin-member-accordion-summary .admin-member-name {
            margin: 0;
            color: #0f2b55;
            font-size: 1rem;
            line-height: 1.28;
            font-weight: 800;
        }

        .admin-member-id {
            margin: 0.2rem 0 0.55rem;
            color: #4f6281;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .admin-member-card dl,
        .admin-member-accordion-body dl {
            margin: 0;
            display: grid;
            grid-template-columns: 108px minmax(0, 1fr);
            gap: 0.35rem 0.55rem;
            align-items: start;
        }

        .admin-member-card dt,
        .admin-member-accordion-body dt {
            margin: 0;
            color: #1f4a8a;
            font-size: 0.76rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            font-weight: 800;
        }

        .admin-member-card dd,
        .admin-member-accordion-body dd {
            margin: 0;
            color: #2c3e5a;
            font-size: 0.83rem;
            line-height: 1.35;
            overflow-wrap: anywhere;
        }

        .admin-members-empty {
            margin: 0;
            border: 1px dashed #c7d5ea;
            border-radius: 10px;
            padding: 0.85rem;
            background: #ffffff;
            color: #4b5f7d;
            text-align: center;
            font-weight: 700;
        }

        .admin-updates-accordion {
            display: grid;
            gap: 0.7rem;
        }

        .admin-update-item {
            border: 1px solid #d6e1f1;
            border-radius: 12px;
            background: #ffffff;
            overflow: hidden;
            box-shadow: 0 10px 24px rgba(15, 43, 85, 0.08);
        }

        .admin-update-summary {
            list-style: none;
            cursor: pointer;
            padding: 0.8rem 0.9rem;
            background: linear-gradient(135deg, #0f2b55 0%, #1f4a8a 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.8rem;
        }

        .admin-update-summary::-webkit-details-marker {
            display: none;
        }

        .admin-update-summary-main {
            display: grid;
            gap: 0.22rem;
            min-width: 0;
        }

        .admin-update-chip {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            opacity: 0.88;
            font-weight: 700;
        }

        .admin-update-title {
            margin: 0;
            font-size: 0.98rem;
            font-weight: 800;
            line-height: 1.25;
            word-break: break-word;
        }

        .admin-update-chevron {
            width: 28px;
            height: 28px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.18);
            display: grid;
            place-items: center;
            font-size: 1rem;
            transition: transform 0.25s ease;
            flex-shrink: 0;
        }

        details[open] .admin-update-chevron {
            transform: rotate(180deg);
        }

        .admin-update-fields {
            padding: 0.8rem;
            display: grid;
            gap: 0.65rem;
            background: #f7faff;
        }

        .admin-update-fields-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.55rem;
        }

        .admin-update-fields label {
            display: grid;
            gap: 0.28rem;
            color: #1f4a8a;
            font-weight: 700;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        @media (max-width: 980px) {
            .admin-card {
                padding: 0.7rem;
            }

            .admin-insights-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .admin-chart-row {
                grid-template-columns: minmax(85px, 120px) minmax(0, 1fr) auto;
            }

            .admin-panel-nav {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .admin-accordion-content {
                overflow: auto;
            }

            .admin-update-fields-grid {
                grid-template-columns: 1fr;
            }

            .admin-table th,
            .admin-table td {
                padding: 0.36rem;
                font-size: 0.82rem;
            }

            .admin-input,
            .admin-textarea {
                font-size: 0.84rem;
                padding: 0.36rem 0.42rem;
            }
        }

        @media (max-width: 760px) {
            .admin-wrap {
                gap: 0.75rem;
                padding-inline: 0.25rem;
            }

            .admin-card {
                border-radius: 12px;
            }

            .admin-insights-grid {
                grid-template-columns: 1fr;
            }

            .admin-chart {
                padding: 0.7rem;
            }

            .admin-chart-row {
                grid-template-columns: 1fr;
                gap: 0.35rem;
            }

            .admin-chart-label,
            .admin-chart-value {
                text-align: left;
            }

            .admin-chart-track {
                height: 10px;
            }

            .admin-panel-nav {
                grid-template-columns: 1fr;
            }

            .admin-panel-btn {
                text-align: left;
                padding: 0.7rem 0.75rem;
            }

            .admin-accordion-header {
                padding: 0.72rem 0.78rem;
            }

            .admin-card.accordion-expanded .admin-accordion-content {
                padding: 0.75rem;
            }

            .admin-table {
                border-collapse: separate;
                border-spacing: 0;
            }

            .admin-table thead {
                display: none;
            }

            .admin-table tbody,
            .admin-table tr,
            .admin-table td {
                display: block;
                width: 100%;
            }

            .admin-table tr {
                border: 1px solid #dce4ef;
                border-radius: 10px;
                background: #fbfdff;
                margin-bottom: 0.65rem;
                padding: 0.25rem;
            }

            .admin-table td {
                border: 0;
                border-top: 1px solid #e6edf7;
                padding: 0.45rem 0.4rem;
            }

            .admin-table td:first-child {
                border-top: 0;
            }

            .admin-table td::before {
                content: attr(data-label);
                display: block;
                margin-bottom: 0.28rem;
                color: #1f4a8a;
                font-size: 0.74rem;
                font-weight: 800;
                letter-spacing: 0.02em;
                text-transform: uppercase;
            }
            
            .admin-table td:has(input[type="checkbox"]) {
                padding: 0.55rem 0.4rem;
            }
            
            .admin-table label {
                display: flex !important;
                align-items: center;
                gap: 0.4rem;
                font-weight: 600;
                font-size: 0.9rem;
                color: #1a1a1a;
            }
            
            .admin-table input[type="checkbox"] {
                min-width: 20px;
                width: 20px;
                height: 20px;
                cursor: pointer;
                flex-shrink: 0;
            }

            .admin-table td[colspan] {
                text-align: left;
            }

            .admin-table td[colspan]::before {
                display: none;
            }

            .admin-members-book {
                padding: 0.76rem 0.72rem 0.8rem 1.2rem;
            }

            .admin-members-book::before {
                width: 14px;
            }

            .admin-members-book::after {
                left: 14px;
                width: 6px;
            }

            .admin-members-grid {
                grid-template-columns: 1fr;
            }

            .admin-member-card dl,
            .admin-member-accordion-body dl {
                grid-template-columns: 90px minmax(0, 1fr);
                gap: 0.32rem 0.45rem;
            }

            .admin-actions {
                display: flex;
                flex-direction: column;
                gap: 0.55rem;
            }

            .admin-actions .admin-btn,
            .admin-actions a.admin-btn {
                width: 100%;
                padding: 0.72rem 0.9rem;
                text-align: center;
                border-radius: 12px;
                min-height: 46px;
            }
        }
    </style>

    <section class="admin-wrap" aria-label="Administrator dashboard">
        <article class="admin-hero">
            <h1>Administrator Dashboard</h1>
            <p>Manage pastors, ask-pastor contact, event media content, updates, communication, ministries, church families, and registered member records.</p>
        </article>

        <article class="admin-card admin-insights" aria-label="Dashboard analytics">
            <div class="admin-card-title-row">
                <h2>Performance Snapshot</h2>
                <p class="admin-card-note">Live chart summary for members and forum activity.</p>
            </div>
            <div class="admin-insights-grid" aria-label="Top statistics">
                @foreach($dashboardMetrics as $metric)
                    <div class="admin-insight-card">
                        <p class="admin-insight-label">{{ $metric['label'] }}</p>
                        <p class="admin-insight-value">{{ $metric['value'] }}</p>
                    </div>
                @endforeach
            </div>
            <section class="admin-chart" aria-label="Member and forum chart">
                <div class="admin-chart-head">
                    <p class="admin-chart-title">Activity Chart</p>
                    <p class="admin-chart-subtitle">Scaled to highest metric ({{ $dashboardMaxMetric }})</p>
                </div>
                <ul class="admin-chart-list">
                    @foreach($dashboardMetrics as $metric)
                        @php
                            $chartWidth = (int) round((((int) $metric['value']) / $dashboardMaxMetric) * 100);
                        @endphp
                        <li class="admin-chart-row">
                            <span class="admin-chart-label">{{ $metric['label'] }}</span>
                            <span class="admin-chart-track" aria-hidden="true">
                                <span class="admin-chart-fill {{ $metric['tone'] }}" style="width: {{ $chartWidth }}%;"></span>
                            </span>
                            <span class="admin-chart-value">{{ $metric['value'] }}</span>
                        </li>
                    @endforeach
                </ul>
            </section>
        </article>

        @if(session('success'))
            <p class="admin-success">{{ session('success') }}</p>
        @endif

        @if(session('mail_summary'))
            <p class="admin-success" style="background:#edf8ef;border-color:#b8e0c0;color:#1f6b35;">
                {{ session('mail_summary') }}
            </p>
        @endif

        <form action="{{ route('admin.dashboard.save') }}" method="post" enctype="multipart/form-data">
            @csrf

            <article class="admin-card slim">
                <div class="admin-card-title-row">
                    <h2>Update Sections</h2>
                    <p class="admin-card-note">Click a button to open one section at a time.</p>
                </div>
                <div class="admin-panel-nav" role="tablist" aria-label="Admin dashboard sections">
                    <button class="admin-panel-btn is-active" type="button" data-target-panel="pastor">Ask Pastor Contact</button>
                    <button class="admin-panel-btn" type="button" data-target-panel="pastors">Pastors Since Inception</button>
                    <button class="admin-panel-btn" type="button" data-target-panel="event-media">Event Story / Videos / Gallery</button>
                    <button class="admin-panel-btn" type="button" data-target-panel="family-calendar">Friday Kitchen Rotation</button>
                    <button class="admin-panel-btn" type="button" data-target-panel="members">Registered Members</button>
                    <button class="admin-panel-btn" type="button" data-target-panel="updates">Updates by Month</button>
                    <button class="admin-panel-btn" type="button" data-target-panel="sabbaths">Upcoming Sabbaths</button>
                    <button class="admin-panel-btn" type="button" data-target-panel="daily">Daily Communication</button>
                    <button class="admin-panel-btn" type="button" data-target-panel="board-departments">Church Board Departments</button>
                    <button class="admin-panel-btn" type="button" data-target-panel="association-departments">Association Departments</button>
                    <button class="admin-panel-btn" type="button" data-target-panel="board-slider">Church Board Top Slider</button>
                    <button class="admin-panel-btn" type="button" data-target-panel="association-slider">Association Top Slider</button>
                    <button class="admin-panel-btn" type="button" data-target-panel="association-previous-executives">Association Previous Executives</button>
                    <button class="admin-panel-btn" type="button" data-target-panel="homepage-hero">Homepage Hero Slider</button>
                    <button class="admin-panel-btn" type="button" data-target-panel="families">Church Family Details</button>
                </div>
            </article>

            <article class="admin-card" data-panel="pastor">
                <div class="admin-card-title-row">
                    <h2>Ask Pastor Contact</h2>
                    <p class="admin-card-note">Controls Ask Pastor name and communication methods: call, WhatsApp, and email.</p>
                </div>
                <div class="admin-two-col">
                    <input class="admin-input" type="text" name="pastor[name]" value="{{ $pastor['name'] ?? '' }}" placeholder="Pastor name">
                    <input class="admin-input" type="text" name="pastor[photo]" value="{{ $pastor['photo'] ?? '' }}" placeholder="Photo path or URL">
                    <input class="admin-input" type="file" name="pastor[photo_file]" accept=".jpg,.jpeg,.png,.webp">
                    @if($resolvePreview($pastor['photo'] ?? '') !== null)
                        <img class="admin-inline-preview" src="{{ $resolvePreview($pastor['photo'] ?? '') }}" alt="Pastor photo preview">
                    @endif
                    <input class="admin-input" type="text" name="pastor[phone]" value="{{ $pastor['phone'] ?? '' }}" placeholder="Direct call number">
                    <input class="admin-input" type="text" name="pastor[whatsapp]" value="{{ $pastor['whatsapp'] ?? '' }}" placeholder="WhatsApp number">
                    <input class="admin-input" type="email" name="pastor[email]" value="{{ $pastor['email'] ?? '' }}" placeholder="Email address">
                </div>
            </article>

            <article class="admin-card is-hidden" data-panel="pastors">
                <div class="admin-card-title-row">
                    <h2>Pastors Since Inception</h2>
                    <p class="admin-card-note">Add, edit, and delete timeline entries shown on Our Journey page.</p>
                </div>
                <table class="admin-table" style="min-width: 1000px;">
                    <thead>
                        <tr>
                            <th style="width: 220px;">Pastor Name</th>
                            <th style="width: 150px;">Years</th>
                            <th style="width: 280px;">Photo (file or URL)</th>
                            <th style="width: 120px;">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pastorsSinceInception as $index => $row)
                            <tr>
                                <td><input class="admin-input" type="text" name="pastors_since_inception[{{ $index }}][name]" value="{{ $row['name'] ?? '' }}" placeholder="Pastor name"></td>
                                <td><input class="admin-input" type="text" name="pastors_since_inception[{{ $index }}][years]" value="{{ $row['years'] ?? '' }}" placeholder="2021-2024"></td>
                                <td>
                                    <input class="admin-input" type="text" name="pastors_since_inception[{{ $index }}][photo]" value="{{ $row['photo'] ?? '' }}" placeholder="https://... or uploads/...">
                                    <input class="admin-input" style="margin-top:0.35rem;" type="file" name="pastors_since_inception[{{ $index }}][photo_file]" accept=".jpg,.jpeg,.png,.webp">
                                </td>
                                <td data-label="Delete">
                                    <label style="display:inline-flex;align-items:center;gap:0.35rem;">
                                        <input type="checkbox" name="pastors_since_inception[{{ $index }}][_delete]" value="1">
                                        Remove
                                    </label>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </article>

            <article class="admin-card is-hidden" data-panel="event-media">
                <div class="admin-card-title-row">
                    <h2>Event Story / Videos / Gallery Content</h2>
                    <p class="admin-card-note">Pick a category and section (`story`, `videos`, `gallery`) for each row. Saved changes update the Events pages.</p>
                </div>
                <table class="admin-table" style="min-width: 1180px;">
                    <thead>
                        <tr>
                            <th style="width: 180px;">Category</th>
                            <th style="width: 120px;">Section</th>
                            <th style="width: 180px;">Title</th>
                            <th style="width: 240px;">Description</th>
                            <th style="width: 220px;">Media URL / Path</th>
                            <th style="width: 220px;">Upload Story/Video</th>
                            <th style="width: 240px;">Thumbnail (file or path)</th>
                            <th style="width: 120px;">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($eventMedia as $index => $row)
                            @php
                                $eventMediaRaw = trim((string) ($row['media_url'] ?? ''));
                                $eventMediaResolved = $resolvePreview($eventMediaRaw);
                                $eventThumbnailResolved = $resolvePreview($row['thumbnail'] ?? '');
                            @endphp
                            <tr>
                                <td>
                                    <select class="admin-input" name="event_media[{{ $index }}][category]">
                                        <option value="" {{ ($row['category'] ?? '') === '' ? 'selected' : '' }}>Select category</option>
                                        @foreach($eventCategoryOptions as $slug => $label)
                                            <option value="{{ $slug }}" {{ ($row['category'] ?? '') === $slug ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="admin-input" name="event_media[{{ $index }}][section]">
                                        @foreach(['story', 'videos', 'gallery'] as $section)
                                            <option value="{{ $section }}" {{ ($row['section'] ?? '') === $section ? 'selected' : '' }}>{{ ucfirst($section) }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input class="admin-input" type="text" name="event_media[{{ $index }}][title]" value="{{ $row['title'] ?? '' }}"></td>
                                <td><textarea class="admin-textarea" name="event_media[{{ $index }}][description]">{{ $row['description'] ?? '' }}</textarea></td>
                                <td>
                                    <input class="admin-input js-event-media-url" type="text" name="event_media[{{ $index }}][media_url]" value="{{ $row['media_url'] ?? '' }}" placeholder="uploads/... or https://..." data-preview-target="eventMediaPreview{{ $index }}">
                                </td>
                                <td>
                                    <input class="admin-input js-event-media-file" type="file" name="event_media[{{ $index }}][media_file]" accept=".jpg,.jpeg,.png,.webp,.gif,.mp4,.webm,.mov,.m4v" data-preview-target="eventMediaPreview{{ $index }}">
                                    <div class="admin-media-preview-box" id="eventMediaPreview{{ $index }}" data-initial-url="{{ $eventMediaResolved ?? '' }}" data-initial-raw="{{ $eventMediaRaw }}"></div>
                                </td>
                                <td>
                                    <input class="admin-input" type="text" name="event_media[{{ $index }}][thumbnail]" value="{{ $row['thumbnail'] ?? '' }}" placeholder="e.g. S1.jpg">
                                    <input class="admin-input js-event-thumb-file" style="margin-top:0.35rem;" type="file" name="event_media[{{ $index }}][thumbnail_file]" accept=".jpg,.jpeg,.png,.webp" data-preview-target="eventThumbPreview{{ $index }}">
                                    <div class="admin-media-preview-box" id="eventThumbPreview{{ $index }}" data-initial-url="{{ $eventThumbnailResolved ?? '' }}"></div>
                                </td>
                                <td data-label="Delete">
                                    <label style="display:inline-flex;align-items:center;gap:0.35rem;">
                                        <input type="checkbox" name="event_media[{{ $index }}][_delete]" value="1">
                                        Remove
                                    </label>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </article>

            <article class="admin-card is-hidden" data-panel="family-calendar">
                <div class="admin-card-title-row">
                    <h2>Friday Kitchen Clan Rotation</h2>
                    <p class="admin-card-note">These entries power the recurring Friday clan rotation shown on the Church Family calendar.</p>
                </div>
                <table class="admin-table" style="min-width: 1100px;">
                    <thead>
                        <tr>
                            <th style="width: 170px;">Specific Date (Optional)</th>
                            <th style="width: 140px;">Day (Optional)</th>
                            <th style="width: 220px;">Clan</th>
                            <th style="width: 420px;">Activity / Message</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($familyCalendarActivities as $index => $row)
                            <tr>
                                <td><input class="admin-input" type="date" name="family_calendar_activities[{{ $index }}][date]" value="{{ $row['date'] ?? '' }}"></td>
                                <td>
                                    <select class="admin-input" name="family_calendar_activities[{{ $index }}][day]">
                                        <option value="" {{ ($row['day'] ?? '') === '' ? 'selected' : '' }}>Select day</option>
                                        @foreach(['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                                            <option value="{{ $day }}" {{ ($row['day'] ?? '') === $day ? 'selected' : '' }}>{{ $day }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input class="admin-input" type="text" name="family_calendar_activities[{{ $index }}][area]" value="{{ $row['area'] ?? '' }}" placeholder="Jordan"></td>
                                <td><input class="admin-input" type="text" name="family_calendar_activities[{{ $index }}][activity]" value="{{ $row['activity'] ?? '' }}" placeholder="Serving in the kitchen and related activities. Please keep time and work together."></td>
                                <td data-label="Delete">
                                    <label style="display:inline-flex;align-items:center;gap:0.35rem;">
                                        <input type="checkbox" name="family_calendar_activities[{{ $index }}][_delete]" value="1">
                                        Remove
                                    </label>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </article>

            <article class="admin-card is-hidden" data-panel="members">
                <div class="admin-toolbar">
                    <div>
                        <h2 style="margin:0;">Registered Members</h2>
                        <p class="admin-count">Total records: {{ $registrations->count() }}</p>
                        <div class="admin-member-totals" aria-label="Registered category totals">
                            <span class="admin-member-total-chip">Students: <strong id="adminStudentCountText">{{ $studentRegistrationsCount }}</strong></span>
                            <span class="admin-member-total-chip">Others: <strong id="adminOtherCountText">{{ $otherRegistrationsCount }}</strong></span>
                        </div>
                    </div>
                    <a class="admin-btn small secondary" href="{{ route('admin.dashboard.members.download') }}">Download Members CSV</a>
                </div>

                <section class="admin-forum-tools" aria-label="Forum moderation controls">
                    <p class="admin-forum-tools-title">Forum Moderation</p>
                    <p class="admin-forum-tools-meta">Current forum messages: <strong id="adminForumMessageCount">{{ $forumMessagesCount }}</strong></p>
                    <div>
                        <button
                            id="adminDeleteAllForumMessagesBtn"
                            class="admin-btn small"
                            type="button"
                            data-clear-url="{{ route('admin.dashboard.forum.messages.clear') }}"
                        >
                            Delete All Forum Messages (Everyone)
                        </button>
                    </div>
                    <p id="adminForumToolsStatus" class="admin-forum-tools-status" aria-live="polite"></p>
                </section>

                <section class="admin-forum-chat-list" aria-label="All forum messages">
                    @forelse($forumMessages as $message)
                        <article class="admin-forum-chat-item">
                            <div class="admin-forum-chat-head">
                                <div>
                                    <h3 class="admin-forum-chat-name">{{ $message->name }}</h3>
                                    <p class="admin-forum-chat-meta">
                                        {{ $message->created_at ? $message->created_at->format('M d, Y h:i A') : 'Unknown time' }}
                                        @if(!empty($message->topic))
                                            | Topic: {{ $message->topic }}
                                        @endif
                                        @if(!empty($message->parent))
                                            | Reply to #{{ $message->parent->id }}
                                        @endif
                                    </p>
                                </div>
                                <button
                                    class="admin-forum-chat-delete"
                                    type="button"
                                    data-delete-url="{{ route('admin.dashboard.forum.messages.destroy', ['message' => $message->id]) }}"
                                >
                                    Delete Chat
                                </button>
                            </div>

                            <p class="admin-forum-chat-message">{{ $message->message }}</p>

                            <div class="admin-forum-chat-footer">
                                <span class="admin-forum-chat-meta">Message #{{ $message->id }}</span>
                                @if(!empty($message->attachment_path))
                                    <a class="admin-forum-chat-attachment" href="{{ route('forum.messages.attachment', ['message' => $message->id]) }}" target="_blank" rel="noopener">View attachment</a>
                                @endif
                            </div>
                        </article>
                    @empty
                        <div class="admin-forum-chat-item">
                            <p class="admin-forum-chat-message">No forum messages found.</p>
                        </div>
                    @endforelse
                </section>

                <section class="admin-members-book" aria-label="Registered members directory book view">
                    <p class="admin-members-book-meta">Open directory view of member profiles</p>
                    @if($registrations->isNotEmpty())
                        <div class="admin-members-grid">
                            @foreach($registrations as $member)
                                <details class="admin-member-accordion-item" aria-label="Member profile card">
                                    <summary class="admin-member-accordion-summary">
                                        <div class="admin-member-accordion-summary-main">
                                            <h3 class="admin-member-name">{{ $member->full_name }}</h3>
                                            <p class="admin-member-id">Member #{{ $member->id }} | {{ $member->category ?: 'Unspecified category' }}</p>
                                        </div>
                                        <span class="admin-member-accordion-caret" aria-hidden="true">▾</span>
                                    </summary>
                                    <div class="admin-member-accordion-body">
                                        <dl>
                                            <dt>Email</dt><dd>{{ $member->email }}</dd>
                                            <dt>Phone</dt><dd>{{ $member->phone }}</dd>
                                            <dt>Gender</dt><dd>{{ $member->gender }}</dd>
                                            <dt>Category</dt><dd>{{ $member->category }}</dd>
                                            <dt>Year</dt><dd>{{ $member->year_of_study }}</dd>
                                            <dt>Program</dt><dd>{{ $member->program_name }}</dd>
                                            <dt>Program Cat.</dt><dd>{{ $member->program_category }}</dd>
                                            <dt>Entry</dt><dd>{{ $member->year_of_entry }}</dd>
                                            <dt>Division</dt><dd>{{ $member->division_of_study }}</dd>
                                            <dt>Family</dt><dd>{{ $member->family }}</dd>
                                            <dt>Address</dt><dd>{{ $member->address }}</dd>
                                        </dl>
                                    </div>
                                </details>
                            @endforeach
                        </div>
                    @else
                        <p class="admin-members-empty">No registered members yet.</p>
                    @endif
                </section>
            </article>

            <article class="admin-card is-hidden" data-panel="updates">
                <h2>Updates by Month</h2>
                <table class="admin-table" style="min-width: 1120px;">
                    <thead>
                        <tr>
                            <th style="width: 150px;">Month</th>
                            <th style="width: 180px;">Date Range</th>
                            <th style="width: 180px;">Department</th>
                            <th style="width: 220px;">Title</th>
                            <th style="width: 300px;">Details</th>
                            <th style="width: 120px;">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($updates as $index => $row)
                            <tr>
                                <td><input class="admin-input" type="text" name="updates[{{ $index }}][month]" value="{{ $row['month'] ?? '' }}" placeholder="January"></td>
                                <td><input class="admin-input" type="text" name="updates[{{ $index }}][date_range]" value="{{ $row['date_range'] ?? '' }}" placeholder="1st - 7th January"></td>
                                <td><input class="admin-input" type="text" name="updates[{{ $index }}][department]" value="{{ $row['department'] ?? '' }}" placeholder="Youth Ministry"></td>
                                <td><input class="admin-input" type="text" name="updates[{{ $index }}][title]" value="{{ $row['title'] ?? '' }}" placeholder="Weekly Fellowship Update"></td>
                                <td><textarea class="admin-textarea" name="updates[{{ $index }}][details]" placeholder="Write full update details here...">{{ $row['details'] ?? '' }}</textarea></td>
                                <td data-label="Delete">
                                    <label style="display:inline-flex;align-items:center;gap:0.35rem;">
                                        <input type="checkbox" name="updates[{{ $index }}][_delete]" value="1">
                                        Remove
                                    </label>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </article>

            <article class="admin-card is-hidden" data-panel="sabbaths">
                <h2>Upcoming Sabbaths</h2>
                <table class="admin-table" style="min-width: 1020px;">
                    <thead>
                        <tr>
                            <th style="width: 430px;">Text</th>
                            <th style="width: 260px;">Image/Video Path or URL</th>
                            <th style="width: 220px;">Upload Media (image/video)</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($upcomingSabbaths as $index => $row)
                        <tr>
                            <td><input class="admin-input" type="text" name="upcoming_sabbaths[{{ $index }}][text]" value="{{ $row['text'] ?? '' }}" placeholder="Example: March 1: Women’s Day of Prayer | Resources"></td>
                            <td><input class="admin-input" type="text" name="upcoming_sabbaths[{{ $index }}][media_url]" value="{{ $row['media_url'] ?? '' }}" placeholder="uploads/... or https://..."></td>
                            <td><input class="admin-input" type="file" name="upcoming_sabbaths[{{ $index }}][media_file]" accept=".jpg,.jpeg,.png,.webp,.gif,.mp4,.webm,.mov"></td>
                            <td data-label="Delete">
                                <label style="display:inline-flex;align-items:center;gap:0.35rem;">
                                    <input type="checkbox" name="upcoming_sabbaths[{{ $index }}][_delete]" value="1">
                                    Remove
                                </label>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </article>

            <article class="admin-card is-hidden" data-panel="daily">
                <h2>Daily Communication</h2>
                <table class="admin-table" style="min-width: 1020px;">
                    <thead>
                        <tr>
                            <th style="width: 430px;">Text</th>
                            <th style="width: 260px;">Image/Video Path or URL</th>
                            <th style="width: 220px;">Upload Media (image/video)</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($dailyCommunication as $index => $row)
                        <tr>
                            <td><textarea class="admin-textarea" name="daily_communication[{{ $index }}][text]">{{ $row['text'] ?? '' }}</textarea></td>
                            <td><input class="admin-input" type="text" name="daily_communication[{{ $index }}][media_url]" value="{{ $row['media_url'] ?? '' }}" placeholder="uploads/... or https://..."></td>
                            <td><input class="admin-input" type="file" name="daily_communication[{{ $index }}][media_file]" accept=".jpg,.jpeg,.png,.webp,.gif,.mp4,.webm,.mov"></td>
                            <td data-label="Delete">
                                <label style="display:inline-flex;align-items:center;gap:0.35rem;">
                                    <input type="checkbox" name="daily_communication[{{ $index }}][_delete]" value="1">
                                    Remove
                                </label>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </article>

            <article class="admin-card is-hidden" data-panel="board-departments">
                <h2>Church Board Departments</h2>
                <table class="admin-table" style="min-width: 1520px;">
                    <thead>
                        <tr>
                            <th style="width: 160px;">Department Name</th>
                            <th style="width: 240px;">Image (file or URL)</th>
                            <th style="width: 180px;">Department Intro</th>
                            <th style="width: 180px;">Department Introduction</th>
                            <th style="width: 130px;">Department Head Name</th>
                            <th style="width: 220px;">Head Photo</th>
                            <th style="width: 140px;">Secretary Name</th>
                            <th style="width: 220px;">Secretary Photo</th>
                            <th style="width: 230px;">Explore URL</th>
                            <th style="width: 220px;">Department Information</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($boardRows as $index => $row)
                            <tr>
                                <td><input class="admin-input" type="text" name="departments[church_board][{{ $index }}][name]" value="{{ $row['name'] ?? '' }}"></td>
                                <td>
                                    <input class="admin-input" type="text" name="departments[church_board][{{ $index }}][image]" value="{{ $row['image'] ?? '' }}" placeholder="e.g. 4.jpg or https://...">
                                    <input class="admin-input" style="margin-top:0.35rem;" type="file" name="departments[church_board][{{ $index }}][image_file]" accept=".jpg,.jpeg,.png,.webp">
                                    @if($resolvePreview($row['image'] ?? '') !== null)
                                        <img class="admin-inline-preview" src="{{ $resolvePreview($row['image'] ?? '') }}" alt="Department image preview">
                                    @endif
                                </td>
                                <td><textarea class="admin-textarea" name="departments[church_board][{{ $index }}][intro]">{{ $row['intro'] ?? '' }}</textarea></td>
                                <td><textarea class="admin-textarea" name="departments[church_board][{{ $index }}][department_introduction]">{{ $row['department_introduction'] ?? '' }}</textarea></td>
                                <td><input class="admin-input" type="text" name="departments[church_board][{{ $index }}][department_head_name]" value="{{ $row['department_head_name'] ?? '' }}"></td>
                                <td>
                                    <input class="admin-input" type="text" name="departments[church_board][{{ $index }}][department_head_photo]" value="{{ $row['department_head_photo'] ?? '' }}" placeholder="uploads/... or URL">
                                    <input class="admin-input" style="margin-top:0.35rem;" type="file" name="departments[church_board][{{ $index }}][department_head_photo_file]" accept=".jpg,.jpeg,.png,.webp">
                                    @if($resolvePreview($row['department_head_photo'] ?? '') !== null)
                                        <img class="admin-inline-preview" src="{{ $resolvePreview($row['department_head_photo'] ?? '') }}" alt="Department head photo preview">
                                    @endif
                                </td>
                                <td><input class="admin-input" type="text" name="departments[church_board][{{ $index }}][secretary_name]" value="{{ $row['secretary_name'] ?? '' }}"></td>
                                <td>
                                    <input class="admin-input" type="text" name="departments[church_board][{{ $index }}][secretary_photo]" value="{{ $row['secretary_photo'] ?? '' }}" placeholder="uploads/... or URL">
                                    <input class="admin-input" style="margin-top:0.35rem;" type="file" name="departments[church_board][{{ $index }}][secretary_photo_file]" accept=".jpg,.jpeg,.png,.webp">
                                    @if($resolvePreview($row['secretary_photo'] ?? '') !== null)
                                        <img class="admin-inline-preview" src="{{ $resolvePreview($row['secretary_photo'] ?? '') }}" alt="Secretary photo preview">
                                    @endif
                                </td>
                                <td><input class="admin-input" type="text" name="departments[church_board][{{ $index }}][explore_url]" value="{{ $row['explore_url'] ?? '' }}" placeholder="https://..."></td>
                                <td><textarea class="admin-textarea" name="departments[church_board][{{ $index }}][details]">{{ $row['details'] ?? '' }}</textarea></td>
                                <td data-label="Delete">
                                    <label style="display:inline-flex;align-items:center;gap:0.35rem;">
                                        <input type="checkbox" name="departments[church_board][{{ $index }}][_delete]" value="1">
                                        Remove
                                    </label>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </article>

            <article class="admin-card is-hidden" data-panel="association-departments">
                <h2>Association Departments</h2>
                <table class="admin-table" style="min-width: 1520px;">
                    <thead>
                        <tr>
                            <th style="width: 160px;">Department Name</th>
                            <th style="width: 240px;">Image (file or URL)</th>
                            <th style="width: 180px;">Department Intro</th>
                            <th style="width: 180px;">Department Introduction</th>
                            <th style="width: 130px;">Department Head Name</th>
                            <th style="width: 220px;">Head Photo</th>
                            <th style="width: 140px;">Secretary Name</th>
                            <th style="width: 220px;">Secretary Photo</th>
                            <th style="width: 230px;">Explore URL</th>
                            <th style="width: 220px;">Department Information</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($associationRows as $index => $row)
                            <tr>
                                <td><input class="admin-input" type="text" name="departments[association][{{ $index }}][name]" value="{{ $row['name'] ?? '' }}"></td>
                                <td>
                                    <input class="admin-input" type="text" name="departments[association][{{ $index }}][image]" value="{{ $row['image'] ?? '' }}" placeholder="e.g. S1.jpg or https://...">
                                    <input class="admin-input" style="margin-top:0.35rem;" type="file" name="departments[association][{{ $index }}][image_file]" accept=".jpg,.jpeg,.png,.webp">
                                    @if($resolvePreview($row['image'] ?? '') !== null)
                                        <img class="admin-inline-preview" src="{{ $resolvePreview($row['image'] ?? '') }}" alt="Association department image preview">
                                    @endif
                                </td>
                                <td><textarea class="admin-textarea" name="departments[association][{{ $index }}][intro]">{{ $row['intro'] ?? '' }}</textarea></td>
                                <td><textarea class="admin-textarea" name="departments[association][{{ $index }}][department_introduction]">{{ $row['department_introduction'] ?? '' }}</textarea></td>
                                <td><input class="admin-input" type="text" name="departments[association][{{ $index }}][department_head_name]" value="{{ $row['department_head_name'] ?? '' }}"></td>
                                <td>
                                    <input class="admin-input" type="text" name="departments[association][{{ $index }}][department_head_photo]" value="{{ $row['department_head_photo'] ?? '' }}" placeholder="uploads/... or URL">
                                    <input class="admin-input" style="margin-top:0.35rem;" type="file" name="departments[association][{{ $index }}][department_head_photo_file]" accept=".jpg,.jpeg,.png,.webp">
                                    @if($resolvePreview($row['department_head_photo'] ?? '') !== null)
                                        <img class="admin-inline-preview" src="{{ $resolvePreview($row['department_head_photo'] ?? '') }}" alt="Association head photo preview">
                                    @endif
                                </td>
                                <td><input class="admin-input" type="text" name="departments[association][{{ $index }}][secretary_name]" value="{{ $row['secretary_name'] ?? '' }}"></td>
                                <td>
                                    <input class="admin-input" type="text" name="departments[association][{{ $index }}][secretary_photo]" value="{{ $row['secretary_photo'] ?? '' }}" placeholder="uploads/... or URL">
                                    <input class="admin-input" style="margin-top:0.35rem;" type="file" name="departments[association][{{ $index }}][secretary_photo_file]" accept=".jpg,.jpeg,.png,.webp">
                                    @if($resolvePreview($row['secretary_photo'] ?? '') !== null)
                                        <img class="admin-inline-preview" src="{{ $resolvePreview($row['secretary_photo'] ?? '') }}" alt="Association secretary photo preview">
                                    @endif
                                </td>
                                <td><input class="admin-input" type="text" name="departments[association][{{ $index }}][explore_url]" value="{{ $row['explore_url'] ?? '' }}" placeholder="https://..."></td>
                                <td><textarea class="admin-textarea" name="departments[association][{{ $index }}][details]">{{ $row['details'] ?? '' }}</textarea></td>
                                <td data-label="Delete">
                                    <label style="display:inline-flex;align-items:center;gap:0.35rem;">
                                        <input type="checkbox" name="departments[association][{{ $index }}][_delete]" value="1">
                                        Remove
                                    </label>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </article>

            <article class="admin-card is-hidden" data-panel="board-slider">
                <h2>Church Board Top Slider (Pastor / Chief Elder / Church Clerk)</h2>
                <table class="admin-table" style="min-width: 1200px;">
                    <thead>
                        <tr>
                            <th style="width: 180px;">Role</th>
                            <th style="width: 200px;">Name</th>
                            <th style="width: 280px;">Message</th>
                            <th style="width: 300px;">Image (file or path)</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($boardLeaderRows as $index => $row)
                            <tr>
                                <td><input class="admin-input" type="text" name="departments[church_board_leaders][{{ $index }}][role]" value="{{ $row['role'] ?? '' }}" placeholder="Pastor"></td>
                                <td><input class="admin-input" type="text" name="departments[church_board_leaders][{{ $index }}][name]" value="{{ $row['name'] ?? '' }}"></td>
                                <td><textarea class="admin-textarea" name="departments[church_board_leaders][{{ $index }}][message]">{{ $row['message'] ?? '' }}</textarea></td>
                                <td>
                                    <input class="admin-input" type="text" name="departments[church_board_leaders][{{ $index }}][image]" value="{{ $row['image'] ?? '' }}" placeholder="uploads/... or URL">
                                    <input class="admin-input" style="margin-top:0.35rem;" type="file" name="departments[church_board_leaders][{{ $index }}][image_file]" accept=".jpg,.jpeg,.png,.webp">
                                    @if($resolvePreview($row['image'] ?? '') !== null)
                                        <img class="admin-inline-preview" src="{{ $resolvePreview($row['image'] ?? '') }}" alt="Church board leader image preview">
                                    @endif
                                </td>
                                <td data-label="Delete">
                                    <label style="display:inline-flex;align-items:center;gap:0.35rem;">
                                        <input type="checkbox" name="departments[church_board_leaders][{{ $index }}][_delete]" value="1">
                                        Remove
                                    </label>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </article>

            <article class="admin-card is-hidden" data-panel="association-slider">
                <h2>Association Top Slider (President / Vice President / Secretary)</h2>
                <table class="admin-table" style="min-width: 1200px;">
                    <thead>
                        <tr>
                            <th style="width: 180px;">Role</th>
                            <th style="width: 200px;">Name</th>
                            <th style="width: 280px;">Message</th>
                            <th style="width: 300px;">Image (file or path)</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($associationLeaderRows as $index => $row)
                            <tr>
                                <td><input class="admin-input" type="text" name="departments[association_leaders][{{ $index }}][role]" value="{{ $row['role'] ?? '' }}" placeholder="President"></td>
                                <td><input class="admin-input" type="text" name="departments[association_leaders][{{ $index }}][name]" value="{{ $row['name'] ?? '' }}"></td>
                                <td><textarea class="admin-textarea" name="departments[association_leaders][{{ $index }}][message]">{{ $row['message'] ?? '' }}</textarea></td>
                                <td>
                                    <input class="admin-input" type="text" name="departments[association_leaders][{{ $index }}][image]" value="{{ $row['image'] ?? '' }}" placeholder="uploads/... or URL">
                                    <input class="admin-input" style="margin-top:0.35rem;" type="file" name="departments[association_leaders][{{ $index }}][image_file]" accept=".jpg,.jpeg,.png,.webp">
                                    @if($resolvePreview($row['image'] ?? '') !== null)
                                        <img class="admin-inline-preview" src="{{ $resolvePreview($row['image'] ?? '') }}" alt="Association leader image preview">
                                    @endif
                                </td>
                                <td data-label="Delete">
                                    <label style="display:inline-flex;align-items:center;gap:0.35rem;">
                                        <input type="checkbox" name="departments[association_leaders][{{ $index }}][_delete]" value="1">
                                        Remove
                                    </label>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </article>

            <article class="admin-card is-hidden" data-panel="association-previous-executives">
                <h2>Association Previous Executives</h2>
                <p class="admin-card-note">Manage period cards and executive members shown on the Previous Executives page. First row should include President, Vice President, and Secretary roles where possible.</p>

                @foreach($associationPreviousExecutives as $periodIndex => $period)
                    @php $periodPhotoResolved = $resolvePreview($period['photo'] ?? ''); @endphp
                    <section style="margin-bottom: 1rem; border: 1px solid #d6e1f2; border-radius: 12px; padding: 0.7rem; background: #f8fbff;">
                        <div class="admin-card-title-row" style="margin-bottom:0.55rem;">
                            <h3 style="margin:0; color:#173f70;">Period {{ $periodIndex + 1 }}</h3>
                            <label style="display:inline-flex;align-items:center;gap:0.35rem;">
                                <input type="checkbox" name="association_previous_executives[{{ $periodIndex }}][_delete]" value="1">
                                Remove period
                            </label>
                        </div>

                        <div class="admin-two-col" style="margin-bottom:0.7rem;">
                            <input class="admin-input" type="text" name="association_previous_executives[{{ $periodIndex }}][years]" value="{{ $period['years'] ?? '' }}" placeholder="e.g. 2001-2005">
                            <input class="admin-input" type="text" name="association_previous_executives[{{ $periodIndex }}][name]" value="{{ $period['name'] ?? '' }}" placeholder="President name (card title)">
                            <input class="admin-input" type="text" name="association_previous_executives[{{ $periodIndex }}][photo]" value="{{ $period['photo'] ?? '' }}" placeholder="Period image path or URL">
                            <input class="admin-input" type="file" name="association_previous_executives[{{ $periodIndex }}][photo_file]" accept=".jpg,.jpeg,.png,.webp">
                            @if($periodPhotoResolved !== null)
                                <img class="admin-inline-preview" src="{{ $periodPhotoResolved }}" alt="Period image preview">
                            @endif
                        </div>

                        <table class="admin-table" style="min-width: 980px;">
                            <thead>
                                <tr>
                                    <th style="width: 180px;">Role</th>
                                    <th style="width: 220px;">Name</th>
                                    <th style="width: 320px;">Photo (path or upload)</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(($period['executives'] ?? []) as $memberIndex => $member)
                                    @php $memberPhotoResolved = $resolvePreview($member['photo'] ?? ''); @endphp
                                    <tr>
                                        <td><input class="admin-input" type="text" name="association_previous_executives[{{ $periodIndex }}][executives][{{ $memberIndex }}][role]" value="{{ $member['role'] ?? '' }}" placeholder="President"></td>
                                        <td><input class="admin-input" type="text" name="association_previous_executives[{{ $periodIndex }}][executives][{{ $memberIndex }}][name]" value="{{ $member['name'] ?? '' }}" placeholder="Executive member name"></td>
                                        <td>
                                            <input class="admin-input" type="text" name="association_previous_executives[{{ $periodIndex }}][executives][{{ $memberIndex }}][photo]" value="{{ $member['photo'] ?? '' }}" placeholder="uploads/... or https://...">
                                            <input class="admin-input" style="margin-top:0.35rem;" type="file" name="association_previous_executives[{{ $periodIndex }}][executives][{{ $memberIndex }}][photo_file]" accept=".jpg,.jpeg,.png,.webp">
                                            @if($memberPhotoResolved !== null)
                                                <img class="admin-inline-preview" src="{{ $memberPhotoResolved }}" alt="Executive member photo preview">
                                            @endif
                                        </td>
                                        <td data-label="Delete">
                                            <label style="display:inline-flex;align-items:center;gap:0.35rem;">
                                                <input type="checkbox" name="association_previous_executives[{{ $periodIndex }}][executives][{{ $memberIndex }}][_delete]" value="1">
                                                Remove
                                            </label>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </section>
                @endforeach
            </article>

            <article class="admin-card is-hidden" data-panel="homepage-hero">
                <h2>Homepage Hero Slider</h2>
                <p class="admin-card-note">Manage the main site hero slides shown on the homepage. Upload images or provide a public path. You can also set link and text color.</p>
                <table class="admin-table" style="min-width: 980px;">
                    <thead>
                        <tr>
                            <th style="width: 240px;">Title</th>
                            <th style="width: 360px;">Subtitle / Caption</th>
                            <th style="width: 260px;">Image (path or upload)</th>
                            <th style="width: 200px;">Link (optional)</th>
                            <th style="width: 120px;">Text Color</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($heroSlides as $index => $row)
                            @php $heroImageResolved = $resolvePreview($row['image'] ?? ''); @endphp
                            <tr>
                                <td><input class="admin-input" type="text" name="hero_slides[{{ $index }}][title]" value="{{ $row['title'] ?? '' }}" placeholder="Slide title"></td>
                                <td><textarea class="admin-textarea" name="hero_slides[{{ $index }}][subtitle]">{{ $row['subtitle'] ?? '' }}</textarea></td>
                                <td>
                                    <input class="admin-input" type="text" name="hero_slides[{{ $index }}][image]" value="{{ $row['image'] ?? '' }}" placeholder="uploads/... or https://...">
                                    <input class="admin-input" style="margin-top:0.35rem;" type="file" name="hero_slides[{{ $index }}][image_file]" accept=".jpg,.jpeg,.png,.webp">
                                    @if($heroImageResolved !== null)
                                        <img class="admin-inline-preview" src="{{ $heroImageResolved }}" alt="Slide preview">
                                    @endif
                                </td>
                                <td><input class="admin-input" type="text" name="hero_slides[{{ $index }}][link]" value="{{ $row['link'] ?? '' }}" placeholder="https://..."></td>
                                <td><input class="admin-input" type="color" name="hero_slides[{{ $index }}][text_color]" value="{{ preg_match('/^#[0-9a-fA-F]{6}$/', (string) ($row['text_color'] ?? '')) ? $row['text_color'] : '#ffffff' }}" aria-label="Hero slide text color"></td>
                                <td data-label="Delete">
                                    <label style="display:inline-flex;align-items:center;gap:0.35rem;">
                                        <input type="checkbox" name="hero_slides[{{ $index }}][_delete]" value="1">
                                        Remove
                                    </label>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </article>

            <article class="admin-card is-hidden" data-panel="families">
                <h2>Church Family Details</h2>
                <table class="admin-table" style="min-width: 1640px;">
                    <thead>
                        <tr>
                            <th style="width: 150px;">Family Name</th>
                            <th style="width: 210px;">Family Head Photo</th>
                            <th style="width: 200px;">Family Intro</th>
                            <th style="width: 140px;">Family Head</th>
                            <th style="width: 150px;">Family Secretary</th>
                            <th style="width: 150px;">Spiritual Leader</th>
                            <th style="width: 150px;">Financial Mobiliser</th>
                            <th style="width: 170px;">Social Wellbeing Leader</th>
                            <th style="width: 210px;">Explore URL</th>
                            <th style="width: 130px;">Family Contact</th>
                            <th style="width: 180px;">Family Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($familyRows as $index => $row)
                            <tr>
                                <td><input class="admin-input" type="text" name="departments[church_families][{{ $index }}][name]" value="{{ $row['name'] ?? '' }}"></td>
                                <td>
                                    <input class="admin-input" type="text" name="departments[church_families][{{ $index }}][image]" value="{{ $row['image'] ?? '' }}" placeholder="e.g. 6.png or https://...">
                                    <input class="admin-input" style="margin-top:0.35rem;" type="file" name="departments[church_families][{{ $index }}][image_file]" accept=".jpg,.jpeg,.png,.webp">
                                </td>
                                <td><textarea class="admin-textarea" name="departments[church_families][{{ $index }}][intro]">{{ $row['intro'] ?? '' }}</textarea></td>
                                <td><input class="admin-input" type="text" name="departments[church_families][{{ $index }}][family_head_name]" value="{{ $row['family_head_name'] ?? '' }}"></td>
                                <td><input class="admin-input" type="text" name="departments[church_families][{{ $index }}][family_secretary_name]" value="{{ $row['family_secretary_name'] ?? '' }}"></td>
                                <td><input class="admin-input" type="text" name="departments[church_families][{{ $index }}][family_spiritual_leader]" value="{{ $row['family_spiritual_leader'] ?? '' }}"></td>
                                <td><input class="admin-input" type="text" name="departments[church_families][{{ $index }}][family_financial_mobiliser]" value="{{ $row['family_financial_mobiliser'] ?? '' }}"></td>
                                <td><input class="admin-input" type="text" name="departments[church_families][{{ $index }}][family_social_wellbeing_leader]" value="{{ $row['family_social_wellbeing_leader'] ?? '' }}"></td>
                                <td><input class="admin-input" type="text" name="departments[church_families][{{ $index }}][explore_url]" value="{{ $row['explore_url'] ?? '' }}" placeholder="https://..."></td>
                                <td><input class="admin-input" type="text" name="departments[church_families][{{ $index }}][family_contact]" value="{{ $row['family_contact'] ?? ($row['pastor_phone'] ?? '') }}"></td>
                                <td><input class="admin-input" type="email" name="departments[church_families][{{ $index }}][family_email]" value="{{ $row['family_email'] ?? ($row['pastor_email'] ?? '') }}"></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </article>

            <div class="admin-actions">
                <a class="admin-btn secondary" href="{{ route('home') }}">Back to Site</a>
                <a class="admin-btn secondary" href="{{ route('admin.dashboard.logout') }}">Close Admin Session</a>
                <button class="admin-btn" type="submit">Save Dashboard Content</button>
            </div>
        </form>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.8.0/build/js/intlTelInput.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.8.0/build/js/utils.js"></script>
    <script>
        (function () {
            const csrfToken = @json(csrf_token());
            const tables = Array.from(document.querySelectorAll('.admin-table'));
            const panelButtons = Array.from(document.querySelectorAll('.admin-panel-btn[data-target-panel]'));
            const panels = Array.from(document.querySelectorAll('.admin-card[data-panel]'));
            const adminForm = document.querySelector('form[action*="admin/dashboard"]');
            const activePanelStorageKey = 'mubs_admin_active_panel_v1';
            const adminDeleteAllForumMessagesBtn = document.getElementById('adminDeleteAllForumMessagesBtn');
            const adminForumToolsStatus = document.getElementById('adminForumToolsStatus');
            const adminForumMessageCount = document.getElementById('adminForumMessageCount');
            const adminForumDeleteButtons = Array.from(document.querySelectorAll('.admin-forum-chat-delete[data-delete-url]'));
            const phoneInputs = Array.from(document.querySelectorAll(
                'input[name="pastor[phone]"], input[name="pastor[whatsapp]"], input[name$="[family_contact]"]'
            ));
            const phoneIntlInstances = [];

            if (window.intlTelInput) {
                phoneInputs.forEach(function (input) {
                    const intlInstance = window.intlTelInput(input, {
                        initialCountry: 'ug',
                        separateDialCode: true,
                        nationalMode: false,
                        autoPlaceholder: 'aggressive',
                        formatOnDisplay: true,
                        strictMode: true,
                        dropdownContainer: document.body,
                        utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@23.8.0/build/js/utils.js',
                    });

                    if (input.value.trim() !== '') {
                        intlInstance.setNumber(input.value.trim());
                    }

                    phoneIntlInstances.push({
                        input: input,
                        instance: intlInstance,
                    });
                });

                if (adminForm) {
                    adminForm.addEventListener('submit', function () {
                        phoneIntlInstances.forEach(function (entry) {
                            const internationalNumber = entry.instance.getNumber();
                            if (internationalNumber) {
                                entry.input.value = internationalNumber.replace(/\s+/g, '');
                            }
                        });
                    });
                }
            }

            function showPanel(panelKey) {
                if (!panelKey) {
                    return;
                }

                panels.forEach(function (panel) {
                    const isTarget = panel.getAttribute('data-panel') === panelKey;
                    panel.classList.toggle('is-hidden', !isTarget);
                });

                panelButtons.forEach(function (button) {
                    const isActive = button.getAttribute('data-target-panel') === panelKey;
                    button.classList.toggle('is-active', isActive);
                    button.setAttribute('aria-selected', isActive ? 'true' : 'false');
                });

                try {
                    localStorage.setItem(activePanelStorageKey, panelKey);
                } catch (error) {
                }
            }

            panelButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    const panelKey = button.getAttribute('data-target-panel');
                    showPanel(panelKey);
                });
            });

            let savedPanel = '';

            try {
                savedPanel = localStorage.getItem(activePanelStorageKey) || '';
            } catch (error) {
                savedPanel = '';
            }

            const hasSavedPanel = savedPanel !== '' && panels.some(function (panel) {
                return panel.getAttribute('data-panel') === savedPanel;
            });

            showPanel(hasSavedPanel ? savedPanel : 'pastor');

            tables.forEach(function (table) {
                const headers = Array.from(table.querySelectorAll('thead th')).map(function (th) {
                    return (th.textContent || '').trim() || 'Field';
                });

                const rows = Array.from(table.querySelectorAll('tbody tr'));
                rows.forEach(function (row) {
                    const cells = Array.from(row.querySelectorAll('td'));
                    cells.forEach(function (cell, index) {
                        if (cell.hasAttribute('colspan')) {
                            return;
                        }

                        const label = headers[index] || 'Field';
                        cell.setAttribute('data-label', label);
                    });
                });
            });

            const imagePattern = /\.(jpg|jpeg|png|webp|gif)(\?.*)?$/i;
            const videoPattern = /\.(mp4|webm|mov|m4v)(\?.*)?$/i;

            function renderPreview(target, sourceUrl, rawUrl) {
                if (!target) {
                    return;
                }

                const resolved = (sourceUrl || '').trim();
                const raw = (rawUrl || '').trim();
                target.innerHTML = '';

                if (resolved === '' && raw === '') {
                    target.textContent = 'No preview yet';
                    return;
                }

                const candidate = resolved !== '' ? resolved : raw;

                if (imagePattern.test(candidate)) {
                    const img = document.createElement('img');
                    img.src = candidate;
                    img.alt = 'Media preview';
                    target.appendChild(img);
                    return;
                }

                if (videoPattern.test(candidate)) {
                    const video = document.createElement('video');
                    video.src = candidate;
                    video.controls = true;
                    video.preload = 'metadata';
                    target.appendChild(video);
                    return;
                }

                const link = document.createElement('a');
                link.href = candidate;
                link.target = '_blank';
                link.rel = 'noopener';
                link.textContent = 'Open media URL';
                target.appendChild(link);
            }

            function readFilePreview(fileInput, target) {
                const file = fileInput && fileInput.files ? fileInput.files[0] : null;
                if (!file) {
                    return false;
                }

                const objectUrl = URL.createObjectURL(file);
                renderPreview(target, objectUrl, '');
                return true;
            }

            const previewBoxes = Array.from(document.querySelectorAll('.admin-media-preview-box'));
            previewBoxes.forEach(function (box) {
                const initialUrl = box.getAttribute('data-initial-url') || '';
                const initialRaw = box.getAttribute('data-initial-raw') || '';
                renderPreview(box, initialUrl, initialRaw);
            });

            const mediaUrlInputs = Array.from(document.querySelectorAll('.js-event-media-url'));
            mediaUrlInputs.forEach(function (input) {
                input.addEventListener('input', function () {
                    const targetId = input.getAttribute('data-preview-target');
                    const target = targetId ? document.getElementById(targetId) : null;
                    renderPreview(target, input.value, input.value);
                });
            });

            const mediaFileInputs = Array.from(document.querySelectorAll('.js-event-media-file'));
            mediaFileInputs.forEach(function (input) {
                input.addEventListener('change', function () {
                    const targetId = input.getAttribute('data-preview-target');
                    const target = targetId ? document.getElementById(targetId) : null;
                    if (!readFilePreview(input, target)) {
                        const urlInput = input.closest('tr') ? input.closest('tr').querySelector('.js-event-media-url') : null;
                        renderPreview(target, urlInput ? urlInput.value : '', urlInput ? urlInput.value : '');
                    }
                });
            });

            const thumbFileInputs = Array.from(document.querySelectorAll('.js-event-thumb-file'));
            thumbFileInputs.forEach(function (input) {
                input.addEventListener('change', function () {
                    const targetId = input.getAttribute('data-preview-target');
                    const target = targetId ? document.getElementById(targetId) : null;
                    if (!readFilePreview(input, target)) {
                        renderPreview(target, target ? (target.getAttribute('data-initial-url') || '') : '', '');
                    }
                });
            });

            if (adminDeleteAllForumMessagesBtn) {
                adminDeleteAllForumMessagesBtn.addEventListener('click', async function () {
                    const clearUrl = adminDeleteAllForumMessagesBtn.getAttribute('data-clear-url') || '';
                    if (!clearUrl) {
                        return;
                    }

                    const shouldProceed = await CustomModal.show({
                        title: 'Delete All Forum Messages',
                        message: 'This will delete all forum messages for everyone. This action cannot be undone.',
                        confirmText: 'Delete All',
                        cancelText: 'Cancel',
                        isDangerous: true
                    });
                    
                    if (!shouldProceed) {
                        return;
                    }

                    adminDeleteAllForumMessagesBtn.disabled = true;
                    if (adminForumToolsStatus) {
                        adminForumToolsStatus.textContent = 'Deleting forum messages...';
                    }

                    fetch(clearUrl, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                    }).then(function (response) {
                        if (!response.ok) {
                            throw new Error('Delete failed');
                        }

                        return response.json();
                    }).then(function (payload) {
                        const deletedMessages = Number(payload && payload.deleted_messages ? payload.deleted_messages : 0);
                        const safeDeletedMessages = Number.isFinite(deletedMessages) && deletedMessages >= 0 ? Math.floor(deletedMessages) : 0;

                        if (adminForumMessageCount) {
                            adminForumMessageCount.textContent = '0';
                        }

                        if (adminForumToolsStatus) {
                            adminForumToolsStatus.textContent = 'Deleted ' + safeDeletedMessages + ' forum messages for everyone.';
                        }
                    }).catch(function () {
                        if (adminForumToolsStatus) {
                            adminForumToolsStatus.textContent = 'Unable to delete forum messages right now. Please try again.';
                        }
                    }).finally(function () {
                        adminDeleteAllForumMessagesBtn.disabled = false;
                    });
                });
            }

            adminForumDeleteButtons.forEach(function (button) {
                button.addEventListener('click', async function () {
                    const deleteUrl = button.getAttribute('data-delete-url') || '';
                    if (!deleteUrl) {
                        return;
                    }

                    const shouldProceed = await CustomModal.show({
                        title: 'Delete Forum Message',
                        message: 'Delete this forum message? This action cannot be undone.',
                        confirmText: 'Delete',
                        cancelText: 'Cancel',
                        isDangerous: true
                    });
                    
                    if (!shouldProceed) {
                        return;
                    }

                    button.disabled = true;

                    fetch(deleteUrl, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                    }).then(function (response) {
                        if (!response.ok) {
                            throw new Error('Delete failed');
                        }

                        return response.json();
                    }).then(function () {
                        const chatItem = button.closest('.admin-forum-chat-item');
                        if (chatItem) {
                            chatItem.remove();
                        }

                        if (adminForumMessageCount) {
                            const currentCount = Number(adminForumMessageCount.textContent || '0');
                            const safeCurrentCount = Number.isFinite(currentCount) ? currentCount : 0;
                            adminForumMessageCount.textContent = String(Math.max(0, safeCurrentCount - 1));
                        }

                        if (adminForumToolsStatus) {
                            adminForumToolsStatus.textContent = 'Forum message deleted.';
                        }
                    }).catch(function () {
                        if (adminForumToolsStatus) {
                            adminForumToolsStatus.textContent = 'Unable to delete this forum message right now. Please try again.';
                        }
                        button.disabled = false;
                    });
                });
            });
        })();
    </script>
@endsection

