<?php

namespace App\Http\Controllers;

use App\Models\ManagedStudent;
use App\Models\MarksmanshipScore;
use App\Models\StudentScore;
use App\Models\StudentActivityLog;
use App\Models\StudentTrainingSession;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstructorStudentProfileController extends Controller
{
    private function currentUser()
    {
        $user = Auth::user();

        abort_unless($user && in_array($user->role, ['instructor', 'department_head'], true), 403);

        return $user;
    }

    public function portal(Request $request)
    {
        $user = $this->currentUser();

        $students = ManagedStudent::where('instructor_user_id', $user->id)
            ->with(['scores'])
            ->get();

        $studentReports = $students->map(function ($student) {
            $scores = $student->scores->keyBy('module_key');

            $module1Score = $scores->get('module-1');
            $module2Score = $scores->get('module-2');
            $module3Score = $scores->get('module-3');
            $marksmanshipScore = $scores->get('final');

            return [
                'student' => $student,
                'module_1' => $module1Score ? [
                    'score' => $module1Score->score,
                    'max_score' => $module1Score->max_score,
                    'percentage' => $module1Score->max_score > 0 ? round(($module1Score->score / $module1Score->max_score) * 100) : 0,
                ] : null,
                'module_2' => $module2Score ? [
                    'score' => $module2Score->score,
                    'max_score' => $module2Score->max_score,
                    'percentage' => $module2Score->max_score > 0 ? round(($module2Score->score / $module2Score->max_score) * 100) : 0,
                ] : null,
                'module_3' => $module3Score ? [
                    'score' => $module3Score->score,
                    'max_score' => $module3Score->max_score,
                    'percentage' => $module3Score->max_score > 0 ? round(($module3Score->score / $module3Score->max_score) * 100) : 0,
                ] : null,
                'marksmanship' => $marksmanshipScore ? [
                    'score' => $marksmanshipScore->score,
                    'max_score' => $marksmanshipScore->max_score,
                    'percentage' => $marksmanshipScore->max_score > 0 ? round(($marksmanshipScore->score / $marksmanshipScore->max_score) * 100) : 0,
                ] : null,
            ];
        });

        $leaderboard = $studentReports
            ->filter(fn($r) => $r['module_1'] !== null && $r['module_2'] !== null && $r['module_3'] !== null && $r['marksmanship'] !== null)
            ->map(function ($r) {
                $avg = round(($r['module_1']['percentage'] + $r['module_2']['percentage'] + $r['module_3']['percentage'] + $r['marksmanship']['percentage']) / 4);
                return array_merge($r, ['average' => $avg]);
            })
            ->sortByDesc('average')
            ->take(10)
            ->values();

        return view('Instructor.manage-module', [
            'modules' => [
                [
                    'key' => 'module-1',
                    'title' => 'Courseware',
                    'description' => 'Instruction of guns and gun parts with descriptions.',
                    'route' => route('instructor.manage-module.module-1'),
                ],
            ],
            'studentReports' => $studentReports,
            'leaderboard' => $leaderboard,
            'students' => $students,
        ]);
    }

    public function selectStudent(Request $request)
    {
        $user = $this->currentUser();

        $data = $request->validate([
            'student_profile_id' => ['required', 'integer'],
        ]);

        $student = ManagedStudent::query()
            ->active()
            ->when($user->role === 'instructor', function ($query) use ($user) {
                $query->where('instructor_user_id', $user->id);
            })
            ->findOrFail($data['student_profile_id']);

        $request->session()->put('student_portal.selected_student', [
            'id' => $student->id,
            'student_id_number' => $student->student_id_number,
            'full_name' => $student->full_name,
            'section' => $student->section,
        ]);
        $request->session()->put('student_portal.selected_name', $student->full_name);

        return response()->json([
            'message' => 'Student selected for the active portal session.',
            'data' => $student,
        ]);
    }

    public function moduleOne(Request $request)
    {
        $moduleState = $this->moduleAccessState('module-1');

        $module = \App\Models\Module::where('module_key', 'module-1')
            ->with(['lessons' => function ($q) {
                $q->orderBy('sort_order');
            }, 'lessons.pages'])
            ->first();

        $lessonPages = $module?->lessons->flatMap(function ($l) {
            return $l->pages;
        }) ?? collect();

        return view('Instructor.courseware', [
            'moduleKey' => 'module-1',
            'moduleTitle' => $module?->title ?? 'Courseware',
            'moduleDescription' => $module?->description ?? 'Instruction of Guns and Gun Parts',
            'moduleState' => $moduleState,
            'module' => $module,
            'lessonPages' => $lessonPages,
        ]);
    }

    public function moduleThree(Request $request)
    {
        $moduleState = $this->moduleAccessState('module-3');

        return view('Instructor.assembly-dissasemble', [
            'moduleKey' => 'module-3',
            'moduleTitle' => 'Assembly Dissasemble',
            'moduleDescription' => 'Drag-and-drop assembly and disassembly snapping activity.',
            'moduleState' => $moduleState,
            'firearms' => \App\Models\Firearm::with('parts')->get(),
        ]);
    }

    public function moduleFour(Request $request)
    {
        $moduleState = $this->moduleAccessState('module-4');

        $weapon = $request->query('weapon', '9mm');
        $time = (int) $request->query('time', 30);
        $mode = $request->query('mode', 'steady');
        $studentId = $request->query('student_id', '');
        $maxShots = (int) $request->query('max_shots', 0);

        $weaponCycle = ['9mm', '.45'];
        $modeCycle = ['steady', 'sideways'];

        if ($weapon === 'all' || strpos($weapon, ',') !== false) {
            $items = array_filter(array_map('trim', explode(',', $weapon)));
            $items = array_values(array_intersect($items, $weaponCycle));
            if (!empty($items)) {
                $seed = $studentId !== '' ? (int) (hexdec(substr(md5($studentId), 0, 4)) ?: 0) : (int) (time() / 60);
                $weapon = $items[$seed % count($items)];
            } else {
                $weapon = '9mm';
            }
        } elseif (!in_array($weapon, ['9mm', '.45', '38'], true)) {
            $weapon = '9mm';
        }

        if ($mode === 'all' || strpos($mode, ',') !== false) {
            $items = array_filter(array_map('trim', explode(',', $mode)));
            $items = array_values(array_intersect($items, $modeCycle));
            if (!empty($items)) {
                $seed = $studentId !== '' ? (int) (hexdec(substr(md5($studentId), 0, 4)) ?: 0) : (int) (time() / 60);
                $mode = $items[$seed % count($items)];
            } else {
                $mode = 'steady';
            }
        } elseif (!in_array($mode, ['steady', 'sideways', 'around'], true)) {
            $mode = 'steady';
        }

        $time = max(5, min(999, $time));

        return view('Instructor.firing-range', [
            'moduleKey' => 'module-4',
            'moduleTitle' => 'Firing Range',
            'moduleDescription' => 'Firing range test and scoring interface.',
            'moduleState' => $moduleState,
            'weapon' => $weapon,
            'time' => $time,
            'mode' => $mode,
            'studentId' => $studentId,
            'maxShots' => $maxShots,
        ]);
    }

    public function saveFiringRangeScore(Request $request)
    {
        $user = $this->currentUser();

        $data = $request->validate([
            'student_id' => ['required', 'string'],
            'score' => ['required', 'numeric', 'min:0'],
            'max_score' => ['required', 'numeric', 'min:0'],
            'accuracy' => ['nullable', 'numeric'],
            'bullseyes' => ['nullable', 'integer', 'min:0'],
            'total_shots' => ['nullable', 'integer', 'min:0'],
            'hits' => ['nullable', 'integer', 'min:0'],
            'weapon' => ['nullable', 'string'],
            'time_limit' => ['nullable', 'integer'],
            'target_mode' => ['nullable', 'string'],
            'alpha_count' => ['nullable', 'integer', 'min:0'],
            'bravo_count' => ['nullable', 'integer', 'min:0'],
            'charlie_count' => ['nullable', 'integer', 'min:0'],
            'delta_count' => ['nullable', 'integer', 'min:0'],
            'miss_count' => ['nullable', 'integer', 'min:0'],
            'max_shots' => ['nullable', 'integer', 'min:0'],
            'started_at' => ['nullable', 'string'],
            'completed_at' => ['nullable', 'string'],
        ]);

        $student = ManagedStudent::where('student_id_number', $data['student_id'])->first();

        if (!$student) {
            return response()->json(['message' => 'Student not found.'], 404);
        }

        $score = StudentScore::create([
            'student_id' => $student->id,
            'recorded_by_user_id' => $user->id,
            'module_key' => 'final',
            'score' => $data['score'],
            'max_score' => $data['max_score'],
            'recorded_at' => now(),
            'metadata' => [
                'accuracy' => $data['accuracy'] ?? null,
                'bullseyes' => $data['bullseyes'] ?? null,
                'total_shots' => $data['total_shots'] ?? null,
                'hits' => $data['hits'] ?? null,
                'weapon' => $data['weapon'] ?? null,
                'time_limit' => $data['time_limit'] ?? null,
                'target_mode' => $data['target_mode'] ?? null,
                'alpha_count' => $data['alpha_count'] ?? null,
                'bravo_count' => $data['bravo_count'] ?? null,
                'charlie_count' => $data['charlie_count'] ?? null,
                'delta_count' => $data['delta_count'] ?? null,
                'miss_count' => $data['miss_count'] ?? null,
                'max_shots' => $data['max_shots'] ?? null,
            ],
        ]);

        MarksmanshipScore::create([
            'score_id' => $score->id,
            'student_id' => $student->id,
            'instructor_id' => $user->id,
            'weapon' => $data['weapon'] ?? null,
            'time_limit' => $data['time_limit'] ?? null,
            'target_mode' => $data['target_mode'] ?? null,
            'total_shots' => $data['total_shots'] ?? 0,
            'max_shots' => $data['max_shots'] ?? 0,
            'bullseye_count' => $data['bullseyes'] ?? 0,
            'alpha_count' => $data['alpha_count'] ?? 0,
            'bravo_count' => $data['bravo_count'] ?? 0,
            'charlie_count' => $data['charlie_count'] ?? 0,
            'delta_count' => $data['delta_count'] ?? 0,
            'miss_count' => $data['miss_count'] ?? 0,
            'total_score' => $data['score'],
            'max_score' => $data['max_score'],
            'accuracy' => $data['accuracy'] ?? null,
            'started_at' => $data['started_at'] ?? null,
            'completed_at' => $data['completed_at'] ?? null,
        ]);

        return response()->json([
            'message' => 'Score saved successfully.',
            'data' => $score,
        ]);
    }

    public function downloadTemplate()
    {
        $this->currentUser();

        $headers = [
            'student_id_number', 'first_name', 'middle_name', 'last_name', 'section', 'metadata'
        ];

        $filename = 'student_import_template.csv';

        $callback = function () use ($headers) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $headers);
            fputcsv($out, ['20260001', 'Juan', 'Dela', 'Cruz', 'A', '']);
            fclose($out);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function update(Request $request, int $studentId)
    {
        $user = $this->currentUser();
        $student = ManagedStudent::findOrFail($studentId);

        if ($user->role === 'instructor' && $student->instructor_user_id !== $user->id) {
            abort(403);
        }

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:80'],
            'middle_name' => ['nullable', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'section' => ['nullable', 'string', 'max:50'],
        ]);

        $student->update($data);

        return back()->with('status', 'Student record updated.');
    }

    public function archive(int $studentId)
    {
        $user = $this->currentUser();
        $student = ManagedStudent::findOrFail($studentId);

        if ($user->role === 'instructor' && $student->instructor_user_id !== $user->id) {
            abort(403);
        }

        $student->update([
            'archived_at' => now(),
        ]);

        return back()->with('status', 'Student archived.');
    }

    private function moduleAccessState(string $moduleKey): array
    {
        return [
            'active' => 'enabled',
            'status' => 'active',
        ];
    }
}
