<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\AssessmentScore;
use App\Models\AssessmentSimulation;
use App\Models\Lesson;
use App\Models\ManagedStudent;
use App\Models\Module;
use App\Models\StudentScore;
use Illuminate\Support\Facades\DB;
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
            'simulations' => AssessmentSimulation::with('parts')->get(),
        ]));
    }

    public function gunParts()
    {
        $modules = Module::with(['lessons' => function ($q) {
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
        $assessmentResults = collect();
        $activityResults = collect();
        if ($student) {
            $allScores = StudentScore::where('student_id', $student->id)
                ->with('activity.lessonDetail.lesson.module')
                ->get();

            $latestPerModule = [];
            foreach ($allScores as $s) {
                $modKey = $s->activity?->lessonDetail?->lesson?->module?->module_key
                    ?? ($s->metadata['module_key'] ?? 'unknown');
                if (!isset($latestPerModule[$modKey]) || $s->created_at > $latestPerModule[$modKey]->created_at) {
                    $latestPerModule[$modKey] = $s;
                }
            }

            foreach ($modules as $mod) {
                $latest = $latestPerModule[$mod->module_key] ?? null;
                if ($latest) {
                    $pct = $latest->max_score > 0 ? round(((float) $latest->score / (float) $latest->max_score) * 100) : 0;
                    $assessmentResults[$mod->module_key] = [
                        'completed' => true,
                        'score' => (float) $latest->score,
                        'max_score' => (float) $latest->max_score,
                        'percentage' => $pct,
                        'label' => "{$latest->score}/{$latest->max_score} ({$pct}%)",
                    ];
                } else {
                    $assessmentResults[$mod->module_key] = [
                        'completed' => false,
                        'score' => 0,
                        'max_score' => 0,
                        'percentage' => 0,
                        'label' => 'Not taken',
                    ];
                }
            }

            // Module activity (assembly simulation) results
            $simAttempts = AssessmentSimulation::whereHas('score', function ($q) use ($student) {
                $q->where('student_id', $student->id);
            })->with('score')->get();

            foreach ($modules as $mod) {
                $modAttempts = $simAttempts->filter(fn ($sa) => ($sa->score->metadata['module_key'] ?? '') === $mod->module_key);
                $completedCount = $modAttempts->where('status', 'completed')->count();
                $passedCount = $modAttempts->where('passed', true)->count();
                $activityResults[$mod->module_key] = [
                    'completed' => $completedCount > 0,
                    'passed' => $passedCount > 0,
                    'count' => $completedCount,
                    'total' => $simAttempts->count(),
                    'label' => $completedCount > 0 ? ($passedCount > 0 ? 'Passed' : 'Attempted') : 'Not started',
                ];
            }
        }

        return view('Students.module-checkpoint-node', array_merge($this->studentViewData(), [
            'modules' => $modules,
            'activeModule' => $activeModule,
            'moduleKey' => $moduleKey,
            'student' => $student,
            'simulations' => AssessmentSimulation::with('parts')->get(),
            'assessmentResults' => $assessmentResults,
            'activityResults' => $activityResults,
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

        $student = Auth::guard('student')->user();
        if (! $student && Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user && $user->role === 'student') {
                $student = ManagedStudent::withArchived()->where('student_id_number', $user->email)->first();
            }
        }

        $scores = collect();
        $allRows = collect();
        $marksmanshipRows = collect();
        $assemblyRows = collect();
        if ($student) {
            $scores = StudentScore::where('student_id', $student->id)
                ->with(['activity.lessonDetail.lesson.module', 'activityScores', 'assessmentScore.marksmanshipSimulation.shotResults'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($score) {
                    $moduleKey = $score->activity?->lessonDetail?->lesson?->module?->module_key
                        ?? ($score->metadata['module_key'] ?? 'unknown');
                    $score->module_key = $moduleKey;

                    $module = Module::where('module_key', $moduleKey)->first();
                    $score->module_num = $module ? (int) str_replace('module-', '', $module->module_key) : 0;
                    $score->module_title = $module?->title ?? 'Unknown';
                    $score->module_id = $module?->id;

                    $score->enrichedAnswers = collect();
                    if ($score->activityScores->isNotEmpty() && $module) {
                        $moduleQuestions = Activity::whereHas('lessonDetail.lesson', function ($q) use ($module) {
                            $q->where('module_id', $module->id);
                        })->get()->keyBy('question_number');

                        $score->enrichedAnswers = $score->activityScores->map(function ($as) use ($moduleQuestions, $score) {
                            $q = $moduleQuestions->get($as->question_number);
                            return [
                                'question_number' => $as->question_number,
                                'question_text' => $q?->question_text ?? 'Unknown Question',
                                'options' => $q?->options ?? [],
                                'correct_answer' => $q?->correct_answer,
                                'correct_answer_text' => $q ? ($q->options[$q->correct_answer] ?? 'N/A') : 'N/A',
                                'selected_answer' => $as->selected_answer,
                                'selected_answer_text' => $as->selected_answer !== null && $q
                                    ? ($q->options[$as->selected_answer] ?? 'Invalid option')
                                    : 'No answer',
                                'is_correct' => $as->is_correct,
                                'score_id' => $score->id,
                                'module_title' => $score->module_title,
                                'module_key' => $score->module_key,
                                'recorded_at' => $score->recorded_at,
                                'score_value' => $score->score,
                                'max_score' => $score->max_score,
                            ];
                        });
                    }

                    return $score;
                });

            $allRows = $scores->flatMap(fn ($s) => $s->enrichedAnswers);

            $attemptIndex = [];
            foreach ($scores as $score) {
                $key = $score->module_key;
                $attemptIndex[$key] = ($attemptIndex[$key] ?? 0) + 1;
                $score->attempt_number = $attemptIndex[$key];
            }

            $scoreAttemptMap = $scores->mapWithKeys(fn ($s) => [$s->id => $s->attempt_number]);
            $totalPerModule = $scores->groupBy('module_key')->map->count();

            $allRows = $allRows->map(function ($row) use ($scoreAttemptMap, $totalPerModule) {
                $row['attempt_number'] = $scoreAttemptMap[$row['score_id']] ?? 1;
                $row['total_attempts'] = $totalPerModule[$row['module_key']] ?? 1;
                return $row;
            });

            // Marksmanship rows (module_key === 'final')
            $marksmanshipScores = $scores->filter(fn ($s) => $s->module_key === 'final');
            $mAttemptIdx = [];
            foreach ($marksmanshipScores as $s) {
                $mAttemptIdx[$s->id] = ($mAttemptIdx[$s->id] ?? 0) + 1;
            }
            $marksmanshipRows = $marksmanshipScores->map(function ($s) {
                $asmScore = $s->assessmentScore;
                $ms = $asmScore?->marksmanshipSimulation;
                $meta = $asmScore?->metadata ?? [];
                $sMeta = $s->metadata ?? [];
                return [
                    'score_id' => $s->id,
                    'recorded_at' => $s->recorded_at,
                    'score' => $s->score,
                    'max_score' => $s->max_score,
                    'accuracy' => $sMeta['accuracy'] ?? $meta['accuracy'] ?? null,
                    'weapon' => $sMeta['weapon'] ?? $meta['weapon'] ?? 'N/A',
                    'target_mode' => $sMeta['target_mode'] ?? $meta['target_mode'] ?? 'N/A',
                    'total_shots' => $sMeta['total_shots'] ?? $meta['total_shots'] ?? 0,
                    'hits' => $sMeta['hits'] ?? 0,
                    'bullseye_count' => $meta['bullseye_count'] ?? 0,
                    'alpha_count' => $meta['alpha_count'] ?? 0,
                    'bravo_count' => $meta['bravo_count'] ?? 0,
                    'charlie_count' => $meta['charlie_count'] ?? 0,
                    'delta_count' => $meta['delta_count'] ?? 0,
                    'miss_count' => $meta['miss_count'] ?? 0,
                    'passed' => $ms?->passed ?? false,
                    'sim_status' => $ms?->status ?? 'N/A',
                    'shotResults' => $ms?->shotResults ?? collect(),
                ];
            })->values();

            // Assembly rows (assessmentScore.score_type === 'assembly_disasembly')
            $assemblyScores = $scores->filter(fn ($s) => $s->assessmentScore && $s->assessmentScore->score_type === 'assembly_disasembly');
            $aAttemptIdx = [];
            foreach ($assemblyScores as $s) {
                $aAttemptIdx[$s->id] = ($aAttemptIdx[$s->id] ?? 0) + 1;
            }
            $assemblyRows = $assemblyScores->map(function ($s) use ($aAttemptIdx) {
                $meta = $s->assessmentScore->metadata ?? [];
                $sMeta = $s->metadata ?? [];
                return [
                    'score_id' => $s->id,
                    'attempt_number' => $aAttemptIdx[$s->id] ?? 1,
                    'recorded_at' => $s->recorded_at,
                    'score' => $s->score,
                    'max_score' => $s->max_score,
                    'passed' => $meta['passed'] ?? false,
                    'simulation_slug' => $sMeta['simulation_slug'] ?? $meta['simulation_slug'] ?? 'N/A',
                    'mode' => $sMeta['mode'] ?? $meta['mode'] ?? 'asm',
                    'wrong_attempts' => $meta['wrong_attempts'] ?? 0,
                    'total_parts' => $meta['total_parts'] ?? 0,
                    'parts_order' => $meta['parts_order'] ?? [],
                    'mistakes' => $meta['mistakes'] ?? [],
                    'part_attempts' => $meta['part_attempts'] ?? [],
                ];
            })->values();

            $data['total_scores_count'] = $scores->count();
            $data['latest_assessment_score'] = $scores->first();
        }

        $data['scores'] = $scores;
        $data['allRows'] = $allRows;
        $data['marksmanshipRows'] = $marksmanshipRows;
        $data['assemblyRows'] = $assemblyRows;

        return view('Students.Assessment-report', $data);
    }

    public function saveAssemblyScore(Request $request)
    {
        $data = $request->validate([
            'simulation_slug' => 'required|string',
            'mode' => 'required|in:asm,dis',
            'score' => 'required|numeric|min:0|max:100',
            'max_score' => 'required|numeric|min:1',
            'wrong_attempts' => 'required|integer|min:0',
            'passed' => 'required|boolean',
            'metadata' => 'nullable|array',
        ]);

        $student = Auth::guard('student')->user();
        if (! $student) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        DB::beginTransaction();
        try {
            $score = StudentScore::create([
                'student_id' => $student->id,
                'activity_id' => null,
                'score' => $data['score'],
                'max_score' => $data['max_score'],
                'recorded_at' => now(),
                'metadata' => [
                    'module_key' => 'module-2',
                    'activity_type' => 'assembly_disassembly',
                    'source' => 'assembly',
                    'simulation_slug' => $data['simulation_slug'],
                    'mode' => $data['mode'],
                    'wrong_attempts' => $data['wrong_attempts'],
                    'passed' => $data['passed'],
                ],
            ]);

            AssessmentScore::create([
                'score_id' => $score->id,
                'score_type' => 'assembly_disasembly',
                'metadata' => array_merge($data['metadata'] ?? [], [
                    'wrong_attempts' => $data['wrong_attempts'],
                    'passed' => $data['passed'],
                    'simulation_slug' => $data['simulation_slug'],
                    'mode' => $data['mode'],
                ]),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Assembly score saved successfully.',
                'data' => [
                    'score' => $data['score'],
                    'max_score' => $data['max_score'],
                    'passed' => $data['passed'],
                    'wrong_attempts' => $data['wrong_attempts'],
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to save score.'], 500);
        }
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
            'module_key' => ['required', 'string', 'exists:modules,module_key'],
            'score' => ['required', 'numeric', 'min:0'],
            'max_score' => ['required', 'numeric', 'min:1'],
            'metadata' => ['nullable', 'array'],
        ]);

        $student = Auth::guard('student')->user();
        if (!$student) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $score = StudentScore::create([
            'student_id' => $student->id,
            'activity_id' => null,
            'score' => $data['score'],
            'max_score' => $data['max_score'],
            'recorded_at' => now(),
            'metadata' => array_merge($data['metadata'] ?? [], [
                'module_key' => $data['module_key'],
            ]),
        ]);

        return response()->json([
            'message' => 'Score saved successfully.',
            'data' => $score,
        ]);
    }
}
