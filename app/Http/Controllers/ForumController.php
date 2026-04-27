<?php

namespace App\Http\Controllers;

use App\Models\ForumMessage;
use App\Models\ForumMessageReaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ForumController extends Controller
{
    private const REACTIONS = ['like', 'amen', 'pray', 'love'];

    public function index(): View
    {
        return view('forum');
    }

    public function stream(Request $request)
    {
        $token = $this->resolveToken($request);

        return response()->stream(function () use ($token) {
            @ini_set('zlib.output_compression', '0');
            @ini_set('implicit_flush', '1');
            if (function_exists('apache_setenv')) {
                @apache_setenv('no-gzip', '1');
            }

            $lastHash = '';
            for ($i = 0; $i < 30; $i++) {
                $payload = [
                    'messages' => $this->buildMessagesPayload($token),
                    'presence' => [
                        'online' => array_values($this->collectPresence()),
                        'count' => count($this->collectPresence()),
                    ],
                    'typing' => $this->collectTypingUsers($token),
                ];

                $encoded = (string) json_encode($payload);
                $hash = md5($encoded);

                if ($hash !== $lastHash) {
                    echo "event: sync\n";
                    echo 'data: ' . $encoded . "\n\n";
                    $lastHash = $hash;
                } else {
                    echo "event: ping\n";
                    echo 'data: {"time":' . time() . "}\n\n";
                }

                @ob_flush();
                @flush();
                sleep(1);
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache, no-transform',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    public function messages(Request $request): JsonResponse
    {
        $token = $this->resolveToken($request);
        $payload = $this->buildMessagesPayload($token);

        return response()->json([
            'messages' => $payload,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $token = $this->resolveToken($request);
        $this->guardPostRate($request, $token);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:60', 'regex:/.*\S.*/'],
            'topic' => ['nullable', 'string', 'max:100'],
            'message' => ['nullable', 'string', 'max:1200', 'required_without:attachment'],
            'parent_id' => ['nullable', 'integer', 'exists:forum_messages,id'],
            'attachment' => ['nullable', 'file', 'max:51200', 'required_without:message'],
        ]);

        $attachmentPath = null;
        $attachmentMime = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            if ($file && $file->isValid()) {
                $originalBase = pathinfo((string) $file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeBase = Str::slug((string) $originalBase, '-');
                if ($safeBase === '') {
                    $safeBase = 'file';
                }

                $extension = strtolower((string) $file->getClientOriginalExtension());
                $storedName = $safeBase . '-' . strtolower(Str::random(8));
                if ($extension !== '') {
                    $storedName .= '.' . $extension;
                }

                $attachmentPath = $file->storeAs('uploads/forum', $storedName, 'public');
                $attachmentMime = $file->getMimeType();
            }
        }

        $message = ForumMessage::create([
            'parent_id' => $validated['parent_id'] ?? null,
            'name' => trim((string) $validated['name']),
            'topic' => isset($validated['topic']) ? trim((string) $validated['topic']) : null,
            'message' => isset($validated['message']) ? trim((string) $validated['message']) : '',
            'attachment_path' => $attachmentPath,
            'attachment_mime' => $attachmentMime,
            'ip_address' => $request->ip(),
            'chat_token' => $token,
        ]);

        return response()->json([
            'message_id' => $message->id,
        ], 201);
    }

    public function update(Request $request, ForumMessage $message): JsonResponse
    {
        if (!$this->canManage($request, $message)) {
            return response()->json(['error' => 'Not allowed.'], 403);
        }

        if ($message->deleted_at) {
            return response()->json(['error' => 'Message already deleted.'], 422);
        }

        $validated = $request->validate([
            'topic' => ['nullable', 'string', 'max:100'],
            'message' => ['required', 'string', 'max:1200'],
        ]);

        $message->topic = isset($validated['topic']) ? trim((string) $validated['topic']) : null;
        $message->message = trim((string) $validated['message']);
        $message->edited_at = now();
        $message->save();

        return response()->json(['ok' => true]);
    }

    public function destroy(Request $request, ForumMessage $message): JsonResponse
    {
        if (!$this->canManage($request, $message)) {
            return response()->json(['error' => 'Not allowed.'], 403);
        }

        if ($message->attachment_path) {
            Storage::disk('public')->delete($message->attachment_path);
        }

        $message->delete();

        return response()->json(['ok' => true]);
    }

    public function destroyAll(Request $request): JsonResponse
    {
        $token = $this->resolveToken($request);

        if ($token === '') {
            return response()->json(['error' => 'Not allowed.'], 403);
        }

        $messages = ForumMessage::query()
            ->where('chat_token', $token)
            ->get(['id', 'attachment_path']);

        foreach ($messages as $message) {
            if ($message->attachment_path) {
                Storage::disk('public')->delete($message->attachment_path);
            }
        }

        ForumMessage::query()->where('chat_token', $token)->delete();

        return response()->json([
            'ok' => true,
            'deleted' => $messages->count(),
        ]);
    }

    public function destroyViaPost(Request $request, ForumMessage $message): JsonResponse
    {
        return $this->destroy($request, $message);
    }

    public function attachment(ForumMessage $message)
    {
        if (!$message->attachment_path || !Storage::disk('public')->exists($message->attachment_path)) {
            return response()->json(['error' => 'Attachment not found.'], 404);
        }

        $path = Storage::disk('public')->path($message->attachment_path);
        $mime = trim((string) ($message->attachment_mime ?: Storage::disk('public')->mimeType($message->attachment_path)));
        if ($mime === '') {
            $mime = 'application/octet-stream';
        }
        $safeName = basename((string) $message->attachment_path);

        $headers = [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . addslashes($safeName) . '"',
        ];

        return response()->file($path, $headers);
    }

    public function downloadAttachment(ForumMessage $message)
    {
        if (!$message->attachment_path || !Storage::disk('public')->exists($message->attachment_path)) {
            return response()->json(['error' => 'Attachment not found.'], 404);
        }

        $path = Storage::disk('public')->path($message->attachment_path);
        return response()->download($path, basename((string) $message->attachment_path));
    }

    public function react(Request $request, ForumMessage $message): JsonResponse
    {
        $token = $this->resolveToken($request);
        $validated = $request->validate([
            'reaction' => ['required', 'in:' . implode(',', self::REACTIONS)],
        ]);

        $reaction = (string) $validated['reaction'];

        $existing = ForumMessageReaction::query()
            ->where('forum_message_id', $message->id)
            ->where('chat_token', $token)
            ->where('reaction', $reaction)
            ->first();

        if ($existing) {
            $existing->delete();
        } else {
            ForumMessageReaction::create([
                'forum_message_id' => $message->id,
                'chat_token' => $token,
                'reaction' => $reaction,
            ]);
        }

        return response()->json(['ok' => true]);
    }

    public function heartbeat(Request $request): JsonResponse
    {
        $token = $this->resolveToken($request);
        $name = trim((string) $request->input('name', 'Guest'));
        $this->putPresence($token, $name);

        return response()->json(['ok' => true]);
    }

    public function presence(): JsonResponse
    {
        $users = $this->collectPresence();

        return response()->json([
            'online' => array_values($users),
            'count' => count($users),
        ]);
    }

    public function typing(Request $request): JsonResponse
    {
        $token = $this->resolveToken($request);
        $name = trim((string) $request->input('name', 'Guest'));
        Cache::put('forum_typing_user_' . $token, [
            'token' => $token,
            'name' => $name,
            'at' => now()->timestamp,
        ], now()->addSeconds(8));

        $registry = Cache::get('forum_typing_registry', []);
        $registry[$token] = now()->timestamp;
        Cache::put('forum_typing_registry', $registry, now()->addMinutes(5));

        return response()->json(['ok' => true]);
    }

    public function typingUsers(Request $request): JsonResponse
    {
        $self = $this->resolveToken($request);
        $users = $this->collectTypingUsers($self);

        return response()->json([
            'typing' => $users,
        ]);
    }

    private function resolveToken(Request $request): string
    {
        $header = trim((string) $request->header('X-Chat-Token', ''));
        if ($header !== '') {
            return Str::limit(preg_replace('/[^A-Za-z0-9_-]/', '', $header), 80, '');
        }

        $queryToken = trim((string) $request->query('token', ''));
        if ($queryToken !== '') {
            return Str::limit(preg_replace('/[^A-Za-z0-9_-]/', '', $queryToken), 80, '');
        }

        $fallback = sha1((string) $request->ip() . '|' . (string) $request->userAgent());
        return substr($fallback, 0, 40);
    }

    private function canManage(Request $request, ForumMessage $message): bool
    {
        $token = $this->resolveToken($request);
        return $token !== '' && (string) $message->chat_token === $token;
    }

    private function guardPostRate(Request $request, string $token): void
    {
        $key = 'forum_post_rate_' . sha1((string) $request->ip() . '|' . $token);
        $last = Cache::get($key);
        if (is_int($last) && now()->timestamp - $last < 2) {
            abort(429, 'You are posting too fast. Please wait briefly.');
        }

        Cache::put($key, now()->timestamp, now()->addSeconds(10));
    }

    private function putPresence(string $token, string $name): void
    {
        Cache::put('forum_presence_user_' . $token, [
            'token' => $token,
            'name' => $name,
            'at' => now()->timestamp,
        ], now()->addSeconds(45));

        $registry = Cache::get('forum_presence_registry', []);
        $registry[$token] = now()->timestamp;
        Cache::put('forum_presence_registry', $registry, now()->addMinutes(10));
    }

    private function collectPresence(): array
    {
        $registry = Cache::get('forum_presence_registry', []);
        $online = [];

        foreach ($registry as $token => $ts) {
            $payload = Cache::get('forum_presence_user_' . $token);
            if (!$payload || !is_array($payload)) {
                unset($registry[$token]);
                continue;
            }

            $online[$token] = [
                'name' => (string) ($payload['name'] ?? 'Guest'),
            ];
        }

        Cache::put('forum_presence_registry', $registry, now()->addMinutes(10));
        return $online;
    }

    private function collectTypingUsers(string $selfToken): array
    {
        $registry = Cache::get('forum_typing_registry', []);
        $users = [];

        foreach ($registry as $token => $ts) {
            $payload = Cache::get('forum_typing_user_' . $token);
            if (!$payload || !is_array($payload)) {
                unset($registry[$token]);
                continue;
            }

            if ($token === $selfToken) {
                continue;
            }

            $users[] = [
                'name' => (string) ($payload['name'] ?? 'Guest'),
            ];
        }

        Cache::put('forum_typing_registry', $registry, now()->addMinutes(5));
        return $users;
    }

    private function buildMessagesPayload(string $token)
    {
        $messages = ForumMessage::query()
            ->with(['parent:id,name,message', 'reactions:id,forum_message_id,chat_token,reaction'])
            ->latest('created_at')
            ->limit(220)
            ->get()
            ->sortBy('created_at')
            ->values();

        return $messages->map(function (ForumMessage $message) use ($token) {
            $reactionCounts = [];
            $myReactions = [];

            foreach ($message->reactions as $reaction) {
                $reactionCounts[$reaction->reaction] = ($reactionCounts[$reaction->reaction] ?? 0) + 1;
                if ($reaction->chat_token === $token) {
                    $myReactions[] = $reaction->reaction;
                }
            }

            return [
                'id' => $message->id,
                'name' => $message->name,
                'topic' => $message->topic,
                'message' => $message->message,
                'attachment_url' => $message->attachment_path ? route('forum.messages.attachment', ['message' => $message->id]) : null,
                'attachment_download_url' => $message->attachment_path ? route('forum.messages.attachment.download', ['message' => $message->id]) : null,
                'attachment_mime' => $message->attachment_mime,
                'attachment_name' => $message->attachment_path ? basename((string) $message->attachment_path) : null,
                'parent_id' => $message->parent_id,
                'parent' => $message->parent ? [
                    'id' => $message->parent->id,
                    'name' => $message->parent->name,
                    'message' => $message->parent->message,
                ] : null,
                'is_mine' => $token !== '' && $message->chat_token === $token,
                'is_deleted' => false,
                'edited_at' => $message->edited_at ? $message->edited_at->toIso8601String() : null,
                'created_at' => $message->created_at ? $message->created_at->toIso8601String() : null,
                'reactions' => $reactionCounts,
                'my_reactions' => array_values(array_unique($myReactions)),
            ];
        });
    }
}
