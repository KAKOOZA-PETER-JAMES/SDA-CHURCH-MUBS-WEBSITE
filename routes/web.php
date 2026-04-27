<?php

use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\BibleQuizController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\SdaHistoryController;
use App\Http\Controllers\ForumController;
use App\Support\AdminContentStore;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/', 'home')->name('home');
Route::view('/ministry', 'ministry')->name('ministry');
Route::view('/media', 'media')->name('media');
Route::view('/adventist-pocket', 'adventist-pocket')->name('adventist-pocket');
Route::view('/association/previous-executives', 'association-previous-executives')->name('association.previous-executives');
Route::view('/events', 'events')->name('events');
Route::view('/our-journey', 'our-journey')->name('our-journey');
Route::view('/our-beliefs', 'our-beliefs')->name('our-beliefs');
Route::view('/about-us', 'our-journey')->name('about-us');
Route::view('/other-resources', 'other-resources')->name('other-resources');
Route::view('/contact', 'contact')->name('contact');
Route::get('/forum', [ForumController::class, 'index'])->name('forum');
Route::get('/forum/stream', [ForumController::class, 'stream'])->name('forum.stream');
Route::get('/forum/messages', [ForumController::class, 'messages'])->name('forum.messages');
Route::post('/forum/messages', [ForumController::class, 'store'])->name('forum.messages.store');
Route::put('/forum/messages/{message}', [ForumController::class, 'update'])->name('forum.messages.update');
Route::delete('/forum/messages/{message}', [ForumController::class, 'destroy'])->name('forum.messages.destroy');
Route::post('/forum/messages/{message}/delete', [ForumController::class, 'destroyViaPost'])->name('forum.messages.destroy.post');
Route::delete('/forum/messages', [ForumController::class, 'destroyAll'])->name('forum.messages.destroy_all');
Route::get('/forum/messages/{message}/attachment', [ForumController::class, 'attachment'])->name('forum.messages.attachment');
Route::get('/forum/messages/{message}/attachment/download', [ForumController::class, 'downloadAttachment'])->name('forum.messages.attachment.download');
Route::post('/forum/messages/{message}/reactions', [ForumController::class, 'react'])->name('forum.messages.react');
Route::post('/forum/presence', [ForumController::class, 'heartbeat'])->name('forum.presence.heartbeat');
Route::get('/forum/presence', [ForumController::class, 'presence'])->name('forum.presence');
Route::post('/forum/typing', [ForumController::class, 'typing'])->name('forum.typing');
Route::get('/forum/typing', [ForumController::class, 'typingUsers'])->name('forum.typing.users');
Route::get('/sda-history', [SdaHistoryController::class, 'index'])->name('sda-history');
Route::view('/updates', 'updates')->name('updates');
Route::get('/updates/feed', function () {
	$content = AdminContentStore::get();
	$updates = array_values(array_filter((array) ($content['updates'] ?? []), function ($row) {
		return is_array($row) && trim((string) ($row['title'] ?? '')) !== '';
	}));
	$formatEventCategoryLabel = function (string $slug): string {
		$normalized = trim(str_replace(['_', ' '], '-', strtolower($slug)));

		if ($normalized === '') {
			return 'Events';
		}

		return ucwords(str_replace('-', ' ', $normalized));
	};
	$eventSectionLabels = [
		'gallery' => 'Photo',
		'story' => 'Story',
		'videos' => 'Video',
	];
	$eventNotifications = collect($content['event_media'] ?? [])
		->filter(function ($item) {
			return is_array($item);
		})
		->map(function ($item) use ($formatEventCategoryLabel, $eventSectionLabels) {
			$section = strtolower(trim((string) ($item['section'] ?? '')));
			$categorySlug = trim(str_replace(['_', ' '], '-', strtolower((string) ($item['category'] ?? ''))));

			if (!isset($eventSectionLabels[$section]) || $categorySlug === '') {
				return null;
			}

			$title = trim((string) ($item['title'] ?? ''));
			$description = trim((string) ($item['description'] ?? ''));
			$subject = trim(preg_replace('/\s+/', ' ', $title !== '' ? $title : $description));

			if ($subject === '') {
				$subject = $formatEventCategoryLabel($categorySlug);
			}

			if (strlen($subject) > 72) {
				$subject = substr($subject, 0, 69) . '...';
			}

			return [
				'title' => 'New ' . $eventSectionLabels[$section] . ' about ' . $subject,
				'meta' => $formatEventCategoryLabel($categorySlug) . ' - ' . $eventSectionLabels[$section],
				'url' => route('events', ['category' => $categorySlug, 'section' => $section]),
			];
		})
		->filter()
		->values()
		->all();
	$notifications = array_values(array_merge($eventNotifications, $updates));

	return response()->json([
		'count' => count($notifications),
		'updated_at' => trim((string) data_get($content, '_meta.last_admin_update_at', now()->toIso8601String())),
		'updates' => $notifications,
	]);
})->name('updates.feed');
Route::view('/church-families', 'church-families')->name('church-families');
Route::get('/database', [RegistrationController::class, 'create'])->name('database');
Route::post('/database', [RegistrationController::class, 'store'])->name('database.store');
Route::get('/database/edit', [RegistrationController::class, 'edit'])->name('database.edit');
Route::post('/database/update', [RegistrationController::class, 'update'])->name('database.update');
Route::post('/registration/cancel', [RegistrationController::class, 'cancel'])->name('registration.cancel');
Route::get('/registration/confirm', [RegistrationController::class, 'confirm'])->name('registration.confirm');
Route::post('/registration/confirm', [RegistrationController::class, 'finalize'])->name('registration.finalize');
Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
Route::post('/admin/dashboard', [AdminDashboardController::class, 'store'])->name('admin.dashboard.save');
Route::delete('/admin/dashboard/forum/messages', [AdminDashboardController::class, 'clearForumMessages'])->name('admin.dashboard.forum.messages.clear');
Route::delete('/admin/dashboard/forum/messages/{message}', [AdminDashboardController::class, 'destroyForumMessage'])->name('admin.dashboard.forum.messages.destroy');
Route::get('/admin/dashboard/members/download', [AdminDashboardController::class, 'downloadMembers'])->name('admin.dashboard.members.download');
Route::get('/admin/dashboard/logout', [AdminDashboardController::class, 'logout'])->name('admin.dashboard.logout');
Route::get('/bible-quiz', [BibleQuizController::class, 'index'])->name('bible-quiz');
Route::get('/bible-quiz/start', [BibleQuizController::class, 'start'])->name('bible-quiz.start');
Route::post('/bible-quiz', [BibleQuizController::class, 'submit'])->name('bible-quiz.submit');

Route::get('/our-beliefs/{range}', function (string $range) {
	$beliefCategories = config('beliefs.ranges', []);
	$beliefCards = [
		'1-5' => ['image' => 'sda.png'],
		'6-7' => ['image' => 'S1.jpg'],
		'8-11' => ['image' => 'adventist-symbol--black.png'],
		'12-16' => ['image' => '4.jpg'],
		'17-23' => ['image' => 'S2.jpg'],
		'24-28' => ['image' => 'tp-clean.png'],
	];

	abort_unless(array_key_exists($range, $beliefCategories), 404);

	$category = $beliefCategories[$range];
	$otherCategories = [];

	foreach ($beliefCards as $cardRange => $cardMeta) {
		if ($cardRange === $range || !array_key_exists($cardRange, $beliefCategories)) {
			continue;
		}

		$otherCategories[] = [
			'range' => $cardRange,
			'title' => $beliefCategories[$cardRange]['title'] ?? ('Beliefs ' . $cardRange),
			'image' => $cardMeta['image'],
		];
	}

	return view('beliefs-category', [
		'range' => $range,
		'category' => $category,
		'otherCategories' => $otherCategories,
	]);
})->name('beliefs.category');
