<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Support\BrevoMailer;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use PDOException;

class RegistrationController extends Controller
{
    private const ADMIN_NAME = 'SDA CHURCH MUBS';
    private const ADMIN_EMAIL = 'mubssdachurch@gmail.com';

    public function create(): View
    {
        $preferredFamily = request()->query('family');
        $adminPrefill = request()->boolean('admin')
            ? ['full_name' => self::ADMIN_NAME, 'email' => self::ADMIN_EMAIL]
            : [];

        if (empty($adminPrefill)) {
            $existingMember = $this->resolveExistingMember(request());

            if ($existingMember) {
                return redirect()->route('database.edit');
            }
        }

        return view('registration', [
            'preferredFamily' => $preferredFamily,
            'adminPrefill' => $adminPrefill,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $isAdminAttempt = $this->matchesAdminProfile((array) $request->all());

            if ($isAdminAttempt) {
                $request->session()->put('registered_user_name', trim((string) $request->input('full_name', 'Administrator')));
                $request->session()->put('is_admin_dashboard', true);

                return redirect()->route('admin.dashboard')->with('success', 'Administrator access granted.');
            }

            $maxEntryYear = (int) date('Y') + 1;

            $validated = $request->validate([
                'full_name' => ['required', 'string', 'max:120'],
                'email' => ['required', 'email', 'max:120', 'unique:registrations,email'],
                'phone' => ['required', 'string', 'max:20'],
                'gender' => ['nullable', 'string', 'max:20'],
                'address' => ['nullable', 'string', 'max:150'],
                'category' => ['required', 'in:Student,Other'],
                'year_of_study' => ['nullable', 'required_if:category,Student', 'string', 'max:40'],
                'program_name' => ['nullable', 'required_if:category,Student', 'string', 'max:120'],
                'program_category' => ['nullable', 'required_if:category,Student', 'in:Certificate,Diploma,Bachelors,Masters,Doctorate,PhD'],
                'year_of_entry' => ['nullable', 'required_if:category,Student', 'regex:/^\d{4}\/\d{4}$/'],
                'hostel_name' => ['nullable', 'string', 'max:120'],
                'renting_area' => ['nullable', 'string', 'max:120'],
                'division_of_study' => ['required', 'in:Lesson Study,Bible Study,Fundamental Beliefs Study,Fundumental Beliefs Study,Children Ministry'],
                'family' => ['required', 'in:Jericho,Jordan,Jerusalem,Hebron'],
                'wants_updates' => ['nullable'],
            ]);

            $validated['hostel_name'] = trim((string) ($validated['hostel_name'] ?? ''));
            $validated['renting_area'] = trim((string) ($validated['renting_area'] ?? ''));

            if (($validated['category'] ?? null) === 'Student' && $validated['hostel_name'] === '' && $validated['renting_area'] === '') {
                return redirect()->back()
                    ->withErrors(['student_residence' => 'For students, provide either Hostel Name or Renting Area.'])
                    ->withInput();
            }

            if (!empty($validated['year_of_entry'])) {
                [$firstYear, $secondYear] = array_map('intval', explode('/', $validated['year_of_entry']));

                if ($secondYear !== $firstYear + 1 || $firstYear < 1990 || $firstYear > $maxEntryYear) {
                    return redirect()->back()
                        ->withErrors(['year_of_entry' => 'Year of entry must be in format like 2023/2024 and follow consecutive academic years.'])
                        ->withInput();
                }
            }

            if (($validated['category'] ?? null) !== 'Student') {
                $validated['year_of_study'] = null;
                $validated['program_name'] = null;
                $validated['program_category'] = null;
                $validated['year_of_entry'] = null;
                $validated['hostel_name'] = null;
                $validated['renting_area'] = null;
            } else {
                $validated['hostel_name'] = $validated['hostel_name'] === '' ? null : $validated['hostel_name'];
                $validated['renting_area'] = $validated['renting_area'] === '' ? null : $validated['renting_area'];
            }

            // checkbox -> boolean
            $validated['wants_updates'] = $request->has('wants_updates') ? 1 : 0;

            $request->session()->put('registered_user_name', $validated['full_name']);
            Cookie::queue(cookie('mubs_registered_name', $validated['full_name'], 60 * 24 * 365));
            Cookie::queue(cookie('mubs_registered_email', $validated['email'], 60 * 24 * 365));

            // Store registration data in session for confirmation
            $request->session()->put('registration_data', $validated);

            return redirect()->route('registration.confirm');
        } catch (QueryException | PDOException $exception) {
            return redirect()->back()
                ->withErrors(['admin' => 'Registration service is temporarily unavailable. Please check database settings and try again.'])
                ->withInput();
        }
    }

    public function confirm(Request $request)
    {
        $data = $request->session()->get('registration_data');

        if (!$data) {
            return redirect()->route('database')->with('error', 'No registration data found. Please start over.');
        }

        return view('registration-confirm', ['data' => $data]);
    }

    public function finalize(Request $request)
    {
        $data = $request->session()->get('registration_data');

        if (!$data) {
            return redirect()->route('database')->with('error', 'Registration data expired. Please start over.');
        }

        $mailErrors = [];

        try {
            Registration::create($data);

            // 1) Member confirmation email
            if (!empty($data['email'])) {
                try {
                    if (!app(BrevoMailer::class)->sendRegistrationConfirmation($data)) {
                        $mailErrors[] = 'member_confirmation';
                    }
                } catch (\Throwable $e) {
                    $mailErrors[] = 'member_confirmation';
                    Log::error('Registration confirmation email failed.', [
                        'email' => $data['email'] ?? null,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            // 2) Internal/system notification email
            // Fallback to MAIL_FROM_ADDRESS when a dedicated system inbox is not configured.
            $systemRecipient = trim((string) config('mail.system_notification_address', ''));

            if ($systemRecipient === '') {
                $systemRecipient = trim((string) config('mail.from.address', ''));
            }

            if ($systemRecipient !== '') {
                try {
                    if (!app(BrevoMailer::class)->sendSystemRegistrationAlert($data)) {
                        $mailErrors[] = 'system_notification';
                    }
                } catch (\Throwable $e) {
                    $mailErrors[] = 'system_notification';
                    Log::error('System registration alert email failed.', [
                        'recipient' => $systemRecipient,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        } catch (QueryException | PDOException $exception) {
            return redirect()->route('database')
                ->withErrors(['admin' => 'Registration could not be completed because the database is unreachable right now.'])
                ->withInput();
        }

        $request->session()->forget('registration_data');
        $welcomeName = $data['full_name'] ?? 'Member';
        Cookie::queue(cookie('mubs_registered_name', (string) $welcomeName, 60 * 24 * 365));
        if (!empty($data['email'])) {
            Cookie::queue(cookie('mubs_registered_email', (string) $data['email'], 60 * 24 * 365));
        }

        $redirect = redirect()->route('home')
            ->with('welcome_name', $welcomeName)
            ->with('success', 'Welcome, ' . $welcomeName . '! You have successfully joined the association and the church!');

        if (!empty($mailErrors)) {
            $redirect->with('mail_warning', 'Registration was saved, but one or more email notifications could not be delivered. Please check mail settings/logs.');
        }

        return $redirect;
    }

    public function edit(Request $request)
    {
        $registeredUserName = trim((string) $request->session()->get('registered_user_name', ''));
        $prefill = $request->session()->get('registration_data', null);

        if (!$prefill && $registeredUserName !== '') {
            $record = Registration::where('full_name', $registeredUserName)->orderByDesc('id')->first();
            if ($record) {
                $prefill = $record->toArray();
            }
        }

        if (!$prefill) {
            $record = $this->resolveExistingMember($request);

            if ($record) {
                $prefill = $record->toArray();
                $request->session()->put('registered_user_name', (string) ($record->full_name ?? ''));
            }
        }

        if (!$prefill) {
            return redirect()->route('database')->with('error', 'No registration found to edit.');
        }

        return view('registration', ['preferredFamily' => $prefill['family'] ?? null, 'prefillData' => $prefill]);
    }

    public function update(Request $request)
    {
        $registeredUserName = trim((string) $request->session()->get('registered_user_name', ''));
        $record = null;

        if ($registeredUserName !== '') {
            $record = Registration::where('full_name', $registeredUserName)->orderByDesc('id')->first();
        }

        if (!$record) {
            // If no existing record, fall back to creating a new registration
            return $this->store($request);
        }

        $maxEntryYear = (int) date('Y') + 1;

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:120', Rule::unique('registrations', 'email')->ignore($record->id)],
            'phone' => ['required', 'string', 'max:20'],
            'gender' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:150'],
            'category' => ['required', 'in:Student,Other'],
            'year_of_study' => ['nullable', 'required_if:category,Student', 'string', 'max:40'],
            'program_name' => ['nullable', 'required_if:category,Student', 'string', 'max:120'],
            'program_category' => ['nullable', 'required_if:category,Student', 'in:Certificate,Diploma,Bachelors,Masters,Doctorate,PhD'],
            'year_of_entry' => ['nullable', 'required_if:category,Student', 'regex:/^\d{4}\/\d{4}$/'],
            'hostel_name' => ['nullable', 'string', 'max:120'],
            'renting_area' => ['nullable', 'string', 'max:120'],
            'division_of_study' => ['required', 'in:Lesson Study,Bible Study,Fundamental Beliefs Study,Fundumental Beliefs Study,Children Ministry'],
            'family' => ['required', 'in:Jericho,Jordan,Jerusalem,Hebron'],
            'wants_updates' => ['nullable'],
        ]);

        $validated['hostel_name'] = trim((string) ($validated['hostel_name'] ?? ''));
        $validated['renting_area'] = trim((string) ($validated['renting_area'] ?? ''));

        if (($validated['category'] ?? null) === 'Student' && $validated['hostel_name'] === '' && $validated['renting_area'] === '') {
            return redirect()->back()
                ->withErrors(['student_residence' => 'For students, provide either Hostel Name or Renting Area.'])
                ->withInput();
        }

        if (($validated['category'] ?? null) !== 'Student') {
            $validated['year_of_study'] = null;
            $validated['program_name'] = null;
            $validated['program_category'] = null;
            $validated['year_of_entry'] = null;
            $validated['hostel_name'] = null;
            $validated['renting_area'] = null;
        } else {
            $validated['hostel_name'] = $validated['hostel_name'] === '' ? null : $validated['hostel_name'];
            $validated['renting_area'] = $validated['renting_area'] === '' ? null : $validated['renting_area'];
        }

        $validated['wants_updates'] = $request->has('wants_updates') ? 1 : 0;

        $record->update($validated);

        // keep session name in sync
        $request->session()->put('registered_user_name', $validated['full_name']);
        Cookie::queue(cookie('mubs_registered_name', $validated['full_name'], 60 * 24 * 365));
        Cookie::queue(cookie('mubs_registered_email', $validated['email'], 60 * 24 * 365));

        return redirect()->route('home')->with('success', 'Your registration has been updated.');
    }

    public function cancel(Request $request)
    {
        $registeredUserName = trim((string) $request->session()->get('registered_user_name', ''));
        $sessionData = $request->session()->get('registration_data');

        if ($sessionData) {
            // registration not yet finalized; just forget session data
            $request->session()->forget('registration_data');
            $request->session()->forget('registered_user_name');
            Cookie::queue(Cookie::forget('mubs_registered_name'));
            Cookie::queue(Cookie::forget('mubs_registered_email'));

            return redirect()->route('home')->with('success', 'Registration cancelled.');
        }

        if ($registeredUserName !== '') {
            $record = Registration::where('full_name', $registeredUserName)->orderByDesc('id')->first();

            if ($record) {
                try {
                    $record->delete();
                } catch (\Exception $e) {
                    // ignore deletion errors
                }
            }

            $request->session()->forget('registered_user_name');
            Cookie::queue(Cookie::forget('mubs_registered_name'));
            Cookie::queue(Cookie::forget('mubs_registered_email'));

            return redirect()->route('home')->with('success', 'Your registration has been cancelled.');
        }

        return redirect()->route('home')->with('error', 'No active registration found to cancel.');
    }

    private function matchesAdminProfile(array $payload): bool
    {
        $normalize = static function ($value): string {
            $normalized = preg_replace('/\s+/u', ' ', (string) $value);
            return mb_strtolower(trim((string) $normalized));
        };

        return $normalize($payload['full_name'] ?? '') === $normalize(self::ADMIN_NAME)
            && $normalize($payload['email'] ?? '') === $normalize(self::ADMIN_EMAIL);
    }

    private function resolveExistingMember(Request $request): ?Registration
    {
        $sessionName = trim((string) $request->session()->get('registered_user_name', ''));
        $sessionData = $request->session()->get('registration_data', null);

        if (is_array($sessionData) && !empty($sessionData['email'])) {
            $record = Registration::where('email', trim((string) $sessionData['email']))->orderByDesc('id')->first();

            if ($record) {
                return $record;
            }
        }

        if ($sessionName !== '') {
            $record = Registration::where('full_name', $sessionName)->orderByDesc('id')->first();

            if ($record) {
                return $record;
            }
        }

        $cookieEmail = trim((string) $request->cookie('mubs_registered_email', ''));
        if ($cookieEmail !== '') {
            $record = Registration::where('email', $cookieEmail)->orderByDesc('id')->first();

            if ($record) {
                return $record;
            }
        }

        $cookieName = trim((string) $request->cookie('mubs_registered_name', ''));
        if ($cookieName !== '') {
            $record = Registration::where('full_name', $cookieName)->orderByDesc('id')->first();

            if ($record) {
                return $record;
            }
        }

        return null;
    }
}

