@extends('layouts.site')

@section('title', 'SDA CHURCH MUBS | Confirm Registration')

@section('content')
    <style>
        .confirm-wrap {
            max-width: 900px;
            margin: 0 auto;
            padding: 3rem 1rem;
        }

        .confirm-container {
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 12px 40px rgba(12, 38, 74, 0.12);
        }

        .confirm-header {
            background: linear-gradient(135deg, #102a52 0%, #0f2b55 100%);
            padding: 3rem 2rem;
            text-align: center;
            color: #ffffff;
        }

        .confirm-header-icon {
            width: 90px;
            height: 90px;
            margin: 0 auto 1.5rem;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid rgba(255, 255, 255, 0.3);
            overflow: hidden;
            padding: 6px;
        }

        .confirm-header-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .confirm-header-title {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #ffffff !important;
        }

        .confirm-header-subtitle {
            margin: 0.5rem 0 0;
            font-size: 1rem;
            color: #ffffff !important;
            opacity: 0.92;
        }

        .confirm-body {
            padding: 3rem 2rem;
        }

        .confirm-section {
            margin-bottom: 3rem;
        }

        .confirm-section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #102a52;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #c19434;
            display: inline-block;
        }

        .confirm-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .confirm-field {
            background: #f8fafc;
            padding: 1.2rem;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .confirm-field:hover {
            background: #f0f5ff;
            border-color: #102a52;
        }

        .confirm-field-label {
            font-size: 0.85rem;
            font-weight: 700;
            color: #5a667a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .confirm-field-value {
            font-size: 1.1rem;
            font-weight: 600;
            color: #102a52;
            word-break: break-word;
        }

        .confirm-field.full {
            grid-column: 1 / -1;
        }

        .confirm-actions {
            display: flex;
            gap: 1rem;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 2px solid #e2e8f0;
        }

        .confirm-btn {
            flex: 1;
            padding: 1rem 2rem;
            border-radius: 999px;
            border: none;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .confirm-btn-confirm {
            background: linear-gradient(135deg, #102a52 0%, #0f2b55 100%);
            color: #ffffff;
        }

        .confirm-btn-confirm:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 42, 82, 0.25);
        }

        .confirm-btn-edit {
            background: #c19434;
            color: #ffffff;
        }

        .confirm-btn-edit:hover {
            background: #a67c2f;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(193, 148, 52, 0.25);
        }

        .confirm-success-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #edf8ef;
            color: #1f6b35;
            padding: 0.5rem 1rem;
            border-radius: 999px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .confirm-success-badge::before {
            content: "✓";
            font-weight: 700;
            font-size: 1.2rem;
        }

        .confirm-warning {
            background: #fff4f4;
            border: 1px solid #f1c7c7;
            color: #9d2b2b;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }

        @media (max-width: 768px) {
            .confirm-wrap {
                padding: 2rem 1rem;
            }

            .confirm-body {
                padding: 2rem 1.5rem;
            }

            .confirm-grid {
                grid-template-columns: 1fr;
            }

            .confirm-actions {
                flex-direction: column;
            }

            .confirm-header {
                padding: 2rem 1.5rem;
            }

            .confirm-header-title {
                font-size: 1.5rem;
            }

            .confirm-header-icon {
                width: 64px;
                height: 64px;
                font-size: 2rem;
            }
        }
    </style>

    <section class="confirm-wrap">
        <article class="confirm-container">
            <div class="confirm-header">
                <div class="confirm-header-icon"><img src="{{ asset('6.png') }}" alt="SDA Church Logo"></div>
                <h1 class="confirm-header-title">Confirm Your Registration</h1>
                <p class="confirm-header-subtitle">Please review your details before completing registration</p>
            </div>

            <div class="confirm-body">
                <div class="confirm-success-badge">
                    Registration data received successfully
                </div>

                @if(!empty($data['email']))
                    <div class="confirm-warning">
                        📋 Once you confirm, this registration will be saved and you'll be redirected to the home page.
                    </div>
                @endif

                <div class="confirm-section">
                    <h2 class="confirm-section-title">Personal Information</h2>
                    <div class="confirm-grid">
                        <div class="confirm-field">
                            <div class="confirm-field-label">Full Name</div>
                            <div class="confirm-field-value">{{ $data['full_name'] ?? 'N/A' }}</div>
                        </div>
                        <div class="confirm-field">
                            <div class="confirm-field-label">Email Address</div>
                            <div class="confirm-field-value">{{ $data['email'] ?? 'N/A' }}</div>
                        </div>
                        <div class="confirm-field">
                            <div class="confirm-field-label">Phone Number</div>
                            <div class="confirm-field-value">{{ $data['phone'] ?? 'N/A' }}</div>
                        </div>
                        <div class="confirm-field">
                            <div class="confirm-field-label">Gender</div>
                            <div class="confirm-field-value">{{ $data['gender'] ?? 'Not specified' }}</div>
                        </div>
                        <div class="confirm-field full">
                            <div class="confirm-field-label">Address</div>
                            <div class="confirm-field-value">{{ $data['address'] ?? 'Not specified' }}</div>
                        </div>
                    </div>
                </div>

                <div class="confirm-section">
                    <h2 class="confirm-section-title">Church & Ministry</h2>
                    <div class="confirm-grid">
                        <div class="confirm-field">
                            <div class="confirm-field-label">Category</div>
                            <div class="confirm-field-value">{{ $data['category'] ?? 'N/A' }}</div>
                        </div>
                        <div class="confirm-field">
                            <div class="confirm-field-label">Family Group</div>
                            <div class="confirm-field-value">{{ $data['family'] ?? 'N/A' }}</div>
                        </div>
                        <div class="confirm-field full">
                            <div class="confirm-field-label">Division of Study</div>
                            <div class="confirm-field-value">{{ $data['division_of_study'] ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                <div class="confirm-section">
                    <h2 class="confirm-section-title">Communication Preferences</h2>
                    <div class="confirm-grid">
                        <div class="confirm-field full">
                            <div class="confirm-field-label">Receive update emails</div>
                            <div class="confirm-field-value">{{ !empty($data['wants_updates']) ? 'Yes — will receive update emails' : 'No — will not receive update emails' }}</div>
                        </div>
                    </div>
                </div>

                @if($data['category'] === 'Student')
                    <div class="confirm-section">
                        <h2 class="confirm-section-title">Student Information</h2>
                        <div class="confirm-grid">
                            <div class="confirm-field">
                                <div class="confirm-field-label">Year of Study</div>
                                <div class="confirm-field-value">{{ $data['year_of_study'] ?? 'N/A' }}</div>
                            </div>
                            <div class="confirm-field">
                                <div class="confirm-field-label">Year of Entry</div>
                                <div class="confirm-field-value">{{ $data['year_of_entry'] ?? 'N/A' }}</div>
                            </div>
                            <div class="confirm-field full">
                                <div class="confirm-field-label">Program Name</div>
                                <div class="confirm-field-value">{{ $data['program_name'] ?? 'N/A' }}</div>
                            </div>
                            <div class="confirm-field">
                                <div class="confirm-field-label">Program Category</div>
                                <div class="confirm-field-value">{{ $data['program_category'] ?? 'N/A' }}</div>
                            </div>
                            @if(!empty($data['hostel_name']))
                                <div class="confirm-field">
                                    <div class="confirm-field-label">Hostel Name</div>
                                    <div class="confirm-field-value">{{ $data['hostel_name'] }}</div>
                                </div>
                            @endif
                            @if(!empty($data['renting_area']))
                                <div class="confirm-field">
                                    <div class="confirm-field-label">Renting Area</div>
                                    <div class="confirm-field-value">{{ $data['renting_area'] }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="confirm-actions">
                    <button class="confirm-btn confirm-btn-edit" onclick="window.history.back()">
                        ← Edit Registration
                    </button>
                    <form method="POST" action="{{ route('registration.finalize') }}" style="flex: 1;">
                        @csrf
                        <button type="submit" class="confirm-btn confirm-btn-confirm" style="width: 100%;">
                            Confirm & Continue ✓
                        </button>
                    </form>
                </div>
            </div>
        </article>
    </section>
@endsection

