<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\ForumMessage;
use App\Models\ForumMessageReaction;
use App\Support\BrevoMailer;
use App\Support\AdminContentStore;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $guardRedirect = $this->guard($request);

        if ($guardRedirect instanceof RedirectResponse) {
            return $guardRedirect;
        }

        $content = AdminContentStore::get();
        $registrations = Registration::query()->latest('id')->get();
        $studentRegistrationsCount = Registration::query()->where('category', 'Student')->count();
        $otherRegistrationsCount = Registration::query()->where('category', 'Other')->count();
        $forumMessagesCount = ForumMessage::query()->count();
        $forumMessages = ForumMessage::query()
            ->with(['parent:id,name,message'])
            ->latest('created_at')
            ->get()
            ->sortBy('created_at')
            ->values();

        return view('admin-dashboard', [
            'content' => $content,
            'registrations' => $registrations,
            'studentRegistrationsCount' => $studentRegistrationsCount,
            'otherRegistrationsCount' => $otherRegistrationsCount,
            'forumMessagesCount' => $forumMessagesCount,
            'forumMessages' => $forumMessages,
        ]);
    }

    public function clearForumMessages(Request $request): JsonResponse
    {
        $guardRedirect = $this->guard($request);

        if ($guardRedirect instanceof RedirectResponse) {
            return response()->json(['error' => 'Administrator access required.'], 403);
        }

        $messages = ForumMessage::query()->get(['id', 'attachment_path']);

        foreach ($messages as $message) {
            if ($message->attachment_path) {
                Storage::disk('public')->delete($message->attachment_path);
            }
        }

        $deletedReactions = ForumMessageReaction::query()->delete();
        $deletedMessages = ForumMessage::query()->delete();

        return response()->json([
            'ok' => true,
            'deleted_messages' => $deletedMessages,
            'deleted_reactions' => $deletedReactions,
        ]);
    }

    public function destroyForumMessage(Request $request, ForumMessage $message): JsonResponse
    {
        $guardRedirect = $this->guard($request);

        if ($guardRedirect instanceof RedirectResponse) {
            return response()->json(['error' => 'Administrator access required.'], 403);
        }

        if ($message->attachment_path) {
            Storage::disk('public')->delete($message->attachment_path);
        }

        $message->delete();

        return response()->json([
            'ok' => true,
            'deleted_message_id' => $message->id,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $guardRedirect = $this->guard($request);

        if ($guardRedirect instanceof RedirectResponse) {
            return $guardRedirect;
        }

        $validated = $request->validate([
            'pastor.name' => ['nullable', 'string', 'max:120'],
            'pastor.photo' => ['nullable', 'string', 'max:255'],
            'pastor.photo_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'pastor.phone' => ['nullable', 'string', 'max:40'],
            'pastor.whatsapp' => ['nullable', 'string', 'max:40'],
            'pastor.email' => ['nullable', 'email', 'max:120'],

            'pastors_since_inception' => ['nullable', 'array'],
            'pastors_since_inception.*.name' => ['nullable', 'string', 'max:140'],
            'pastors_since_inception.*.years' => ['nullable', 'string', 'max:60'],
            'pastors_since_inception.*.photo' => ['nullable', 'string', 'max:255'],
            'pastors_since_inception.*.photo_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'pastors_since_inception.*._delete' => ['nullable', 'in:0,1,on'],

            'association_previous_executives' => ['nullable', 'array'],
            'association_previous_executives.*.years' => ['nullable', 'string', 'max:60'],
            'association_previous_executives.*.name' => ['nullable', 'string', 'max:160'],
            'association_previous_executives.*.photo' => ['nullable', 'string', 'max:255'],
            'association_previous_executives.*.photo_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'association_previous_executives.*._delete' => ['nullable', 'in:0,1,on'],
            'association_previous_executives.*.executives' => ['nullable', 'array'],
            'association_previous_executives.*.executives.*.role' => ['nullable', 'string', 'max:120'],
            'association_previous_executives.*.executives.*.name' => ['nullable', 'string', 'max:160'],
            'association_previous_executives.*.executives.*.photo' => ['nullable', 'string', 'max:255'],
            'association_previous_executives.*.executives.*.photo_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'association_previous_executives.*.executives.*._delete' => ['nullable', 'in:0,1,on'],

            'events' => ['nullable', 'array'],
            'events.*.month' => ['nullable', 'string', 'max:40'],
            'events.*.title' => ['nullable', 'string', 'max:160'],
            'events.*.date_range' => ['nullable', 'string', 'max:120'],
            'events.*.department' => ['nullable', 'string', 'max:120'],
            'events.*.details' => ['nullable', 'string', 'max:500'],

            'event_media' => ['nullable', 'array'],
            'event_media.*.category' => ['nullable', 'in:evangelism-campaign,community-outreach,social-events'],
            'event_media.*.section' => ['nullable', 'in:story,videos,gallery'],
            'event_media.*.title' => ['nullable', 'string', 'max:200'],
            'event_media.*.description' => ['nullable', 'string', 'max:500'],
            'event_media.*.media_url' => ['nullable', 'string', 'max:255'],
            'event_media.*.media_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,gif,mp4,webm,mov,m4v', 'max:51200'],
            'event_media.*.thumbnail' => ['nullable', 'string', 'max:255'],
            'event_media.*.thumbnail_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'event_media.*._delete' => ['nullable', 'in:0,1,on'],

            'hero_slides' => ['nullable', 'array'],
            'hero_slides.*.title' => ['nullable', 'string', 'max:200'],
            'hero_slides.*.subtitle' => ['nullable', 'string', 'max:400'],
            'hero_slides.*.image' => ['nullable', 'string', 'max:255'],
            'hero_slides.*.image_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'hero_slides.*.link' => ['nullable', 'string', 'max:255'],
            'hero_slides.*.text_color' => ['nullable', 'string', 'max:12'],
            'hero_slides.*._delete' => ['nullable', 'in:0,1,on'],

            'family_calendar_activities' => ['nullable', 'array'],
            'family_calendar_activities.*.date' => ['nullable', 'date_format:Y-m-d'],
            'family_calendar_activities.*.day' => ['nullable', 'in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday'],
            'family_calendar_activities.*.area' => ['nullable', 'string', 'max:180'],
            'family_calendar_activities.*.activity' => ['nullable', 'string', 'max:220'],
            'family_calendar_activities.*.time' => ['nullable', 'string', 'max:80'],
            'family_calendar_activities.*._delete' => ['nullable', 'in:0,1,on'],

            'updates' => ['nullable', 'array'],
            'updates.*.month' => ['nullable', 'string', 'max:40'],
            'updates.*.title' => ['nullable', 'string', 'max:160'],
            'updates.*.date_range' => ['nullable', 'string', 'max:120'],
            'updates.*.department' => ['nullable', 'string', 'max:120'],
            'updates.*.details' => ['nullable', 'string', 'max:500'],
            'updates.*._delete' => ['nullable', 'in:0,1,on'],

            'upcoming_sabbaths' => ['nullable', 'array'],
            'upcoming_sabbaths.*.text' => ['nullable', 'string', 'max:220'],
            'upcoming_sabbaths.*.media_url' => ['nullable', 'string', 'max:255'],
            'upcoming_sabbaths.*.media_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,gif,mp4,webm,mov', 'max:15360'],
            'upcoming_sabbaths.*._delete' => ['nullable', 'in:0,1,on'],

            'daily_communication' => ['nullable', 'array'],
            'daily_communication.*.text' => ['nullable', 'string', 'max:320'],
            'daily_communication.*.media_url' => ['nullable', 'string', 'max:255'],
            'daily_communication.*.media_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,gif,mp4,webm,mov', 'max:15360'],
            'daily_communication.*._delete' => ['nullable', 'in:0,1,on'],

            'departments.church_board' => ['nullable', 'array'],
            'departments.church_board.*.name' => ['nullable', 'string', 'max:120'],
            'departments.church_board.*.image' => ['nullable', 'string', 'max:255'],
            'departments.church_board.*.image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'departments.church_board.*.intro' => ['nullable', 'string', 'max:500'],
            'departments.church_board.*.department_introduction' => ['nullable', 'string', 'max:500'],
            'departments.church_board.*.department_head_name' => ['nullable', 'string', 'max:120'],
            'departments.church_board.*.department_head_photo' => ['nullable', 'string', 'max:255'],
            'departments.church_board.*.department_head_photo_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'departments.church_board.*.secretary_name' => ['nullable', 'string', 'max:120'],
            'departments.church_board.*.secretary_photo' => ['nullable', 'string', 'max:255'],
            'departments.church_board.*.secretary_photo_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'departments.church_board.*.explore_url' => ['nullable', 'string', 'max:255'],
            'departments.church_board.*.details' => ['nullable', 'string', 'max:400'],
            'departments.church_board.*.pastor_name' => ['nullable', 'string', 'max:120'],
            'departments.church_board.*.pastor_phone' => ['nullable', 'string', 'max:40'],
            'departments.church_board.*.pastor_email' => ['nullable', 'email', 'max:120'],
            'departments.church_board.*.pastor_info' => ['nullable', 'string', 'max:400'],
            'departments.church_board.*._delete' => ['nullable', 'in:0,1,on'],

            'departments.association' => ['nullable', 'array'],
            'departments.association.*.name' => ['nullable', 'string', 'max:120'],
            'departments.association.*.image' => ['nullable', 'string', 'max:255'],
            'departments.association.*.image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'departments.association.*.intro' => ['nullable', 'string', 'max:500'],
            'departments.association.*.department_introduction' => ['nullable', 'string', 'max:500'],
            'departments.association.*.department_head_name' => ['nullable', 'string', 'max:120'],
            'departments.association.*.department_head_photo' => ['nullable', 'string', 'max:255'],
            'departments.association.*.department_head_photo_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'departments.association.*.secretary_name' => ['nullable', 'string', 'max:120'],
            'departments.association.*.secretary_photo' => ['nullable', 'string', 'max:255'],
            'departments.association.*.secretary_photo_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'departments.association.*.explore_url' => ['nullable', 'string', 'max:255'],
            'departments.association.*.details' => ['nullable', 'string', 'max:400'],
            'departments.association.*.pastor_name' => ['nullable', 'string', 'max:120'],
            'departments.association.*.pastor_phone' => ['nullable', 'string', 'max:40'],
            'departments.association.*.pastor_email' => ['nullable', 'email', 'max:120'],
            'departments.association.*.pastor_info' => ['nullable', 'string', 'max:400'],
            'departments.association.*._delete' => ['nullable', 'in:0,1,on'],

            'departments.church_board_leaders' => ['nullable', 'array'],
            'departments.church_board_leaders.*.role' => ['nullable', 'string', 'max:120'],
            'departments.church_board_leaders.*.name' => ['nullable', 'string', 'max:120'],
            'departments.church_board_leaders.*.message' => ['nullable', 'string', 'max:2000'],
            'departments.church_board_leaders.*.image' => ['nullable', 'string', 'max:255'],
            'departments.church_board_leaders.*.image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'departments.church_board_leaders.*._delete' => ['nullable', 'in:0,1,on'],

            'departments.association_leaders' => ['nullable', 'array'],
            'departments.association_leaders.*.role' => ['nullable', 'string', 'max:120'],
            'departments.association_leaders.*.name' => ['nullable', 'string', 'max:120'],
            'departments.association_leaders.*.message' => ['nullable', 'string', 'max:2000'],
            'departments.association_leaders.*.image' => ['nullable', 'string', 'max:255'],
            'departments.association_leaders.*.image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'departments.association_leaders.*._delete' => ['nullable', 'in:0,1,on'],

            'departments.church_families' => ['nullable', 'array'],
            'departments.church_families.*.name' => ['nullable', 'string', 'max:120'],
            'departments.church_families.*.image' => ['nullable', 'string', 'max:255'],
            'departments.church_families.*.image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'departments.church_families.*.intro' => ['nullable', 'string', 'max:500'],
            'departments.church_families.*.secretary_name' => ['nullable', 'string', 'max:120'],
            'departments.church_families.*.explore_url' => ['nullable', 'string', 'max:255'],
            'departments.church_families.*.family_head_name' => ['nullable', 'string', 'max:120'],
            'departments.church_families.*.family_secretary_name' => ['nullable', 'string', 'max:120'],
            'departments.church_families.*.family_spiritual_leader' => ['nullable', 'string', 'max:120'],
            'departments.church_families.*.family_financial_mobiliser' => ['nullable', 'string', 'max:120'],
            'departments.church_families.*.family_social_wellbeing_leader' => ['nullable', 'string', 'max:120'],
            'departments.church_families.*.family_contact' => ['nullable', 'string', 'max:40'],
            'departments.church_families.*.family_email' => ['nullable', 'email', 'max:120'],
            'departments.church_families.*.details' => ['nullable', 'string', 'max:400'],
            'departments.church_families.*.pastor_name' => ['nullable', 'string', 'max:120'],
            'departments.church_families.*.pastor_phone' => ['nullable', 'string', 'max:40'],
            'departments.church_families.*.pastor_email' => ['nullable', 'email', 'max:120'],
            'departments.church_families.*.pastor_info' => ['nullable', 'string', 'max:400'],
        ]);

        $pastorPhoto = trim((string) data_get($validated, 'pastor.photo', ''));
        $pastorPhotoFile = $request->file('pastor.photo_file');

        if ($pastorPhotoFile instanceof UploadedFile) {
            $pastorPhoto = $this->storeDepartmentImage($pastorPhotoFile);
        }

        $existingContent = AdminContentStore::get();

        $content = [
            'pastor' => [
                'name' => trim((string) data_get($validated, 'pastor.name', '')),
                'photo' => $pastorPhoto,
                'phone' => trim((string) data_get($validated, 'pastor.phone', '')),
                'whatsapp' => trim((string) data_get($validated, 'pastor.whatsapp', '')),
                'email' => trim((string) data_get($validated, 'pastor.email', '')),
            ],
            'pastors_since_inception' => $this->normalizePastorHistoryRows(
                $validated['pastors_since_inception'] ?? [],
                $request->file('pastors_since_inception', [])
            ),
            'association_previous_executives' => $this->normalizeAssociationExecutivePeriodsRows(
                $validated['association_previous_executives'] ?? [],
                $request->file('association_previous_executives', [])
            ),
            'events' => $request->has('events')
                ? $this->normalizeStructuredRows($validated['events'] ?? [])
                : ($existingContent['events'] ?? []),
            'event_media' => $this->normalizeEventMediaRows(
                $validated['event_media'] ?? [],
                $request->file('event_media', [])
            ),
            'hero_slides' => $this->normalizeHeroSlidesRows(
                $validated['hero_slides'] ?? [],
                $request->file('hero_slides', [])
            ),
            'family_calendar_activities' => $this->normalizeFamilyCalendarRows($validated['family_calendar_activities'] ?? []),
            'updates' => $this->normalizeStructuredRows($validated['updates'] ?? []),
            'upcoming_sabbaths' => $this->normalizeNoticeRows(
                $validated['upcoming_sabbaths'] ?? [],
                $request->file('upcoming_sabbaths', [])
            ),
            'daily_communication' => $this->normalizeNoticeRows(
                $validated['daily_communication'] ?? [],
                $request->file('daily_communication', [])
            ),
            'departments' => [
                'church_board' => $this->normalizeNamedRows(
                    data_get($validated, 'departments.church_board', []),
                    $request->file('departments.church_board', [])
                ),
                'association' => $this->normalizeNamedRows(
                    data_get($validated, 'departments.association', []),
                    $request->file('departments.association', [])
                ),
                'church_families' => $this->normalizeNamedRows(
                    data_get($validated, 'departments.church_families', []),
                    $request->file('departments.church_families', [])
                ),
                'church_board_leaders' => $this->normalizeLeaderRows(
                    data_get($validated, 'departments.church_board_leaders', []),
                    $request->file('departments.church_board_leaders', [])
                ),
                'association_leaders' => $this->normalizeLeaderRows(
                    data_get($validated, 'departments.association_leaders', []),
                    $request->file('departments.association_leaders', [])
                ),
            ],
            '_meta' => [
                'last_admin_update_at' => now()->toIso8601String(),
            ],
        ];

        AdminContentStore::save($content);

        $recipientCount = 0;
        $sentCount = 0;
        $failedCount = 0;

        try {
            $recipientCount = Registration::query()
                ->where('wants_updates', true)
                ->whereNotNull('email')
                ->where('email', '<>', '')
                ->count();

            Registration::query()
                ->where('wants_updates', true)
                ->whereNotNull('email')
                ->where('email', '<>', '')
                ->orderBy('id')
                ->chunk(100, function ($rows) use ($content, &$sentCount, &$failedCount) {
                    foreach ($rows as $member) {
                        try {
                            if (app(BrevoMailer::class)->sendUpdateNotification($member->email, $content)) {
                                $sentCount++;
                            } else {
                                $failedCount++;
                            }
                        } catch (\Throwable $e) {
                            $failedCount++;
                            \Log::error('Admin update email failed.', [
                                'registration_id' => $member->id ?? null,
                                'email' => $member->email ?? null,
                                'error' => $e->getMessage(),
                            ]);
                        }
                    }
                });
        } catch (\Throwable $e) {
            \Log::error('Admin update email batch failed.', [
                'error' => $e->getMessage(),
            ]);
        }

        return redirect()->route('admin.dashboard')
            ->with('success', 'Dashboard content updated successfully.')
            ->with('mail_summary', 'Attempted update emails for ' . $recipientCount . ' subscribed member' . ($recipientCount === 1 ? '' : 's') . '. Sent: ' . $sentCount . '. Failed: ' . $failedCount . '.');
    }

    public function downloadMembers(Request $request)
    {
        $guardRedirect = $this->guard($request);

        if ($guardRedirect instanceof RedirectResponse) {
            return $guardRedirect;
        }

        $fileName = 'registered-members-' . date('Y-m-d-His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = static function (): void {
            $output = fopen('php://output', 'w');

            fputcsv($output, [
                'ID',
                'Full Name',
                'Email',
                'Phone',
                'Gender',
                'Address',
                'Category',
                'Year of Study',
                'Program Name',
                'Program Category',
                'Year of Entry',
                'Division of Study',
                'Family',
                'Created At',
            ]);

            Registration::query()->orderBy('id')->chunk(200, function ($rows) use ($output): void {
                foreach ($rows as $member) {
                    fputcsv($output, [
                        $member->id,
                        $member->full_name,
                        $member->email,
                        $member->phone,
                        $member->gender,
                        $member->address,
                        $member->category,
                        $member->year_of_study,
                        $member->program_name,
                        $member->program_category,
                        $member->year_of_entry,
                        $member->division_of_study,
                        $member->family,
                        optional($member->created_at)->toDateTimeString(),
                    ]);
                }
            });

            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('is_admin_dashboard');

        return redirect()->route('database')->with('success', 'Admin dashboard session closed.');
    }

    private function guard(Request $request): ?RedirectResponse
    {
        if ($request->session()->get('is_admin_dashboard')) {
            return null;
        }

        return redirect()->route('database', ['admin' => 1])->withErrors([
            'admin' => 'Administrator access required. Please register using administrator details.',
        ]);
    }

    private function normalizeStructuredRows(array $rows): array
    {
        $normalized = [];

        foreach ($rows as $row) {
            $markedForDelete = in_array((string) ($row['_delete'] ?? ''), ['1', 'on'], true);
            if ($markedForDelete) {
                continue;
            }

            $month = trim((string) ($row['month'] ?? ''));
            $title = trim((string) ($row['title'] ?? ''));
            $dateRange = trim((string) ($row['date_range'] ?? ''));
            $department = trim((string) ($row['department'] ?? ''));
            $details = trim((string) ($row['details'] ?? ''));

            if ($month === '' && $title === '' && $dateRange === '' && $department === '' && $details === '') {
                continue;
            }

            $normalized[] = [
                'month' => $month,
                'title' => $title,
                'date_range' => $dateRange,
                'department' => $department,
                'details' => $details,
            ];
        }

        return $normalized;
    }

    private function normalizeSimpleRows(array $rows): array
    {
        $normalized = [];

        foreach ($rows as $row) {
            $value = trim((string) $row);

            if ($value !== '') {
                $normalized[] = $value;
            }
        }

        return $normalized;
    }

    private function normalizeNoticeRows(array $rows, array $fileRows = []): array
    {
        $normalized = [];

        foreach ($rows as $index => $row) {
            $markedForDelete = in_array((string) ($row['_delete'] ?? ''), ['1', 'on'], true);
            if ($markedForDelete) {
                continue;
            }

            $text = trim((string) ($row['text'] ?? ''));
            $mediaUrl = trim((string) ($row['media_url'] ?? ''));
            $mediaFile = data_get($fileRows, $index . '.media_file');

            if ($mediaFile instanceof UploadedFile) {
                $mediaUrl = $this->storeDepartmentImage($mediaFile);
            }

            if ($text === '' && $mediaUrl === '') {
                continue;
            }

            $normalized[] = [
                'text' => $text,
                'media_url' => $mediaUrl,
            ];
        }

        return $normalized;
    }

    private function normalizeNamedRows(array $rows, array $fileRows = []): array
    {
        $normalized = [];

        foreach ($rows as $index => $row) {
            $markedForDelete = in_array((string) ($row['_delete'] ?? ''), ['1', 'on'], true);
            if ($markedForDelete) {
                continue;
            }

            $name = trim((string) ($row['name'] ?? ''));
            $image = trim((string) ($row['image'] ?? ''));
            $intro = trim((string) ($row['intro'] ?? ''));
            $departmentIntroduction = trim((string) ($row['department_introduction'] ?? ''));
            $secretaryName = trim((string) ($row['secretary_name'] ?? ''));
            $departmentHeadName = trim((string) ($row['department_head_name'] ?? ''));
            $departmentHeadPhoto = trim((string) ($row['department_head_photo'] ?? ''));
            $secretaryPhoto = trim((string) ($row['secretary_photo'] ?? ''));
            $exploreUrl = trim((string) ($row['explore_url'] ?? ''));
            $details = trim((string) ($row['details'] ?? ''));
            $pastorName = trim((string) ($row['pastor_name'] ?? ''));
            $pastorPhone = trim((string) ($row['pastor_phone'] ?? ''));
            $pastorEmail = trim((string) ($row['pastor_email'] ?? ''));
            $pastorInfo = trim((string) ($row['pastor_info'] ?? ''));
            $familyHeadName = trim((string) ($row['family_head_name'] ?? ''));
            $familySecretaryName = trim((string) ($row['family_secretary_name'] ?? ''));
            $familySpiritualLeader = trim((string) ($row['family_spiritual_leader'] ?? ''));
            $familyFinancialMobiliser = trim((string) ($row['family_financial_mobiliser'] ?? ''));
            $familySocialWellbeingLeader = trim((string) ($row['family_social_wellbeing_leader'] ?? ''));
            $familyContact = trim((string) ($row['family_contact'] ?? ($row['pastor_phone'] ?? '')));
            $familyEmail = trim((string) ($row['family_email'] ?? ($row['pastor_email'] ?? '')));
            $imageFile = data_get($fileRows, $index . '.image_file');
            $departmentHeadPhotoFile = data_get($fileRows, $index . '.department_head_photo_file');
            $secretaryPhotoFile = data_get($fileRows, $index . '.secretary_photo_file');

            if ($imageFile instanceof UploadedFile) {
                $image = $this->storeDepartmentImage($imageFile);
            }

            if ($departmentHeadPhotoFile instanceof UploadedFile) {
                $departmentHeadPhoto = $this->storeDepartmentImage($departmentHeadPhotoFile);
            }

            if ($secretaryPhotoFile instanceof UploadedFile) {
                $secretaryPhoto = $this->storeDepartmentImage($secretaryPhotoFile);
            }

            if ($departmentHeadName === '' && $pastorName !== '') {
                $departmentHeadName = $pastorName;
            }

            if ($departmentHeadPhoto === '' && $image !== '') {
                $departmentHeadPhoto = $image;
            }

            if ($secretaryPhoto === '' && $image !== '') {
                $secretaryPhoto = $image;
            }

            if ($departmentIntroduction === '' && $details !== '') {
                $departmentIntroduction = $details;
            }

            if (
                $name === '' && $image === '' && $intro === '' && $secretaryName === '' && $exploreUrl === '' &&
                $details === '' && $pastorName === '' && $pastorPhone === '' && $pastorEmail === '' &&
                $pastorInfo === '' && $familyHeadName === '' && $familySecretaryName === '' &&
                $familySpiritualLeader === '' && $familyFinancialMobiliser === '' && $familySocialWellbeingLeader === '' &&
                $familyContact === '' && $familyEmail === '' &&
                $departmentIntroduction === '' && $departmentHeadName === '' && $departmentHeadPhoto === '' &&
                $secretaryPhoto === ''
            ) {
                continue;
            }

            $normalized[] = [
                'name' => $name,
                'image' => $image,
                'intro' => $intro,
                'department_introduction' => $departmentIntroduction,
                'department_head_name' => $departmentHeadName,
                'department_head_photo' => $departmentHeadPhoto,
                'secretary_name' => $secretaryName,
                'secretary_photo' => $secretaryPhoto,
                'explore_url' => $exploreUrl,
                'details' => $details,
                'pastor_name' => $pastorName,
                'pastor_phone' => $pastorPhone,
                'pastor_email' => $pastorEmail,
                'pastor_info' => $pastorInfo,
                'family_head_name' => $familyHeadName,
                'family_secretary_name' => $familySecretaryName,
                'family_spiritual_leader' => $familySpiritualLeader,
                'family_financial_mobiliser' => $familyFinancialMobiliser,
                'family_social_wellbeing_leader' => $familySocialWellbeingLeader,
                'family_contact' => $familyContact,
                'family_email' => $familyEmail,
            ];
        }

        return $normalized;
    }

    private function normalizeLeaderRows(array $rows, array $fileRows = []): array
    {
        $normalized = [];

        foreach ($rows as $index => $row) {
            $markedForDelete = in_array((string) ($row['_delete'] ?? ''), ['1', 'on'], true);
            if ($markedForDelete) {
                continue;
            }

            $role = trim((string) ($row['role'] ?? ''));
            $name = trim((string) ($row['name'] ?? ''));
            $message = trim((string) ($row['message'] ?? ''));
            $image = trim((string) ($row['image'] ?? ''));
            $imageFile = data_get($fileRows, $index . '.image_file');

            if ($imageFile instanceof UploadedFile) {
                $image = $this->storeDepartmentImage($imageFile);
            }

            if ($role === '' && $name === '' && $message === '' && $image === '') {
                continue;
            }

            $normalized[] = [
                'role' => $role,
                'name' => $name,
                'message' => $message,
                'image' => $image,
            ];
        }

        return $normalized;
    }

    private function normalizeHeroSlidesRows(array $rows, array $fileRows = []): array
    {
        $normalized = [];

        foreach ($rows as $index => $row) {
            $markedForDelete = in_array((string) ($row['_delete'] ?? ''), ['1', 'on'], true);
            if ($markedForDelete) {
                continue;
            }

            $title = trim((string) ($row['title'] ?? ''));
            $subtitle = trim((string) ($row['subtitle'] ?? ''));
            $image = trim((string) ($row['image'] ?? ''));
            $link = trim((string) ($row['link'] ?? ''));
            $textColor = trim((string) ($row['text_color'] ?? ''));
            $imageFile = data_get($fileRows, $index . '.image_file');

            if ($image !== '' && !preg_match('/^https?:\/\//i', $image)) {
                $image = str_replace('\\', '/', $image);
                $image = preg_replace('#^\./#', '', $image);
                $image = preg_replace('#^public/#i', '', $image);
                $image = ltrim($image, '/');
            }

            if ($imageFile instanceof UploadedFile) {
                $image = $this->storeDepartmentImage($imageFile);
            }

            if ($title === '' && $subtitle === '' && $image === '' && $link === '') {
                continue;
            }

            $normalized[] = [
                'title' => $title,
                'subtitle' => $subtitle,
                'image' => $image,
                'link' => $link,
                'text_color' => preg_match('/^#[0-9a-fA-F]{6}$/', $textColor) ? strtolower($textColor) : '#ffffff',
            ];
        }

        return $normalized;
    }

    private function normalizeEventMediaRows(array $rows, array $fileRows = []): array
    {
        $normalized = [];

        foreach ($rows as $index => $row) {
            $markedForDelete = in_array((string) ($row['_delete'] ?? ''), ['1', 'on'], true);
            if ($markedForDelete) {
                continue;
            }

            $category = strtolower(trim((string) ($row['category'] ?? '')));
            if ($category !== '') {
                // Keep a stable slug so event filtering and menu links always match.
                $category = str_replace(['_', ' '], '-', $category);
            }
            $section = trim((string) ($row['section'] ?? ''));
            $title = trim((string) ($row['title'] ?? ''));
            $description = trim((string) ($row['description'] ?? ''));
            $mediaUrl = trim((string) ($row['media_url'] ?? ''));
            $thumbnail = trim((string) ($row['thumbnail'] ?? ''));
            $mediaFile = data_get($fileRows, $index . '.media_file');
            $thumbnailFile = data_get($fileRows, $index . '.thumbnail_file');

            if ($mediaFile instanceof UploadedFile) {
                $mediaUrl = $this->storeDepartmentImage($mediaFile);
            }

            if ($thumbnailFile instanceof UploadedFile) {
                $thumbnail = $this->storeDepartmentImage($thumbnailFile);
            }

            if ($category === '' && $section === '' && $title === '' && $description === '' && $mediaUrl === '' && $thumbnail === '') {
                continue;
            }

            $normalized[] = [
                'category' => $category,
                'section' => $section,
                'title' => $title,
                'description' => $description,
                'media_url' => $mediaUrl,
                'thumbnail' => $thumbnail,
            ];
        }

        return $normalized;
    }

    private function normalizeFamilyCalendarRows(array $rows): array
    {
        $normalized = [];

        foreach ($rows as $row) {
            $markedForDelete = in_array((string) ($row['_delete'] ?? ''), ['1', 'on'], true);
            if ($markedForDelete) {
                continue;
            }

            $date = trim((string) ($row['date'] ?? ''));
            $day = trim((string) ($row['day'] ?? ''));
            $area = trim((string) ($row['area'] ?? ''));
            $activity = trim((string) ($row['activity'] ?? ''));
            $time = trim((string) ($row['time'] ?? ''));

            if ($date === '' && $day === '' && $area === '' && $activity === '' && $time === '') {
                continue;
            }

            $normalized[] = [
                'date' => $date,
                'day' => $day,
                'area' => $area,
                'activity' => $activity,
                'time' => $time,
            ];
        }

        return $normalized;
    }

    private function normalizePastorHistoryRows(array $rows, array $fileRows = []): array
    {
        $normalized = [];

        foreach ($rows as $index => $row) {
            $markedForDelete = in_array((string) ($row['_delete'] ?? ''), ['1', 'on'], true);
            if ($markedForDelete) {
                continue;
            }

            $name = trim((string) ($row['name'] ?? ''));
            $years = trim((string) ($row['years'] ?? ''));
            $photo = trim((string) ($row['photo'] ?? ''));
            $photoFile = data_get($fileRows, $index . '.photo_file');

            if ($photoFile instanceof UploadedFile) {
                $photo = $this->storeDepartmentImage($photoFile);
            }

            if ($name === '' && $years === '' && $photo === '') {
                continue;
            }

            $normalized[] = [
                'name' => $name,
                'years' => $years,
                'photo' => $photo,
            ];
        }

        return $normalized;
    }

    private function normalizeAssociationExecutivePeriodsRows(array $rows, array $fileRows = []): array
    {
        $normalized = [];

        foreach ($rows as $index => $row) {
            $markedForDelete = in_array((string) ($row['_delete'] ?? ''), ['1', 'on'], true);
            if ($markedForDelete) {
                continue;
            }

            $years = trim((string) ($row['years'] ?? ''));
            $name = trim((string) ($row['name'] ?? ''));
            $photo = trim((string) ($row['photo'] ?? ''));
            $photoFile = data_get($fileRows, $index . '.photo_file');

            if ($photoFile instanceof UploadedFile) {
                $photo = $this->storeDepartmentImage($photoFile);
            }

            $executiveRows = is_array($row['executives'] ?? null) ? $row['executives'] : [];
            $executiveFileRows = data_get($fileRows, $index . '.executives', []);
            $executives = [];

            foreach ($executiveRows as $memberIndex => $member) {
                $memberMarkedForDelete = in_array((string) ($member['_delete'] ?? ''), ['1', 'on'], true);
                if ($memberMarkedForDelete) {
                    continue;
                }

                $memberRole = trim((string) ($member['role'] ?? ''));
                $memberName = trim((string) ($member['name'] ?? ''));
                $memberPhoto = trim((string) ($member['photo'] ?? ''));
                $memberPhotoFile = data_get($executiveFileRows, $memberIndex . '.photo_file');

                if ($memberPhotoFile instanceof UploadedFile) {
                    $memberPhoto = $this->storeDepartmentImage($memberPhotoFile);
                }

                if ($memberRole === '' && $memberName === '' && $memberPhoto === '') {
                    continue;
                }

                $executives[] = [
                    'role' => $memberRole,
                    'name' => $memberName,
                    'photo' => $memberPhoto,
                ];
            }

            if ($years === '' && $name === '' && $photo === '' && count($executives) === 0) {
                continue;
            }

            if ($name === '') {
                foreach ($executives as $member) {
                    if (strtolower((string) ($member['role'] ?? '')) === 'president' && trim((string) ($member['name'] ?? '')) !== '') {
                        $name = trim((string) $member['name']);
                        break;
                    }
                }
            }

            $normalized[] = [
                'years' => $years,
                'name' => $name,
                'photo' => $photo,
                'executives' => $executives,
            ];
        }

        return $normalized;
    }

    private function storeDepartmentImage(UploadedFile $file): string
    {
        $directory = public_path('uploads/departments');

        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $fileName = 'dept-' . date('YmdHis') . '-' . bin2hex(random_bytes(4)) . '.' . $file->getClientOriginalExtension();
        $file->move($directory, $fileName);

        return 'uploads/departments/' . $fileName;
    }
}
