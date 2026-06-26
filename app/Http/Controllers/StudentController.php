<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\ManagedStudent;
use App\Models\StudentScore;
use App\Models\StudentTrainingSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function dashboard()
    {
        return view('Students.dashboard', $this->studentViewData());
    }

    public function firingRange()
    {
        return redirect()->route('student.dashboard');
    }

    public function assembly()
    {
        return view('Students.assembly', array_merge($this->studentViewData(), [
            'firearms' => \App\Models\Firearm::with('parts')->get(),
        ]));
    }

    public function gunParts()
    {
        $modules = \App\Models\Module::with(['lessons' => function ($q) {
            $q->orderBy('sort_order');
        }])->orderBy('sort_order')->get();

        $moduleKey = request()->query('module', 'module-1');
        if (!in_array($moduleKey, ['module-1', 'module-2', 'module-3'])) {
            $moduleKey = 'module-1';
        }

        $activeModule = $modules->firstWhere('module_key', $moduleKey);

        $student = Auth::guard('student')->user();
        if (!$student && Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user && $user->role === 'student') {
                $student = ManagedStudent::withArchived()->where('student_id_number', $user->email)->first();
            }
        }
        return view('Students.module-checkpoint-node', array_merge($this->studentViewData(), [
            'modules' => $modules,
            'activeModule' => $activeModule,
            'moduleKey' => $moduleKey,
            'student' => $student,
        ]));
    }

    public function progress()
    {
        return view('Students.progress', $this->studentViewData());
    }

    public function leaderboard()
    {
        return view('Students.leaderboard', $this->studentViewData());
    }

    public function reports()
    {
        $data = $this->studentViewData();

        $studentIdNumber = $data['selectedStudent']['student_id_number'] ?? null;

        $scores = collect();
        if ($student = Auth::guard('student')->user()) {
            $scores = StudentScore::where('student_id', $student->id)->orderBy('recorded_at', 'desc')->get();
        }

        return view('Students.Assessment-report', array_merge($data, [
            'scores' => $scores,
        ]));
    }

    private function studentViewData(): array
    {
        $student = Auth::guard('student')->user();

        if (! $student && Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();

            if ($user && $user->role === 'student') {
                $student = ManagedStudent::withArchived()->where('student_id_number', $user->email)->first();
            }
        }

        $name = $student?->full_name ?? Auth::guard('web')->user()?->name ?? 'Student';
        $parts = preg_split('/\s+/', trim($name)) ?: [];

        return [
            'name' => $name,
            'firstName' => $parts[0] ?? $name,
            'lastName' => $parts[1] ?? '',
            'selectedStudent' => [
                'full_name' => $name,
                'student_id_number' => $student?->student_id_number ?? (Auth::guard('web')->user()?->email ?? ''),
                'section' => $student?->section ?? 'Student Portal',
            ],
        ];
    }

    public static function getAllStudents()
    {
        return ManagedStudent::get();
    }

    public static function getStudentById($id)
    {
        return ManagedStudent::find($id);
    }

    public static function createStudent($data)
    {
        return ManagedStudent::create($data);
    }

    public function updateStudent($id, $data)
    {
        $student = ManagedStudent::find($id);
        if ($student) {
            $student->update($data);
            return $student;
        }
        return null;
    }

    public function deleteStudent($id)
    {
        $student = ManagedStudent::find($id);
        if ($student) {
            $student->delete();
            return true;
        }
        return false;
    }

    public function saveAssessmentScore(Request $request)
    {
        $data = $request->validate([
            'module_key' => ['required', 'string', 'max:80'],
            'student_id' => ['nullable', 'exists:students,id'],
            'score' => ['required', 'numeric', 'min:0'],
            'max_score' => ['required', 'numeric', 'min:1'],
            'metadata' => ['nullable', 'array'],
        ]);

        $student = Auth::guard('student')->user();
        $user = Auth::guard('web')->user();

        $score = StudentScore::create([
            'student_id' => $data['student_id'] ?? $student?->id,
            'recorded_by_user_id' => $user?->id,
            'module_key' => $data['module_key'],
            'score' => $data['score'],
            'max_score' => $data['max_score'],
            'recorded_at' => now(),
            'metadata' => $data['metadata'] ?? null,
        ]);

        return response()->json([
            'message' => 'Score saved successfully.',
            'data' => $score,
        ]);
    }

    public function updateProgress(Request $request)
    {
        $request->validate([
            'module_key' => ['required', 'string', 'max:80'],
            'lesson_key' => ['nullable', 'string', 'max:80'],
            'current_page' => ['required', 'integer', 'min:0'],
            'total_pages' => ['required', 'integer', 'min:1'],
        ]);

        $user = Auth::guard('web')->user();
        if (!$user || $user->role !== 'student') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $student = ManagedStudent::withArchived()->where('student_id_number', $user->email)->first();
        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        $session = StudentTrainingSession::where('student_id', $student->id)
            ->where('module_key', $request->module_key)
            ->latest('started_at')
            ->first();

        if (!$session) {
            $session = StudentTrainingSession::create([
                'student_id' => $student->id,
                'instructor_user_id' => $student->instructor_user_id,
                'module_key' => $request->module_key,
                'session_type' => $request->module_key,
                'status' => 'active',
                'started_at' => now(),
                'metadata' => [],
            ]);
        }

        $metadata = $session->metadata ?? [];
        $metadata['current_lesson'] = $request->lesson_key;
        $metadata['current_page_index'] = (int) $request->current_page;
        $metadata['total_pages'] = (int) $request->total_pages;
        $session->update(['metadata' => $metadata]);

        $student->update([
            'current_progress' => [
                'module_key' => $request->module_key,
                'lesson_key' => $request->lesson_key,
                'page_index' => (int) $request->current_page,
                'total_pages' => (int) $request->total_pages,
                'activity_type' => 'viewing_lesson',
            ],
        ]);

        return response()->json(['success' => true]);
    }
}
