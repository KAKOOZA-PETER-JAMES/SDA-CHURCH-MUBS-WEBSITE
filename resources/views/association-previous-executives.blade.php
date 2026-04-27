@extends('layouts.site')

@section('title', 'SDA CHURCH MUBS | Previous Association Executives')

@section('content')
    @php
        $content = \App\Support\AdminContentStore::get();
        $rawPeriods = is_array($content['association_previous_executives'] ?? null) ? $content['association_previous_executives'] : [];

        $resolveImage = static function ($path) {
            $value = trim((string) $path);

            if ($value === '') {
                return asset('department-head-placeholder.svg');
            }

            if (preg_match('/^https?:\/\//i', $value)) {
                return $value;
            }

            return asset($value);
        };

        $periods = collect($rawPeriods)
            ->filter(function ($period) {
                return is_array($period)
                    && trim((string) ($period['years'] ?? '')) !== ''
                    && trim((string) ($period['name'] ?? '')) !== '';
            })
            ->map(function ($period) use ($resolveImage) {
                $executives = collect($period['executives'] ?? [])
                    ->filter(function ($member) {
                        return is_array($member) && trim((string) ($member['name'] ?? '')) !== '';
                    })
                    ->map(function ($member) use ($resolveImage) {
                        return [
                            'role' => trim((string) ($member['role'] ?? 'Executive Member')),
                            'name' => trim((string) ($member['name'] ?? '')),
                            'photo' => $resolveImage($member['photo'] ?? ''),
                        ];
                    })
                    ->values()
                    ->all();

                return [
                    'years' => trim((string) ($period['years'] ?? '')),
                    'name' => trim((string) ($period['name'] ?? '')),
                    'photo' => $resolveImage($period['photo'] ?? ''),
                    'executives' => $executives,
                ];
            })
            ->values()
            ->all();
    @endphp

    <style>
        .assoc-history-page {
            display: grid;
            gap: 1rem;
        }

        .assoc-history-hero {
            margin: 0;
            border-radius: 14px;
            padding: clamp(0.9rem, 3.2vw, 1.4rem);
            background: linear-gradient(135deg, #0d2a52 0%, #154779 55%, #2f6fa1 100%);
            color: #ffffff;
            box-shadow: 0 12px 24px rgba(12, 34, 68, 0.18);
        }

        .assoc-history-hero h1 {
            margin: 0;
            text-align: center;
            font-size: clamp(1.45rem, 4vw, 2.3rem);
            line-height: 1.12;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .assoc-history-hero p {
            margin: 0.6rem auto 0;
            max-width: 800px;
            text-align: center;
            color: rgba(236, 244, 255, 0.95);
            line-height: 1.55;
        }

        .assoc-timeline {
            position: relative;
            display: grid;
            gap: 0.95rem;
            padding: 0.35rem 0;
        }

        .assoc-timeline::before {
            content: '';
            position: absolute;
            top: 0.45rem;
            bottom: 0.45rem;
            left: 50%;
            width: 3px;
            transform: translateX(-50%);
            background: linear-gradient(180deg, #76a8c7 0%, #5f8fb5 100%);
            border-radius: 999px;
        }

        .assoc-period {
            position: relative;
            display: flex;
        }

        .assoc-period:nth-child(odd) {
            justify-content: flex-start;
        }

        .assoc-period:nth-child(even) {
            justify-content: flex-end;
        }

        .assoc-period-btn {
            width: min(100%, 46%);
            min-height: 118px;
            border: 1px solid #ccd9ec;
            border-radius: 12px;
            background: #ffffff;
            display: grid;
            grid-template-columns: 86px 1fr;
            gap: 0.75rem;
            align-items: center;
            padding: 0.55rem;
            text-align: left;
            cursor: pointer;
            box-shadow: 0 8px 18px rgba(15, 43, 85, 0.08);
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .assoc-period-btn:hover,
        .assoc-period-btn:focus-visible,
        .assoc-period-btn.is-active {
            border-color: #91add1;
            box-shadow: 0 10px 22px rgba(15, 43, 85, 0.16);
            transform: translateY(-2px);
            outline: none;
        }

        .assoc-period-photo {
            width: 82px;
            height: 82px;
            border-radius: 10px;
            object-fit: cover;
            border: 1px solid #d8e4f4;
            background: #eef4ff;
        }

        .assoc-period-years {
            margin: 0;
            color: #2e6f90;
            font-weight: 800;
            letter-spacing: 0.03em;
            font-size: 0.92rem;
            text-transform: uppercase;
        }

        .assoc-period-name {
            margin: 0.2rem 0 0;
            color: #12375f;
            font-size: 1.45rem;
            font-weight: 800;
            line-height: 1.12;
            text-transform: uppercase;
        }

        .assoc-period-dot {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 14px;
            height: 14px;
            transform: translate(-50%, -50%);
            border-radius: 999px;
            background: #2f86a1;
            border: 2px solid #0f4f6b;
            box-shadow: 0 0 0 4px #ecf4ff;
        }

        .assoc-members {
            border: 1px solid #d2e0f2;
            border-radius: 14px;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            padding: 0.85rem;
            box-shadow: 0 10px 20px rgba(15, 43, 85, 0.08);
        }

        .assoc-members h2 {
            margin: 0;
            color: #0f3158;
            font-size: clamp(1.05rem, 3vw, 1.35rem);
            text-transform: uppercase;
            text-align: center;
        }

        .assoc-top-three {
            margin-top: 0.8rem;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.75rem;
        }

        .assoc-lead-card {
            border: 1px solid #d3e0f2;
            border-radius: 12px;
            background: #ffffff;
            text-align: center;
            padding: 0.7rem 0.65rem;
        }

        .assoc-lead-card img {
            width: 88px;
            height: 88px;
            border-radius: 999px;
            object-fit: cover;
            border: 2px solid #d0deef;
            background: #eef4ff;
        }

        .assoc-lead-role {
            margin: 0.45rem 0 0;
            color: #2f6f92;
            font-size: 0.78rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .assoc-lead-name {
            margin: 0.22rem 0 0;
            color: #0f3158;
            font-size: 0.95rem;
            font-weight: 800;
            line-height: 1.25;
            text-transform: uppercase;
        }

        .assoc-all-list {
            margin: 0.9rem 0 0;
            padding-left: 1.15rem;
            display: grid;
            gap: 0.36rem;
        }

        .assoc-all-list li {
            color: #22476f;
            line-height: 1.45;
        }

        .assoc-all-list strong {
            color: #0f3158;
        }

        .assoc-empty {
            margin: 0;
            color: #426287;
            text-align: center;
            padding: 0.9rem 0.3rem;
            font-weight: 600;
        }

        @media (max-width: 900px) {
            .assoc-timeline::before {
                left: 12px;
                transform: none;
            }

            .assoc-period,
            .assoc-period:nth-child(odd),
            .assoc-period:nth-child(even) {
                justify-content: flex-start;
            }

            .assoc-period-btn {
                width: 100%;
                margin-left: 1.45rem;
            }

            .assoc-period-dot {
                left: 12px;
            }

            .assoc-top-three {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <section class="assoc-history-page" aria-label="Previous association executives archive">
        <header class="assoc-history-hero">
            <h1>PREVIOUS ASSOCIATION PRESIDENTS SINCE ESTABLISMENT</h1>
            <p>Select a period card to view that year's full executive team. The top row highlights President, Vice President, and Secretary.</p>
        </header>

        @if(!empty($periods))
            <section id="assocTimeline" class="assoc-timeline" aria-label="Association presidents timeline">
                @foreach($periods as $index => $period)
                    <article class="assoc-period">
                        <button
                            class="assoc-period-btn{{ $index === 0 ? ' is-active' : '' }}"
                            type="button"
                            data-index="{{ $index }}"
                            aria-label="View executive team for {{ $period['years'] }}"
                        >
                            <img class="assoc-period-photo" src="{{ $period['photo'] }}" alt="{{ $period['name'] }} photo">
                            <div>
                                <p class="assoc-period-years">{{ $period['years'] }}</p>
                                <p class="assoc-period-name">{{ $period['name'] }}</p>
                            </div>
                        </button>
                        <span class="assoc-period-dot" aria-hidden="true"></span>
                    </article>
                @endforeach
            </section>

            <section class="assoc-members" aria-live="polite">
                <h2 id="assocMembersHeading"></h2>
                <div id="assocTopThree" class="assoc-top-three"></div>
                <ol id="assocAllMembers" class="assoc-all-list"></ol>
            </section>
        @else
            <p class="assoc-empty">No previous association executive periods are available yet.</p>
        @endif
    </section>

    @if(!empty($periods))
        <script>
            (function () {
                const periods = @json($periods);
                const buttons = Array.from(document.querySelectorAll('.assoc-period-btn'));
                const heading = document.getElementById('assocMembersHeading');
                const topThree = document.getElementById('assocTopThree');
                const allMembers = document.getElementById('assocAllMembers');
                const fallbackPhoto = @json(asset('department-head-placeholder.svg'));

                if (!buttons.length || !heading || !topThree || !allMembers) {
                    return;
                }

                const roleOrder = ['president', 'vice president', 'secretary'];

                function normalizeRole(value) {
                    return String(value || '').trim().toLowerCase();
                }

                function createLeadCard(member, roleLabel) {
                    const card = document.createElement('article');
                    card.className = 'assoc-lead-card';

                    const img = document.createElement('img');
                    img.src = member && member.photo ? member.photo : fallbackPhoto;
                    img.alt = (member && member.name ? member.name : roleLabel) + ' photo';

                    const role = document.createElement('p');
                    role.className = 'assoc-lead-role';
                    role.textContent = roleLabel;

                    const name = document.createElement('p');
                    name.className = 'assoc-lead-name';
                    name.textContent = member && member.name ? member.name : 'To be updated';

                    card.appendChild(img);
                    card.appendChild(role);
                    card.appendChild(name);

                    return card;
                }

                function renderPeriod(index) {
                    const selected = periods[index];
                    if (!selected) {
                        return;
                    }

                    buttons.forEach(function (button, buttonIndex) {
                        button.classList.toggle('is-active', buttonIndex === index);
                    });

                    const executives = Array.isArray(selected.executives) ? selected.executives : [];
                    heading.textContent = selected.years + ' ALL EXECUTIVE MEMBERS';

                    topThree.innerHTML = '';
                    roleOrder.forEach(function (roleName) {
                        const match = executives.find(function (member) {
                            return normalizeRole(member.role) === roleName;
                        });
                        const label = roleName === 'vice president' ? 'Vice President' : roleName.charAt(0).toUpperCase() + roleName.slice(1);
                        topThree.appendChild(createLeadCard(match, label));
                    });

                    allMembers.innerHTML = '';
                    executives.forEach(function (member) {
                        const item = document.createElement('li');
                        item.innerHTML = '<strong>' + (member.role || 'Executive Member') + ':</strong> ' + (member.name || 'To be updated');
                        allMembers.appendChild(item);
                    });
                }

                buttons.forEach(function (button) {
                    button.addEventListener('click', function () {
                        const index = Number(button.getAttribute('data-index') || 0);
                        renderPeriod(index);
                    });
                });

                renderPeriod(0);
            })();
        </script>
    @endif
@endsection

