<?php

namespace App\Http\Controllers;

use App\Models\ManagedStudent;
use App\Models\ModuleAccessControl;
use App\Models\ModuleParticipationLog;
use App\Models\StudentActivityLog;
use App\Models\StudentAttendance;
use App\Models\StudentProfile;
use App\Models\StudentTrainingSession;
use App\Models\TrainingSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class InstructorStudentProfileController extends Controller
{
    private function currentUser()
    {
        $user = Auth::user();

        abort_unless($user && in_array($user->role, ['instructor', 'department_head'], true), 403);

        return $user;
    }

    public function index(): JsonResponse
    {
        $user = $this->currentUser();

        $profiles = StudentProfile::with(['instructor', 'verifier'])
            ->when($user->role === 'instructor', function ($query) use ($user) {
                $query->where('instructor_id', $user->id);
            })
            ->latest()
            ->get();

        return response()->json(['data' => $profiles]);
    }

    public function portal(Request $request)
    {
        $this->currentUser();

        return view('Instructor.manage-lessons', [
            'modules' => [
                [
                    'key' => 'module-1',
                    'title' => 'Courseware',
                    'description' => 'Instruction of guns and gun parts with descriptions.',
                    'route' => route('instructor.manage-lessons.module-1'),
                ],
            ],
        ]);
    }

    public function selectStudent(Request $request)
    {
        $user = $this->currentUser();

        $data = $request->validate([
            'student_profile_id' => ['required', 'integer'],
        ]);

        if (Schema::hasTable('students')) {
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
                'course' => $student->course,
                'year_level' => $student->year_level,
                'section' => $student->section,
                'enrollment_status' => $student->enrollment_status,
            ]);
            $request->session()->put('student_portal.selected_name', $student->full_name);

            return response()->json([
                'message' => 'Student selected for the active portal session.',
                'data' => $student,
            ]);
        }

        $student = StudentProfile::query()
            ->where('verification_status', 'verified')
            ->when($user->role === 'instructor', function ($query) use ($user) {
                $query->where('instructor_id', $user->id);
            })
            ->findOrFail($data['student_profile_id']);

        $request->session()->put('student_portal.selected_student', [
            'id' => $student->id,
            'student_number' => $student->student_number,
            'full_name' => $student->full_name,
            'year_level' => $student->year_level,
            'section' => $student->section,
            'verification_status' => $student->verification_status,
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

        $lesson = \App\Models\Lesson::where('key', 'gun-parts')
            ->with(['pages' => function ($query) {
                $query->orderBy('page_index');
            }])
            ->first();

        $lessonPages = $lesson?->pages ?? collect();

        return view('Instructor.courseware', [
            'moduleKey' => 'module-1',
            'moduleTitle' => 'Courseware',
            'moduleDescription' => 'Instruction of Guns and Gun Parts',
            'moduleState' => $moduleState,
            'lesson' => $lesson,
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
        ]);
    }

    public function moduleFour(Request $request)
    {
        $moduleState = $this->moduleAccessState('module-4');

        $weapon = $request->query('weapon', '9mm');
        $time = (int) $request->query('time', 30);
        $mode = $request->query('mode', 'steady');
        $studentId = $request->query('student_id', '');

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
        ]);

        $profile = \App\Models\StudentProfile::where('student_number', $data['student_id'])->first();

        if (!$profile) {
            return response()->json(['message' => 'Student profile not found.'], 404);
        }

        $session = \App\Models\TrainingSession::firstOrCreate(
            [
                'instructor_id' => $user->id,
                'module_key' => 'module-4',
                'status' => 'active',
            ],
            [
                'title' => 'Firing Range Session',
                'started_at' => now(),
                'metadata' => [
                    'weapon' => $data['weapon'] ?? null,
                    'time_limit' => $data['time_limit'] ?? null,
                    'target_mode' => $data['target_mode'] ?? null,
                ],
            ]
        );

        $score = \App\Models\StudentScore::create([
            'training_session_id' => $session->id,
            'student_profile_id' => $profile->id,
            'recorded_by_user_id' => $user->id,
            'module_key' => 'module-4',
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
            ],
        ]);

        return response()->json([
            'message' => 'Score saved successfully.',
            'data' => $score,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $user = $this->currentUser();

        $data = $request->validate([
            'instructor_id' => ['nullable', 'exists:users,id'],
            'student_number' => ['required', 'string', 'max:50', 'unique:student_profiles,student_number'],
            'school_name' => ['nullable', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:80'],
            'middle_name' => ['nullable', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'year_level' => ['nullable', 'string', 'max:20'],
            'section' => ['nullable', 'string', 'max:50'],
            'gender' => ['nullable', 'string', 'max:30'],
            'notes' => ['nullable', 'string'],
            'metadata' => ['nullable', 'array'],
        ]);

        $profile = StudentProfile::create([
            'instructor_id' => $user->role === 'instructor' ? $user->id : ($data['instructor_id'] ?? $user->id),
            'student_number' => $data['student_number'],
            'school_name' => $data['school_name'] ?? 'SPC School',
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'] ?? null,
            'last_name' => $data['last_name'],
            'year_level' => $data['year_level'] ?? null,
            'section' => $data['section'] ?? null,
            'gender' => $data['gender'] ?? null,
            'verification_status' => 'pending',
            'notes' => $data['notes'] ?? null,
            'metadata' => $data['metadata'] ?? null,
        ]);

        return response()->json([
            'message' => 'Student profile created for instructor-managed verification.',
            'data' => $profile,
        ], 201);
    }

    public function downloadTemplate()
    {
        $this->currentUser();

        $headers = [
            'student_id_number', 'first_name', 'middle_name', 'last_name', 'full_name', 'course', 'year_level', 'section', 'gender', 'metadata'
        ];

        $filename = 'student_import_template.csv';

        $callback = function () use ($headers) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $headers);
            fputcsv($out, ['20260001', 'Juan', 'Dela', 'Cruz', 'Juan Dela Cruz', 'BSCS', '1', 'A', 'M', '']);
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
        abort_unless(Schema::hasTable('students'), 422, 'Student edit requires the new managed-student table.');
        $student = $this->findStudent($studentId, $user);

        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'course' => ['nullable', 'string', 'max:120'],
            'year_level' => ['nullable', 'string', 'max:20'],
            'section' => ['nullable', 'string', 'max:50'],
            'enrollment_status' => ['required', 'in:verified_enrolled,pending,rejected,archived'],
            'module_access_status' => ['required', 'in:ready_for_training,locked,active_in_firing_range,completed_session,archived'],
            'current_activity_status' => ['required', 'in:inactive,active_in_firing_range,active_in_assembly,completed_session,archived'],
        ]);

        $student->update($data + [
            'archived_at' => $data['enrollment_status'] === 'archived' ? now() : null,
        ]);

        return back()->with('status', 'Student record updated.');
    }

    public function archive(int $studentId)
    {
        $user = $this->currentUser();
        abort_unless(Schema::hasTable('students'), 422, 'Student archive requires the new managed-student table.');
        $student = $this->findStudent($studentId, $user);

        $student->update([
            'enrollment_status' => 'archived',
            'module_access_status' => 'archived',
            'current_activity_status' => 'archived',
            'archived_at' => now(),
        ]);

        return back()->with('status', 'Student archived.');
    }

    private function queryStudents($user, Request $request)
    {
        $search = trim($request->string('q')->toString());
        $course = trim($request->string('course')->toString());
        $yearLevel = trim($request->string('year_level')->toString());
        $enrollmentStatus = trim($request->string('enrollment_status')->toString());
        $activityStatus = trim($request->string('activity_status')->toString());

        if (Schema::hasTable('students')) {
            return ManagedStudent::query()
                ->when($user->role === 'instructor', fn ($query) => $query->where('instructor_user_id', $user->id))
                ->when($search, fn ($query) => $query->where(function ($nested) use ($search) {
                    $nested->where('student_id_number', 'like', '%' . $search . '%')
                        ->orWhere('full_name', 'like', '%' . $search . '%');
                }))
                ->when($course, fn ($query) => $query->where('course', $course))
                ->when($yearLevel, fn ($query) => $query->where('year_level', $yearLevel))
                ->when($enrollmentStatus, fn ($query) => $query->where('enrollment_status', $enrollmentStatus))
                ->when($activityStatus, fn ($query) => $query->where('current_activity_status', $activityStatus))
                ->latest();
        }

        return StudentProfile::query()
            ->when($user->role === 'instructor', fn ($query) => $query->where('instructor_id', $user->id))
            ->when($search, fn ($query) => $query->where(function ($nested) use ($search) {
                $nested->where('student_number', 'like', '%' . $search . '%')
                    ->orWhere('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%');
            }))
            ->latest();
    }

    private function findStudent(int $studentId, $user)
    {
        if (Schema::hasTable('students')) {
            $student = ManagedStudent::findOrFail($studentId);

            if ($user->role === 'instructor' && $student->instructor_user_id !== $user->id) {
                abort(403);
            }

            return $student;
        }

        $student = StudentProfile::findOrFail($studentId);

        if ($user->role === 'instructor' && $student->instructor_id !== $user->id) {
            abort(403);
        }

        return $student;
    }

    private function normalizeImportRow(array $row): array
    {
        $normalized = [];

        foreach ($row as $key => $value) {
            $normalized[Str::snake(trim((string) $key))] = is_string($value) ? trim($value) : $value;
        }

        $studentId = $normalized['student_id_number'] ?? $normalized['student_id'] ?? $normalized['student_number'] ?? '';
        $fullName = $normalized['full_name'] ?? trim(implode(' ', array_filter([
            $normalized['first_name'] ?? '',
            $normalized['middle_name'] ?? '',
            $normalized['last_name'] ?? '',
        ])));

        return [
            'student_id_number' => $studentId,
            'full_name' => $fullName,
            'course' => $normalized['course'] ?? $normalized['program'] ?? null,
            'year_level' => $normalized['year_level'] ?? $normalized['year'] ?? null,
            'section' => $normalized['section'] ?? null,
            'metadata' => [
                'source_row' => $normalized,
            ],
        ];
    }

    private function parseCsvFile(string $path): array
    {
        $file = new \SplFileObject($path);
        $file->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY | \SplFileObject::DROP_NEW_LINE);

        $headers = [];
        $rows = [];

        foreach ($file as $index => $line) {
            if ($line === [null] || $line === false) {
                continue;
            }

            if ($index === 0) {
                $headers = array_map(fn ($header) => trim((string) $header), $line);
                $headers[0] = preg_replace('/^\xEF\xBB\xBF/', '', $headers[0]);
                continue;
            }

            $rows[] = array_combine($headers, $line) ?: [];
        }

        return $rows;
    }

    private function parseXlsxFile(string $path): array
    {
        $zip = new \ZipArchive();

        if ($zip->open($path) !== true) {
            return [];
        }

        $sharedStrings = [];
        $sharedXml = $zip->getFromName('xl/sharedStrings.xml');

        if ($sharedXml !== false) {
            $shared = simplexml_load_string($sharedXml);
            if ($shared && isset($shared->si)) {
                foreach ($shared->si as $item) {
                    $sharedStrings[] = trim((string) $item->t);
                }
            }
        }

        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();

        if ($sheetXml === false) {
            return [];
        }

        $sheet = simplexml_load_string($sheetXml);
        if (!$sheet || !isset($sheet->sheetData)) {
            return [];
        }

        $rows = [];
        $headers = [];

        foreach ($sheet->sheetData->row as $index => $row) {
            $cells = [];

            foreach ($row->c as $cell) {
                $ref = (string) $cell['r'];
                $column = preg_replace('/\d+/', '', $ref);
                $value = '';

                if ((string) $cell['t'] === 's') {
                    $value = $sharedStrings[(int) $cell->v] ?? '';
                } elseif ((string) $cell['t'] === 'inlineStr') {
                    $value = trim((string) $cell->is->t);
                } else {
                    $value = trim((string) $cell->v);
                }

                $cells[$column] = $value;
            }

            if ($index === 0) {
                $headers = $cells;
                continue;
            }

            $ordered = [];
            foreach ($headers as $column => $header) {
                $ordered[$header] = $cells[$column] ?? '';
            }

            $rows[] = $ordered;
        }

        return $rows;
    }

    public function search(Request $request)
    {
        $user = $this->currentUser();
        $term = trim($request->string('q')->toString());

        if (Schema::hasTable('students')) {
            $query = ManagedStudent::query()
                ->active()
                ->when($user->role === 'instructor', fn ($builder) => $builder->where('instructor_user_id', $user->id))
                ->when($term, fn ($builder) => $builder->where(function ($nested) use ($term) {
                    $nested->where('student_id_number', 'like', '%' . $term . '%')
                        ->orWhere('full_name', 'like', '%' . $term . '%');
                }))
                ->limit(10)
                ->get();

            return response()->json(['data' => $query]);
        }

        $profiles = StudentProfile::with(['instructor'])
            ->where('verification_status', 'verified')
            ->when($user->role === 'instructor', function ($query) use ($user) {
                $query->where('instructor_id', $user->id);
            })
            ->when($term, function ($query) use ($term) {
                $query->where(function ($nested) use ($term) {
                    $nested->where('student_number', 'like', '%' . $term . '%')
                        ->orWhere('first_name', 'like', '%' . $term . '%')
                        ->orWhere('middle_name', 'like', '%' . $term . '%')
                        ->orWhere('last_name', 'like', '%' . $term . '%');
                });
            })
            ->limit(10)
            ->get();

        return response()->json(['data' => $profiles]);
    }

    public function verify(StudentProfile $studentProfile): JsonResponse
    {
        $user = $this->currentUser();

        if ($user->role === 'instructor' && $studentProfile->instructor_id !== $user->id) {
            abort(403);
        }

        $studentProfile->update([
            'verification_status' => 'verified',
            'verified_by_user_id' => $user->id,
            'verified_at' => now(),
        ]);

        return response()->json([
            'message' => 'Student profile verified.',
            'data' => $studentProfile->fresh(['instructor', 'verifier']),
        ]);
    }

    public function startSession(Request $request, string $module): JsonResponse
    {
        $user = $this->currentUser();

        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'metadata' => ['nullable', 'array'],
        ]);

        $allowedModules = ['firing_range', 'assembly_disassembly', 'practical_examination'];

        abort_unless(in_array($module, $allowedModules, true), 422);

        $session = TrainingSession::create([
            'instructor_id' => $user->id,
            'module_key' => $module,
            'title' => $data['title'] ?? null,
            'status' => 'active',
            'started_at' => now(),
            'metadata' => $data['metadata'] ?? null,
        ]);

        return response()->json([
            'message' => 'Training session opened.',
            'data' => $session,
        ], 201);
    }

    public function unlockModule(Request $request, string $module)
    {
        $this->currentUser();
        abort_unless(in_array($module, ['module-1', 'module-3', 'module-4'], true), 422);

        $moduleState = $this->moduleAccessState($module);
        $user = $this->currentUser();
        $desiredUnlocked = ! $moduleState->is_unlocked;

        $moduleState->update([
            'is_unlocked' => $desiredUnlocked,
            'last_action_by_user_id' => $user->id,
            'locked_at' => $desiredUnlocked ? $moduleState->locked_at : now(),
            'unlocked_at' => $desiredUnlocked ? now() : $moduleState->unlocked_at,
        ]);

        if (Schema::hasTable('students')) {
            ManagedStudent::query()
                ->active()
                ->when($user->role === 'instructor', function ($query) use ($user) {
                    $query->where('instructor_user_id', $user->id);
                })
                ->update([
                    'module_access_status' => $desiredUnlocked ? 'ready_for_training' : 'locked',
                ]);
        }

        return back()->with('module_access_flash', [
            'type' => $desiredUnlocked ? 'success' : 'warning',
            'title' => $desiredUnlocked ? 'Module Unlocked' : 'Module Locked',
            'message' => $desiredUnlocked
                ? 'The module has been unlocked and students can now access it.'
                : 'The module has been locked and students can no longer access it.',
        ]);
    }

    private function moduleAccessState(string $module): ModuleAccessControl
    {
        return ModuleAccessControl::firstOrCreate(
            ['module_key' => $module],
            ['is_unlocked' => false]
        );
    }

    private function unlockStudentsForModule(string $module): void
    {
        $moduleState = $this->moduleAccessState($module);

        if (! $moduleState->is_unlocked || ! Schema::hasTable('students')) {
            return;
        }

        $user = $this->currentUser();

        ManagedStudent::query()
            ->active()
            ->when($user->role === 'instructor', function ($query) use ($user) {
                $query->where('instructor_user_id', $user->id);
            })
            ->where('module_access_status', 'locked')
            ->update([
                'module_access_status' => 'ready_for_training',
            ]);
    }

    public function attachStudent(Request $request, string $module, TrainingSession $trainingSession): JsonResponse
    {
        $user = $this->currentUser();

        abort_unless($trainingSession->module_key === $module, 422);

        if ($user->role === 'instructor' && $trainingSession->instructor_id !== $user->id) {
            abort(403);
        }

        $data = $request->validate([
            'student_profile_id' => ['required', 'exists:student_profiles,id'],
            'metadata' => ['nullable', 'array'],
        ]);

        $studentProfile = StudentProfile::query()
            ->when($user->role === 'instructor', function ($query) use ($user) {
                $query->where('instructor_id', $user->id);
            })
            ->findOrFail($data['student_profile_id']);

        $attendance = StudentAttendance::updateOrCreate(
            [
                'training_session_id' => $trainingSession->id,
                'student_profile_id' => $studentProfile->id,
            ],
            [
                'marked_by_user_id' => $user->id,
                'checked_in_at' => now(),
                'status' => 'attached',
                'metadata' => $data['metadata'] ?? null,
            ]
        );

        ModuleParticipationLog::create([
            'training_session_id' => $trainingSession->id,
            'student_profile_id' => $studentProfile->id,
            'recorded_by_user_id' => $user->id,
            'module_key' => $module,
            'event_type' => 'selected',
            'payload' => $data['metadata'] ?? ['source' => 'instructor_portal'],
        ]);

        return response()->json([
            'message' => 'Student attached to the active training session.',
            'data' => [
                'student_profile' => $studentProfile,
                'attendance' => $attendance,
            ],
        ]);
    }
}
