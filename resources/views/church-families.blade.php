@extends('layouts.site')

@section('title', 'SDA CHURCH MUBS | Church Families')

@section('content')
    @php
        $adminContent = \App\Support\AdminContentStore::get();
        $adminFamilies = $adminContent['departments']['church_families'] ?? [];
        $pastorContact = trim((string) ($adminContent['pastor']['phone'] ?? ''));
        $pastorEmail = trim((string) ($adminContent['pastor']['email'] ?? ''));
        $defaultFamilies = config('church.families', []);

        $normalizeFromConfig = function (array $family): array {
            $members = $family['members'] ?? [];

            return [
                'name' => $family['name'] ?? '',
                'image' => $family['photo'] ?? '',
                'intro' => 'Family committee structure',
                'family_head_name' => $members['Family Head'] ?? '',
                'family_secretary_name' => $members['Family Secretary'] ?? '',
                'family_spiritual_leader' => $members['Family Spiritual Leader'] ?? '',
                'family_financial_mobiliser' => $members['Family Financial Mobiliser'] ?? '',
                'family_social_wellbeing_leader' => $members['Family Social Well Being Leader'] ?? '',
                'family_contact' => '',
                'family_email' => '',
            ];
        };

        $familiesByName = [];

        foreach ($defaultFamilies as $family) {
            $normalized = $normalizeFromConfig((array) $family);
            $nameKey = strtolower(trim((string) ($normalized['name'] ?? '')));
            if ($nameKey !== '') {
                $familiesByName[$nameKey] = $normalized;
            }
        }

        foreach ((array) $adminFamilies as $family) {
            $nameKey = strtolower(trim((string) ($family['name'] ?? '')));
            if ($nameKey !== '') {
                $familiesByName[$nameKey] = array_merge($familiesByName[$nameKey] ?? [], (array) $family);
            }
        }

        $preferredOrder = ['Jordan', 'Jericho', 'Jerusalem', 'Hebron'];
        $families = [];

        foreach ($preferredOrder as $name) {
            $key = strtolower($name);
            if (isset($familiesByName[$key])) {
                $families[] = $familiesByName[$key];
                unset($familiesByName[$key]);
            }
        }

        foreach ($familiesByName as $family) {
            $families[] = $family;
        }

        $fallbackPhoto = asset('department-head-placeholder.svg');
        $familyHeaderColors = ['#1f4a8a', '#3e8391', '#c19434', '#4f6f31', '#6b4f8f', '#a5562a'];

        $familyCalendarActivities = collect($adminContent['family_calendar_activities'] ?? [])->map(function ($row) {
            return [
                'date' => trim((string) ($row['date'] ?? '')),
                'day' => trim((string) ($row['day'] ?? '')),
                'area' => trim((string) ($row['area'] ?? '')),
                'activity' => trim((string) ($row['activity'] ?? '')),
                'time' => trim((string) ($row['time'] ?? '')),
            ];
        })->filter(function ($row) {
            return $row['date'] !== '' || $row['day'] !== '' || $row['area'] !== '' || $row['activity'] !== '' || $row['time'] !== '';
        })->values()->all();

        if (empty($familyCalendarActivities)) {
            $familyCalendarActivities = [
                ['date' => '', 'day' => 'Friday', 'area' => 'Jordan', 'activity' => 'Serving in the kitchen and related activities. Please keep time and work together.', 'time' => ''],
                ['date' => '', 'day' => 'Friday', 'area' => 'Jericho', 'activity' => 'Serving in the kitchen and related activities. Please keep time and work together.', 'time' => ''],
                ['date' => '', 'day' => 'Friday', 'area' => 'Jerusalem', 'activity' => 'Serving in the kitchen and related activities. Please keep time and work together.', 'time' => ''],
                ['date' => '', 'day' => 'Friday', 'area' => 'Hebron', 'activity' => 'Serving in the kitchen and related activities. Please keep time and work together.', 'time' => ''],
            ];
        }

        $familyCalendarActivitiesJson = json_encode($familyCalendarActivities, JSON_UNESCAPED_UNICODE);
    @endphp

    <style>
        .family-hero {
            margin-bottom: 1rem;
            border: 0;
            background: transparent;
            padding: 0;
        }

        .family-hero h1 {
            margin: 0;
            color: #00163a;
            font-size: clamp(1.8rem, 4vw, 2.5rem);
            line-height: 1.1;
            text-transform: uppercase;
        }

        .family-hero .lead {
            margin: 0.7rem 0 0;
            color: var(--text-muted);
            line-height: 1.55;
            max-width: 980px;
        }

        .family-layout {
            display: grid;
            grid-template-columns: minmax(0, 2fr) minmax(300px, 1fr);
            gap: 0.9rem;
            margin-top: 1rem;
        }

        .family-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.9rem;
        }

        @media (max-width: 1060px) {
            .family-layout {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 900px) {
            .family-grid {
                grid-template-columns: 1fr;
            }
        }

        .family-card {
            background: #ffffff;
            border: 1px solid #d9e0ec;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 10px 22px rgba(14, 37, 74, 0.08);
            display: flex;
            flex-direction: column;
        }

        .family-card-head {
            background: var(--family-band, #1f4a8a);
            color: #ffffff;
            padding: 0.75rem 0.9rem;
        }

        .family-card-head h2 {
            margin: 0;
            color: #ffffff;
            font-size: clamp(1.1rem, 2.4vw, 1.55rem);
            text-transform: uppercase;
            letter-spacing: 0.03em;
            line-height: 1.2;
        }

        .family-photo-wrap {
            display: flex;
            justify-content: center;
            padding: 1rem 0.9rem 0.2rem;
        }

        .family-photo {
            width: 220px;
            height: 220px;
            border-radius: 50%;
            object-fit: cover;
            object-position: center;
            border: 3px solid #d9e0ec;
            background: #edf2fb;
            display: block;
        }

        .family-name-row {
            margin: 0.2rem 0.9rem 0.35rem;
            background: var(--family-band, #1f4a8a);
            color: #ffffff;
            border-radius: 8px;
            padding: 0.5rem 0.68rem;
            text-transform: uppercase;
            font-weight: 800;
            font-size: 1.1rem;
            letter-spacing: 0.03em;
            line-height: 1.1;
        }

        .family-body {
            padding: 0.9rem;
            display: grid;
            gap: 0.7rem;
        }

        .family-sub {
            margin: 0;
            color: var(--text-soft);
            font-size: 0.9rem;
            font-weight: 600;
        }

        .family-structure {
            margin: 0;
            padding-left: 1.1rem;
        }

        .family-structure li {
            margin-bottom: 0.35rem;
            color: #2e3c54;
        }

        .family-role {
            color: #1f4a8a;
            font-weight: 700;
        }

        .family-calendar-side {
            border: 1px solid #d9e0ec;
            border-radius: 14px;
            background: #ffffff;
            box-shadow: 0 10px 22px rgba(14, 37, 74, 0.08);
            overflow: hidden;
            height: fit-content;
            position: sticky;
            top: 1rem;
        }

        .family-calendar-head {
            margin: 0;
            padding: 0.8rem 0.9rem;
            font-size: 1rem;
            line-height: 1.35;
            color: #ffffff;
            background: linear-gradient(135deg, #1f4a8a, #3e8391);
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .family-calendar-controls {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            padding: 0.75rem 0.9rem 0.55rem;
            border-bottom: 1px solid #e1e8f3;
        }

        .family-calendar-month {
            margin: 0;
            color: #00163a;
            font-size: 1rem;
            font-weight: 800;
        }

        .family-calendar-btn {
            border: 1px solid #bfcde5;
            background: #f6f9ff;
            color: #1f4a8a;
            border-radius: 8px;
            padding: 0.35rem 0.55rem;
            cursor: pointer;
            font-weight: 700;
        }

        .family-calendar-btn:hover {
            background: #eaf1ff;
        }

        .family-calendar-weekdays,
        .family-calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, minmax(0, 1fr));
            gap: 0.25rem;
            padding: 0 0.75rem;
        }

        .family-calendar-weekdays {
            padding-top: 0.7rem;
        }

        .family-calendar-weekdays span {
            text-align: center;
            font-size: 0.73rem;
            color: #6b7991;
            text-transform: uppercase;
            font-weight: 700;
        }

        .family-calendar-grid {
            padding-top: 0.45rem;
            padding-bottom: 0.75rem;
        }

        .family-calendar-day {
            border: 1px solid #e1e8f3;
            background: #ffffff;
            border-radius: 8px;
            min-height: 54px;
            padding: 0.26rem;
            font-size: 0.82rem;
            color: var(--text-muted);
            display: grid;
            align-content: start;
            justify-items: center;
            cursor: pointer;
        }

        .family-calendar-day.outside {
            color: #a1aec2;
            background: #f9fbff;
        }

        .family-calendar-day.has-event {
            border-color: #96b0dc;
            background: #eef4ff;
        }

        .family-calendar-day.today {
            box-shadow: inset 0 0 0 2px #3e8391;
        }

        .family-calendar-day.active {
            border-color: #1f4a8a;
            background: #1f4a8a;
            color: #ffffff;
        }

        .family-calendar-day small {
            margin-top: 0.16rem;
            font-size: 0.62rem;
            line-height: 1.2;
            text-align: center;
            letter-spacing: 0.02em;
        }

        .family-calendar-day.active small {
            color: #dce8ff;
        }

        .family-calendar-detail {
            border-top: 1px solid #e1e8f3;
            padding: 0.75rem 0.9rem 0.95rem;
            background: #fbfdff;
        }

        .family-calendar-detail h3 {
            margin: 0;
            color: #0d2447;
            font-size: 0.94rem;
            text-transform: uppercase;
        }

        .family-calendar-detail p {
            margin: 0.4rem 0 0;
            color: var(--text-muted);
            line-height: 1.45;
            font-size: 0.88rem;
        }

        .family-calendar-note {
            margin: 0.55rem 0 0;
            color: #6b7991;
            font-size: 0.78rem;
        }

    </style>

    <section class="hero family-hero">
        <h1>SDA CHURCH MUBS family structure</h1>
        <p class="lead">SDA CHURCH MUBS structures their members into four families leaded by a commeette of five people that is Jordan, Jericho, Jerusalem and Hebron for a better church service work spiritually, socially and financially. They follow the cities in the bible as detailed below:</p>
    </section>

    <section class="family-layout" aria-label="Church families and calendar">
        <div class="family-grid" aria-label="Church families">
            @forelse($families as $family)
                @php
                    $photoPath = $family['image'] ?? '';
                    $photoUrl = is_string($photoPath) && $photoPath !== '' && file_exists(public_path($photoPath))
                        ? asset($photoPath)
                        : $fallbackPhoto;
                    $familyName = $family['name'] ?? 'Family';
                    $familyHeadName = trim((string) ($family['family_head_name'] ?? ''));
                    $cardTitle = $familyHeadName !== '' ? $familyHeadName : ($familyName . ' Family Head');
                    $headerColor = $familyHeaderColors[$loop->index % count($familyHeaderColors)];
                    $members = [
                        'Family Secretary' => $family['family_secretary_name'] ?? '',
                        'Family Spiritual Leader' => $family['family_spiritual_leader'] ?? '',
                        'Family Financial Mobiliser' => $family['family_financial_mobiliser'] ?? '',
                        'Family Social Wellbeing Leader' => $family['family_social_wellbeing_leader'] ?? '',
                        'Family Contact' => trim((string) ($family['family_contact'] ?? ($family['pastor_phone'] ?? $pastorContact))) ?: 'Not set yet',
                        'Family Email' => trim((string) ($family['family_email'] ?? ($family['pastor_email'] ?? $pastorEmail))) ?: 'Not set yet',
                    ];
                @endphp

                <article class="family-card" style="--family-band: {{ $headerColor }};">
                    <header class="family-card-head">
                        <h2>HEAD: {{ $cardTitle }}</h2>
                    </header>

                    <div class="family-photo-wrap">
                        <img class="family-photo" src="{{ $photoUrl }}" alt="{{ $familyName }} family head photo">
                    </div>

                    <p class="family-name-row">{{ $familyName }}</p>

                    <div class="family-body">
                        <p class="family-sub">{{ $family['intro'] ?? 'Family committee structure' }}</p>

                        <ul class="family-structure">
                            @foreach($members as $role => $name)
                                @if(in_array($role, ['Family Contact', 'Family Email'], true) || (is_string($name) && trim($name) !== ''))
                                    <li><span class="family-role">{{ $role }}:</span> {{ $name }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </article>
            @empty
                <article class="card">
                    <h2>No families yet</h2>
                    <p class="meta">Add family data in config/church.php to display them here.</p>
                </article>
            @endforelse
        </div>

        <aside class="family-calendar-side" aria-label="Kitchen services and other activities per family">
            <h2 class="family-calendar-head">FRIDAY KITCHEN SERVICE FAMILY ROTATION</h2>

            <div class="family-calendar-controls">
                <button type="button" class="family-calendar-btn" id="familyCalendarPrev" aria-label="Previous month">Prev</button>
                <p class="family-calendar-month" id="familyCalendarMonth">Month Year</p>
                <button type="button" class="family-calendar-btn" id="familyCalendarNext" aria-label="Next month">Next</button>
            </div>

            <div class="family-calendar-weekdays" aria-hidden="true">
                <span>Sun</span>
                <span>Mon</span>
                <span>Tue</span>
                <span>Wed</span>
                <span>Thu</span>
                <span>Fri</span>
                <span>Sat</span>
            </div>

            <div class="family-calendar-grid" id="familyCalendarGrid" role="grid" aria-label="Monthly activities calendar"></div>

            <div class="family-calendar-detail" id="familyCalendarDetail" aria-live="polite">
                <h3 id="familyCalendarDetailTitle">Select a date</h3>
                <p id="familyCalendarDetailBody">Every Friday one clan serves in the kitchen. Select a Friday to see the assigned clan and message.</p>
            </div>
        </aside>
    </section>

    <script>
        (function () {
            const monthLabel = document.getElementById('familyCalendarMonth');
            const grid = document.getElementById('familyCalendarGrid');
            const prevBtn = document.getElementById('familyCalendarPrev');
            const nextBtn = document.getElementById('familyCalendarNext');
            const detailTitle = document.getElementById('familyCalendarDetailTitle');
            const detailBody = document.getElementById('familyCalendarDetailBody');

            if (!monthLabel || !grid || !prevBtn || !nextBtn || !detailTitle || !detailBody) {
                return;
            }

            const today = new Date();
            const rawActivities = {!! $familyCalendarActivitiesJson !!};
            const weekdayMap = {
                Sunday: 0,
                Monday: 1,
                Tuesday: 2,
                Wednesday: 3,
                Thursday: 4,
                Friday: 5,
                Saturday: 6,
            };

            const recurringActivities = {};
            const specificDateActivities = {};

            rawActivities.forEach(function (row) {
                const dayName = (row.day || '').trim();
                const dateValue = (row.date || '').trim();
                const eventEntry = {
                    area: (row.area || '').trim() || 'Jordan',
                    activity: (row.activity || '').trim() || 'Serving in the kitchen and related activities. Please keep time and work together.',
                };

                if (dateValue !== '') {
                    if (!specificDateActivities[dateValue]) {
                        specificDateActivities[dateValue] = [];
                    }
                    specificDateActivities[dateValue].push(eventEntry);
                    return;
                }

                if (Object.prototype.hasOwnProperty.call(weekdayMap, dayName)) {
                    const dayIndex = weekdayMap[dayName];
                    if (!recurringActivities[dayIndex]) {
                        recurringActivities[dayIndex] = [];
                    }
                    recurringActivities[dayIndex].push(eventEntry);
                }
            });

            let viewDate = new Date(today.getFullYear(), today.getMonth(), 1);
            let selectedDate = new Date(today.getFullYear(), today.getMonth(), today.getDate());

            function normalizeDate(value) {
                return new Date(value.getFullYear(), value.getMonth(), value.getDate());
            }

            function toIsoDate(value) {
                return value.toISOString().split('T')[0];
            }

            function formatDate(value) {
                return value.toLocaleDateString(undefined, {
                    weekday: 'long',
                    month: 'long',
                    day: 'numeric',
                    year: 'numeric',
                });
            }

            function getActivityForDate(value) {
                const iso = toIsoDate(value);
                const specific = specificDateActivities[iso] || [];

                if (specific.length > 0) {
                    return specific;
                }

                const recurring = recurringActivities[value.getDay()] || [];

                if (value.getDay() !== weekdayMap.Friday || recurring.length <= 1) {
                    return recurring;
                }

                const firstFridayOfYear = new Date(value.getFullYear(), 0, 1);

                while (firstFridayOfYear.getDay() !== weekdayMap.Friday) {
                    firstFridayOfYear.setDate(firstFridayOfYear.getDate() + 1);
                }

                const diffInDays = Math.floor((normalizeDate(value).getTime() - normalizeDate(firstFridayOfYear).getTime()) / 86400000);
                const fridayIndex = Math.floor(diffInDays / 7);
                const rotationIndex = ((fridayIndex % recurring.length) + recurring.length) % recurring.length;

                return [recurring[rotationIndex]];
            }

            function updateDetails(value) {
                const activity = getActivityForDate(value);
                detailTitle.textContent = formatDate(value);

                if (!activity.length) {
                    detailBody.textContent = 'No clan kitchen service is scheduled for this date.';
                    return;
                }

                detailBody.textContent = activity.map(function (item) {
                    return item.area + ': ' + item.activity;
                }).join(' | ');
            }

            function renderCalendar() {
                const year = viewDate.getFullYear();
                const month = viewDate.getMonth();
                const firstDay = new Date(year, month, 1);
                const startOffset = firstDay.getDay();
                const daysInMonth = new Date(year, month + 1, 0).getDate();
                const daysInPrevMonth = new Date(year, month, 0).getDate();
                const selectedIso = toIsoDate(normalizeDate(selectedDate));
                const todayIso = toIsoDate(normalizeDate(today));

                monthLabel.textContent = firstDay.toLocaleDateString(undefined, { month: 'long', year: 'numeric' });
                grid.innerHTML = '';

                const totalCells = 42;

                for (let i = 0; i < totalCells; i++) {
                    const cell = document.createElement('button');
                    cell.type = 'button';
                    cell.className = 'family-calendar-day';
                    cell.setAttribute('role', 'gridcell');

                    let currentDate;

                    if (i < startOffset) {
                        const day = daysInPrevMonth - startOffset + i + 1;
                        currentDate = new Date(year, month - 1, day);
                        cell.classList.add('outside');
                    } else if (i >= startOffset + daysInMonth) {
                        const day = i - (startOffset + daysInMonth) + 1;
                        currentDate = new Date(year, month + 1, day);
                        cell.classList.add('outside');
                    } else {
                        const day = i - startOffset + 1;
                        currentDate = new Date(year, month, day);
                    }

                    const iso = toIsoDate(currentDate);
                    const activity = getActivityForDate(currentDate);

                    if (activity.length) {
                        cell.classList.add('has-event');
                    }

                    if (iso === todayIso) {
                        cell.classList.add('today');
                    }

                    if (iso === selectedIso) {
                        cell.classList.add('active');
                    }

                    const number = document.createElement('span');
                    number.textContent = String(currentDate.getDate());
                    cell.appendChild(number);

                    if (activity.length) {
                        const label = document.createElement('small');
                        label.textContent = activity[0].area;
                        cell.appendChild(label);
                    }

                    cell.addEventListener('click', function () {
                        selectedDate = currentDate;
                        viewDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
                        renderCalendar();
                        updateDetails(currentDate);
                    });

                    grid.appendChild(cell);
                }
            }

            prevBtn.addEventListener('click', function () {
                viewDate = new Date(viewDate.getFullYear(), viewDate.getMonth() - 1, 1);
                renderCalendar();
            });

            nextBtn.addEventListener('click', function () {
                viewDate = new Date(viewDate.getFullYear(), viewDate.getMonth() + 1, 1);
                renderCalendar();
            });

            renderCalendar();
            updateDetails(selectedDate);
        })();
    </script>
@endsection

