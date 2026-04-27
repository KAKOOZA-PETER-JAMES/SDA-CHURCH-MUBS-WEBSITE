@extends('layouts.site')

@section('title', 'SDA CHURCH MUBS | Database Registration')

@section('content')
    @php
        $mubsPrograms = [
            'Bachelor of Business Administration',
            'Bachelor of Procurement and Supply Chain Management',
            'Bachelor of International Business',
            'Bachelor of Office and Information Management',
            'Bachelor of Human Resource Management',
            'Bachelor of Entrepreneurship and Small Business Management',
            'Bachelor of Science in Accounting',
            'Bachelor of Commerce',
            'Bachelor of Economics and Statistics',
            'Bachelor of Tourism and Hospitality Management',
            'Bachelor of Leisure and Hospitality Management',
            'Bachelor of Vocational Studies in Agriculture',
            'Bachelor of Computer Science',
            'Bachelor of Information Systems',
            'Bachelor of Business Computing',
            'Bachelor of Science in Marketing',
            'Diploma in Business Administration',
            'Diploma in Procurement and Supply Chain Management',
            'Diploma in Accounting and Finance',
            'Diploma in Marketing',
            'Diploma in Entrepreneurship and Small Business Management',
            'Diploma in Human Resource Management',
            'Diploma in Secretarial and Office Management',
            'Certificate in Business Administration',
            'Certificate in Accounting',
            'Certificate in Procurement and Logistics Management',
            'Master of Business Administration',
            'Master of Science in Accounting and Finance',
            'Master of Human Resource Management',
            'Master of Arts in Economic Policy and Management',
            'Doctorate of Business Administration',
            'PhD in Business and Management',
        ];

        $fieldErrors = $errors->getMessages();
        unset($fieldErrors['admin']);
    @endphp

    @php
        $prefill = $prefillData ?? session('registration_data') ?? [];
        $normalizeAdminValue = static function ($value): string {
            $normalized = preg_replace('/\s+/u', ' ', (string) $value);
            return mb_strtolower(trim((string) $normalized));
        };

        $inputName = old('full_name', $prefill['full_name'] ?? '');
        $inputEmail = old('email', $prefill['email'] ?? '');
        $isAdminCredentialInput = $normalizeAdminValue($inputName) === $normalizeAdminValue('SDA CHURCH MUBS')
            && $normalizeAdminValue($inputEmail) === $normalizeAdminValue('mubssdachurch@gmail.com');
        $isAdminPrefill = !empty($adminPrefill ?? []) || $isAdminCredentialInput;

        if (!isset($prefillData) && !empty($adminPrefill ?? [])) {
            $prefill['full_name'] = $adminPrefill['full_name'] ?? ($prefill['full_name'] ?? '');
            $prefill['email'] = $adminPrefill['email'] ?? ($prefill['email'] ?? '');
        }
    @endphp

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.8.0/build/css/intlTelInput.css">

    <style>
        .registration-wrap {
            max-width: 760px;
            margin: 0 auto;
        }

        .registration-card {
            background: #ffffff;
            border: 1px solid #d9e0ec;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(12, 38, 74, 0.08);
        }

        .registration-card-header {
            background: linear-gradient(135deg, #102a52 0%, #0f2b55 100%);
            padding: 2rem 1.5rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        .registration-card-header-icon {
            width: 72px;
            height: 72px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .registration-card-header-icon:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .registration-card-header-icon img {
            width: 56px;
            height: 56px;
            display: block;
            filter: brightness(0) saturate(100%) invert(1);
        }

        .registration-card-header-title {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 0.5px;
        }

        .registration-card-body {
            padding: 1.2rem;
        }

        .registration-title {
            display: none;
        }

        .registration-sub {
            margin: 0 0 1rem;
            color: #5a667a;
        }

        .registration-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.9rem;
        }

        .registration-field {
            display: grid;
            gap: 0.35rem;
        }

        .registration-label-spacer {
            min-height: 1.25rem;
            display: block;
        }

        .registration-field.full {
            grid-column: 1 / -1;
        }

        .registration-student-fields {
            grid-column: 1 / -1;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.9rem;
            padding: 0.9rem;
            border: 1px solid #dbe4f2;
            border-radius: 10px;
            background: #f7faff;
        }

        .registration-student-fields.hidden {
            display: none;
        }

        .registration-label {
            color: #1f4a8a;
            font-weight: 600;
            font-size: 0.92rem;
        }

        .registration-input,
        .registration-select {
            width: 100%;
            border: 1px solid #cfd8e7;
            border-radius: 8px;
            padding: 0.62rem 0.7rem;
            font-size: 0.95rem;
            color: #1a1a1a;
            background: #ffffff;
        }

        .iti {
            width: 100%;
        }

        .registration-field .iti {
            display: block;
        }

        .registration-field .iti input.registration-input {
            padding-left: 7.2rem;
        }

        .registration-field .iti--separate-dial-code .iti__selected-country {
            border-radius: 8px 0 0 8px;
            border-right: 1px solid #d8e2f1;
            background: #f9fbff;
        }

        .registration-select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: linear-gradient(45deg, transparent 50%, #1f4a8a 50%), linear-gradient(135deg, #1f4a8a 50%, transparent 50%);
            background-position: calc(100% - 18px) calc(50% - 2px), calc(100% - 12px) calc(50% - 2px);
            background-size: 6px 6px, 6px 6px;
            background-repeat: no-repeat;
            padding-right: 2.1rem;
            line-height: 1.35;
            min-height: 44px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .registration-select option {
            white-space: normal;
        }

        .registration-input:focus,
        .registration-select:focus {
            outline: none;
            border-color: #1f4a8a;
            box-shadow: 0 0 0 3px rgba(31, 74, 138, 0.12);
        }

        .registration-error {
            margin: 0;
            color: #b13030;
            font-size: 0.82rem;
        }

        .registration-hint {
            margin: 0;
            color: #5a667a;
            font-size: 0.8rem;
        }

        .registration-alert,
        .registration-success {
            border-radius: 8px;
            padding: 0.65rem 0.75rem;
            margin-bottom: 0.9rem;
            font-size: 0.9rem;
        }

        .registration-alert {
            background: #fff4f4;
            border: 1px solid #f1c7c7;
            color: #9d2b2b;
        }

        .registration-success {
            background: #edf8ef;
            border: 1px solid #b8e0c0;
            color: #1f6b35;
        }

        .registration-action {
            margin-top: 1rem;
            display: flex;
            justify-content: flex-end;
        }

        .registration-btn {
            border: 1px solid #1f4a8a;
            background: #1f4a8a;
            color: #ffffff;
            border-radius: 999px;
            padding: 0.58rem 1rem;
            font-weight: 700;
            cursor: pointer;
        }

        .registration-btn:hover {
            background: #153665;
            border-color: #153665;
        }

        @media (max-width: 720px) {
            .registration-wrap {
                padding: 0 0.35rem;
            }

            .registration-card-body {
                padding: 1rem 0.8rem;
            }

            .registration-grid {
                grid-template-columns: 1fr;
            }

            .registration-student-fields {
                grid-template-columns: 1fr;
                padding: 0.72rem;
            }

            .registration-input,
            .registration-select {
                font-size: 16px;
                min-height: 46px;
            }

            .registration-label-spacer {
                min-height: 0;
                display: none;
            }

            .registration-field .iti input.registration-input {
                padding-left: 7rem;
            }

            .registration-select {
                background-position: calc(100% - 16px) calc(50% - 2px), calc(100% - 10px) calc(50% - 2px);
                padding-right: 2.2rem;
            }

            .registration-card-header {
                padding: 1.5rem 1rem;
            }

            .registration-card-header-title {
                font-size: 1.1rem;
            }

            .registration-card-header-icon {
                width: 60px;
                height: 60px;
            }

            .registration-card-header-icon img {
                width: 48px;
                height: 48px;
            }
        }

        @media (max-width: 480px) {
            .registration-label {
                font-size: 0.88rem;
            }

            .registration-input,
            .registration-select {
                border-radius: 10px;
            }

            .registration-select {
                background-position: calc(100% - 14px) calc(50% - 2px), calc(100% - 8px) calc(50% - 2px);
                font-size: 16px;
            }
        }
    </style>

    <section class="registration-wrap">
        <article class="registration-card">
            <div class="registration-card-header">
                <div class="registration-card-header-icon">
                    <img src="{{ asset('6.png') }}" alt="SDA CHURCH MUBS Logo" loading="lazy">
                </div>
                <h1 class="registration-card-header-title">SDA CHURCH MUBS</h1>
            </div>

            <div class="registration-card-body">
                <h1 class="registration-title">Database Registration</h1>
                <p class="registration-sub">This registration is for church members and students who worship from SDA CHURCH MUBS.</p>

                @if(session('success'))
                    <div class="registration-success">{{ session('success') }}</div>
                @endif

                @if(!empty($fieldErrors))
                    <div class="registration-alert">Please correct the highlighted fields and submit again.</div>
                @endif

                @if($errors->has('admin'))
                    <div class="registration-alert">{{ $errors->first('admin') }}</div>
                @endif

            <form action="{{ isset($prefillData) ? route('database.update') : route('database.store') }}" method="post" novalidate>
                @csrf

                <div class="registration-grid">
                    <div class="registration-field">
                        <label class="registration-label" for="full_name">Full Name</label>
                        <input class="registration-input" type="text" id="full_name" name="full_name" value="{{ old('full_name', $prefill['full_name'] ?? '') }}" required>
                        @error('full_name')
                            <p class="registration-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="registration-field">
                        <label class="registration-label" for="email">Email</label>
                        <input class="registration-input" type="email" id="email" name="email" value="{{ old('email', $prefill['email'] ?? '') }}" required>
                        @error('email')
                            <p class="registration-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="registration-field" data-admin-hide="true" style="{{ $isAdminPrefill ? 'display:none;' : '' }}">
                        <span class="registration-label-spacer" aria-hidden="true"></span>
                        <input class="registration-input" type="tel" id="phone" name="phone" aria-label="Phone" placeholder="Phone" value="{{ old('phone', $prefill['phone'] ?? '') }}" {{ $isAdminPrefill ? '' : 'required' }}>
                        @error('phone')
                            <p class="registration-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="registration-field">
                        <label class="registration-label" for="gender">Gender</label>
                        <select class="registration-select" id="gender" name="gender">
                            <option value="">Select</option>
                            <option value="Male" {{ old('gender', $prefill['gender'] ?? '') === 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $prefill['gender'] ?? '') === 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('gender')
                            <p class="registration-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="registration-field full">
                        <label class="registration-label" for="address">Address</label>
                        <input class="registration-input" type="text" id="address" name="address" value="{{ old('address', $prefill['address'] ?? '') }}">
                        @error('address')
                            <p class="registration-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="registration-field full">
                        <label class="registration-label" for="category">Area</label>
                        <select class="registration-select" id="category" name="category" required>
                            <option value="">Select</option>
                            <option value="Student" {{ old('category', $prefill['category'] ?? '') === 'Student' ? 'selected' : '' }}>Student</option>
                            <option value="Other" {{ old('category', $prefill['category'] ?? '') === 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('category')
                            <p class="registration-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="registration-field full">
                        <label class="registration-label" for="division_of_study">Division of Study</label>
                        <select class="registration-select" id="division_of_study" name="division_of_study" required>
                            <option value="">Select</option>
                            <option value="Lesson Study" {{ old('division_of_study', $prefill['division_of_study'] ?? '') === 'Lesson Study' ? 'selected' : '' }}>Lesson Study</option>
                            <option value="Bible Study" {{ old('division_of_study', $prefill['division_of_study'] ?? '') === 'Bible Study' ? 'selected' : '' }}>Bible Study</option>
                            <option value="Fundamental Beliefs Study" {{ old('division_of_study', $prefill['division_of_study'] ?? '') === 'Fundamental Beliefs Study' ? 'selected' : '' }}>Fundamental Beliefs Study</option>
                            <option value="Children Ministry" {{ old('division_of_study', $prefill['division_of_study'] ?? '') === 'Children Ministry' ? 'selected' : '' }}>Children Ministry</option>
                        </select>
                        @error('division_of_study')
                            <p class="registration-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="registration-field full">
                        <label class="registration-label" for="family">Family</label>
                        <select class="registration-select" id="family" name="family" required>
                            <option value="">Select</option>
                            <option value="Jericho" {{ old('family', $prefill['family'] ?? $preferredFamily ?? '') === 'Jericho' ? 'selected' : '' }}>Jericho</option>
                            <option value="Jordan" {{ old('family', $prefill['family'] ?? $preferredFamily ?? '') === 'Jordan' ? 'selected' : '' }}>Jordan</option>
                            <option value="Jerusalem" {{ old('family', $prefill['family'] ?? $preferredFamily ?? '') === 'Jerusalem' ? 'selected' : '' }}>Jerusalem</option>
                            <option value="Hebron" {{ old('family', $prefill['family'] ?? $preferredFamily ?? '') === 'Hebron' ? 'selected' : '' }}>Hebron</option>
                        </select>
                        @error('family')
                            <p class="registration-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="studentFields" class="registration-student-fields {{ old('category', $prefill['category'] ?? '') === 'Student' ? '' : 'hidden' }}">
                        <div class="registration-field">
                            <label class="registration-label" for="year_of_study">Year of Study</label>
                            <input class="registration-input" type="text" id="year_of_study" name="year_of_study" value="{{ old('year_of_study', $prefill['year_of_study'] ?? '') }}" {{ old('category', $prefill['category'] ?? '') === 'Student' ? 'required' : '' }}>
                            @error('year_of_study')
                                <p class="registration-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="registration-field">
                            <label class="registration-label" for="program_name">Program Name</label>
                            <input class="registration-input" type="text" id="program_name" name="program_name" value="{{ old('program_name', $prefill['program_name'] ?? '') }}" list="mubs_programs" placeholder="Type or select your MUBS program" autocomplete="off" {{ old('category', $prefill['category'] ?? '') === 'Student' ? 'required' : '' }}>
                            <datalist id="mubs_programs">
                                @foreach($mubsPrograms as $program)
                                    <option value="{{ $program }}"></option>
                                @endforeach
                            </datalist>
                            @error('program_name')
                                <p class="registration-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="registration-field">
                            <label class="registration-label" for="program_category">Program Category</label>
                            <select class="registration-select" id="program_category" name="program_category" {{ old('category') === 'Student' ? 'required' : '' }}>
                                <option value="">Select</option>
                                <option value="Certificate" {{ old('program_category', $prefill['program_category'] ?? '') === 'Certificate' ? 'selected' : '' }}>Certificate</option>
                                <option value="Diploma" {{ old('program_category', $prefill['program_category'] ?? '') === 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                <option value="Bachelors" {{ old('program_category', $prefill['program_category'] ?? '') === 'Bachelors' ? 'selected' : '' }}>Bachelors</option>
                                <option value="Masters" {{ old('program_category', $prefill['program_category'] ?? '') === 'Masters' ? 'selected' : '' }}>Masters</option>
                                <option value="Doctorate" {{ old('program_category', $prefill['program_category'] ?? '') === 'Doctorate' ? 'selected' : '' }}>Doctorate</option>
                                <option value="PhD" {{ old('program_category', $prefill['program_category'] ?? '') === 'PhD' ? 'selected' : '' }}>PhD</option>
                            </select>
                            @error('program_category')
                                <p class="registration-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="registration-field">
                            <label class="registration-label" for="year_of_entry">Year of Entry</label>
                            <input class="registration-input" type="text" id="year_of_entry" name="year_of_entry" placeholder="e.g. 2023/2024" pattern="\d{4}/\d{4}" value="{{ old('year_of_entry', $prefill['year_of_entry'] ?? '') }}" {{ old('category', $prefill['category'] ?? '') === 'Student' ? 'required' : '' }}>
                            @error('year_of_entry')
                                <p class="registration-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="registration-field">
                            <label class="registration-label" for="hostel_name">Hostel Name</label>
                            <input class="registration-input" type="text" id="hostel_name" name="hostel_name" value="{{ old('hostel_name', $prefill['hostel_name'] ?? '') }}">
                            <p class="registration-hint">Required for students if renting area is empty.</p>
                            @error('hostel_name')
                                <p class="registration-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="registration-field">
                            <label class="registration-label" for="renting_area">Renting Area</label>
                            <input class="registration-input" type="text" id="renting_area" name="renting_area" value="{{ old('renting_area', $prefill['renting_area'] ?? '') }}">
                            <p class="registration-hint">Required for students if hostel name is empty.</p>
                            @error('renting_area')
                                <p class="registration-error">{{ $message }}</p>
                            @enderror
                        </div>

                        @error('student_residence')
                            <div class="registration-field full">
                                <p class="registration-error">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="registration-action">
                            <div id="updatesPreferenceRow" style="{{ $isAdminPrefill ? 'display:none;' : 'display:flex; align-items:center; gap:0.6rem; margin-right:auto;' }}">
                                <input type="checkbox" id="wants_updates" name="wants_updates" {{ old('wants_updates', $prefill['wants_updates'] ?? false) ? 'checked' : '' }}>
                                <label for="wants_updates" style="font-weight:700; color:#314a69;">By checking this box you will receive our emails for updates</label>
                            </div>
                            <button class="registration-btn" type="submit">Save Registration</button>
                </div>
            </form>            </div>        </article>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.8.0/build/js/intlTelInput.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.8.0/build/js/utils.js"></script>
    <script>
        (function () {
            const ADMIN_NAME = 'SDA CHURCH MUBS';
            const ADMIN_EMAIL = 'mubssdachurch@gmail.com';

            const fullNameInput = document.getElementById('full_name');
            const emailInput = document.getElementById('email');
            const phoneInput = document.getElementById('phone');
            const genderInput = document.getElementById('gender');
            const addressInput = document.getElementById('address');
            const categoryInput = document.getElementById('category');
            const divisionInput = document.getElementById('division_of_study');
            const familyInput = document.getElementById('family');
            const studentFields = document.getElementById('studentFields');
            const registrationGrid = document.querySelector('.registration-grid');
            const registrationSub = document.querySelector('.registration-sub');
            const submitBtn = document.querySelector('.registration-btn');
            const form = document.querySelector('form[action*="database"]');
            const selectControls = Array.from(document.querySelectorAll('.registration-select'));
            const updatesPreferenceRow = document.getElementById('updatesPreferenceRow');
            let phoneIntl = null;

            const adminHiddenControls = [
                phoneInput,
                genderInput,
                addressInput,
                categoryInput,
                divisionInput,
                familyInput,
            ].filter(Boolean);

            if (!fullNameInput || !emailInput || !submitBtn) {
                return;
            }

            if (window.intlTelInput && phoneInput) {
                phoneIntl = window.intlTelInput(phoneInput, {
                    initialCountry: 'ug',
                    separateDialCode: true,
                    nationalMode: false,
                    autoPlaceholder: 'aggressive',
                    formatOnDisplay: true,
                    strictMode: true,
                    dropdownContainer: document.body,
                    utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@23.8.0/build/js/utils.js',
                });

                if (phoneInput.value.trim() !== '') {
                    phoneIntl.setNumber(phoneInput.value.trim());
                }

                if (form) {
                    form.addEventListener('submit', function () {
                        const internationalNumber = phoneIntl.getNumber();
                        if (internationalNumber) {
                            phoneInput.value = internationalNumber.replace(/\s+/g, '');
                        }
                    });
                }
            }

            const normalizeValue = (val) => String(val).toLowerCase().replace(/\s+/g, ' ').trim();

            const syncSelectTitles = () => {
                selectControls.forEach(function (select) {
                    const selected = select.options[select.selectedIndex];
                    const selectedText = selected ? selected.textContent.trim() : '';
                    select.title = selectedText;
                });
            };

            const isAdminLogin = () => {
                const isNameMatch = normalizeValue(fullNameInput.value) === normalizeValue(ADMIN_NAME);
                const isEmailMatch = normalizeValue(emailInput.value) === normalizeValue(ADMIN_EMAIL);
                return isNameMatch && isEmailMatch;
            };

            const showAdminMode = () => {
                if (registrationSub) registrationSub.style.display = 'none';
                phoneInput.parentElement.style.display = 'none';
                if (updatesPreferenceRow) updatesPreferenceRow.style.display = 'none';
                genderInput.parentElement.style.display = 'none';
                addressInput.parentElement.style.display = 'none';
                categoryInput.parentElement.style.display = 'none';
                divisionInput.parentElement.style.display = 'none';
                familyInput.parentElement.style.display = 'none';
                if (studentFields) studentFields.style.display = 'none';

                adminHiddenControls.forEach(function (field) {
                    field.required = false;
                    field.disabled = true;
                });

                const studentInputs = studentFields ? studentFields.querySelectorAll('input, select, textarea') : [];
                studentInputs.forEach(function (field) {
                    field.required = false;
                    field.disabled = true;
                });

                submitBtn.textContent = 'Log In';
                submitBtn.style.backgroundColor = '#25a049';
                submitBtn.style.borderColor = '#25a049';
            };

            const showNormalMode = () => {
                if (registrationSub) registrationSub.style.display = 'block';
                phoneInput.parentElement.style.display = 'block';
                if (updatesPreferenceRow) updatesPreferenceRow.style.display = 'flex';
                genderInput.parentElement.style.display = 'block';
                addressInput.parentElement.style.display = 'block';
                categoryInput.parentElement.style.display = 'block';
                divisionInput.parentElement.style.display = 'block';
                familyInput.parentElement.style.display = 'block';

                phoneInput.required = true;
                categoryInput.required = true;
                divisionInput.required = true;
                familyInput.required = true;

                adminHiddenControls.forEach(function (field) {
                    field.disabled = false;
                });

                const studentInputs = studentFields ? studentFields.querySelectorAll('input, select, textarea') : [];
                studentInputs.forEach(function (field) {
                    field.disabled = false;
                });

                if (studentFields && categoryInput.value === 'Student') {
                    studentFields.style.display = 'grid';
                }
                submitBtn.textContent = 'Save Registration';
                submitBtn.style.backgroundColor = '#1f4a8a';
                submitBtn.style.borderColor = '#1f4a8a';
            };

            const checkAdminMode = () => {
                if (isAdminLogin()) {
                    showAdminMode();
                } else {
                    showNormalMode();
                }
            };

            fullNameInput.addEventListener('input', checkAdminMode);
            emailInput.addEventListener('input', checkAdminMode);
            selectControls.forEach(function (select) {
                select.addEventListener('change', syncSelectTitles);
                // Add input event for better mobile/touch support
                select.addEventListener('input', syncSelectTitles);
            });

            // Initial check
            checkAdminMode();
            syncSelectTitles();

            // Rest of the original script
            const category = document.getElementById('category');
            const yearOfStudy = document.getElementById('year_of_study');
            const programName = document.getElementById('program_name');
            const programCategory = document.getElementById('program_category');
            const yearOfEntry = document.getElementById('year_of_entry');
            const hostelName = document.getElementById('hostel_name');
            const rentingArea = document.getElementById('renting_area');

            if (!category || !studentFields || !yearOfStudy || !programName || !programCategory || !yearOfEntry || !hostelName || !rentingArea) {
                return;
            }

            function syncResidenceValidation() {
                const isStudent = category.value === 'Student';
                const hasHostel = hostelName.value.trim() !== '';
                const hasRentingArea = rentingArea.value.trim() !== '';
                const needResidence = isStudent && !hasHostel && !hasRentingArea;
                const message = 'For students, provide either Hostel Name or Renting Area.';

                hostelName.required = false;
                rentingArea.required = false;

                hostelName.setCustomValidity(needResidence ? message : '');
                rentingArea.setCustomValidity(needResidence ? message : '');
            }

            function toggleStudentFields() {
                const isStudent = category.value === 'Student';

                studentFields.classList.toggle('hidden', !isStudent);
                yearOfStudy.required = isStudent;
                programName.required = isStudent;
                programCategory.required = isStudent;
                yearOfEntry.required = isStudent;

                syncResidenceValidation();

                if (!isStudent) {
                    yearOfStudy.value = '';
                    programName.value = '';
                    programCategory.value = '';
                    yearOfEntry.value = '';
                    hostelName.value = '';
                    rentingArea.value = '';
                    hostelName.setCustomValidity('');
                    rentingArea.setCustomValidity('');
                }
            }

            function formatAcademicYear(value) {
                const digits = value.replace(/\D/g, '').slice(0, 8);

                if (digits.length <= 4) {
                    return digits;
                }

                return digits.slice(0, 4) + '/' + digits.slice(4);
            }

            yearOfEntry.addEventListener('input', function () {
                const formatted = formatAcademicYear(yearOfEntry.value);

                if (yearOfEntry.value !== formatted) {
                    yearOfEntry.value = formatted;
                }
            });

            yearOfEntry.addEventListener('blur', function () {
                yearOfEntry.value = formatAcademicYear(yearOfEntry.value);
            });

            hostelName.addEventListener('input', syncResidenceValidation);
            rentingArea.addEventListener('input', syncResidenceValidation);

            category.addEventListener('change', toggleStudentFields);
            // Add input event for better mobile/touch support
            category.addEventListener('input', toggleStudentFields);
            toggleStudentFields();
        })();
    </script>
@endsection

