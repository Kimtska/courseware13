<?php

namespace App\Http\Controllers;

use App\Models\ManagedStudent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class LessonActivityController extends Controller
{
    private const CACHE_TTL_SECONDS = 60;
    private const STALE_AFTER_SECONDS = 30;
    private const LESSON_KEY = 'gun-parts-presentation';

    public function index(Request $request)
    {
        $user = Auth::user();
        abort_unless($user && in_array($user->role, ['instructor', 'department_head'], true), 403);

        $active = $this->activeStudentsFor(self::LESSON_KEY);

        return view('Instructor.lesson-activity', [
            'moduleKey' => 'module-1',
            'moduleTitle' => 'Lesson Activity',
            'moduleDescription' => 'Students currently opening the gun parts lesson.',
            'lessonKey' => self::LESSON_KEY,
            'activeStudents' => $active,
        ]);
    }

    public function activeStudents(Request $request): JsonResponse
    {
        $user = Auth::user();
        abort_unless($user && in_array($user->role, ['instructor', 'department_head'], true), 403);

        $lessonKey = $request->query('lesson', self::LESSON_KEY);
        $students = $this->activeStudentsFor($lessonKey);

        return response()->json([
            'lesson' => $lessonKey,
            'count' => count($students),
            'students' => $students,
            'server_time' => time(),
        ]);
    }

    public function activeStudentsApi(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            $student = Auth::guard('student')->user();
            abort_unless($student, 401);
        }

        $lessonKey = $request->query('lesson', self::LESSON_KEY);
        $students = $this->activeStudentsFor($lessonKey);

        return response()->json([
            'lesson' => $lessonKey,
            'count' => count($students),
            'students' => $students,
            'server_time' => time(),
        ]);
    }

    public function heartbeat(Request $request): JsonResponse
    {
        $student = Auth::guard('student')->user();
        if (! $student && Auth::guard('web')->check()) {
            $authed = Auth::guard('web')->user();
            if ($authed && $authed->role === 'student') {
                $student = ManagedStudent::withArchived()
                    ->where('student_id_number', $authed->email)
                    ->first();
            }
        }

        if (! $student) {
            return response()->json(['ok' => false, 'reason' => 'unauthenticated'], 401);
        }

        $data = $request->validate([
            'lesson' => ['required', 'string', 'max:100'],
            'current_page' => ['nullable', 'integer', 'min:0', 'max:99'],
        ]);

        $lessonKey = $data['lesson'];
        $cacheKey = $this->cacheKey($lessonKey);
        $now = time();
        $active = Cache::get($cacheKey, []);

        $studentIdNumber = $student->student_id_number ?? (Auth::guard('web')->user()?->email ?? '');
        $currentPage = (int) ($data['current_page'] ?? 0);

        $active[$studentIdNumber] = [
            'student_id' => $studentIdNumber,
            'full_name' => $student->full_name ?? ($student->first_name . ' ' . $student->last_name),
            'first_name' => $student->first_name ?? null,
            'last_name' => $student->last_name ?? null,
            'course' => $student->course ?? '—',
            'year_level' => $student->year_level ?? '—',
            'section' => $student->section ?? '—',
            'current_page' => $currentPage,
            'last_active_at' => $now,
        ];

        foreach ($active as $sid => $payload) {
            if ($now - (int) ($payload['last_active_at'] ?? 0) > self::STALE_AFTER_SECONDS) {
                unset($active[$sid]);
            }
        }

        Cache::put($cacheKey, $active, self::CACHE_TTL_SECONDS);

        return response()->json([
            'ok' => true,
            'lesson' => $lessonKey,
            'student_id' => $studentIdNumber,
            'current_page' => $currentPage,
            'server_time' => $now,
        ]);
    }

    public function leave(Request $request): JsonResponse
    {
        $student = Auth::guard('student')->user();
        if (! $student && Auth::guard('web')->check()) {
            $authed = Auth::guard('web')->user();
            if ($authed && $authed->role === 'student') {
                $student = ManagedStudent::withArchived()
                    ->where('student_id_number', $authed->email)
                    ->first();
            }
        }

        if (! $student) {
            return response()->json(['ok' => false], 401);
        }

        $data = $request->validate([
            'lesson' => ['required', 'string', 'max:100'],
        ]);

        $cacheKey = $this->cacheKey($data['lesson']);
        $active = Cache::get($cacheKey, []);
        $studentIdNumber = $student->student_id_number ?? (Auth::guard('web')->user()?->email ?? '');
        unset($active[$studentIdNumber]);
        Cache::put($cacheKey, $active, self::CACHE_TTL_SECONDS);

        return response()->json(['ok' => true]);
    }

    private function activeStudentsFor(string $lessonKey): array
    {
        $cacheKey = $this->cacheKey($lessonKey);
        $active = Cache::get($cacheKey, []);
        $now = time();

        $list = [];
        foreach ($active as $sid => $payload) {
            $lastActive = (int) ($payload['last_active_at'] ?? 0);
            if ($now - $lastActive > self::STALE_AFTER_SECONDS) {
                continue;
            }
            $payload['idle_seconds'] = max(0, $now - $lastActive);
            $list[] = $payload;
        }

        usort($list, function ($a, $b) {
            return ($a['last_active_at'] ?? 0) <=> ($b['last_active_at'] ?? 0);
        });

        return $list;
    }

    private function cacheKey(string $lessonKey): string
    {
        return 'lesson_presence:' . preg_replace('/[^a-z0-9_\-]/i', '', $lessonKey);
    }
}
