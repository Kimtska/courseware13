<?php

namespace App\Http\Controllers;

use App\Models\EnrollmentImportBatch;
use App\Models\ManagedStudent;
use App\Models\StudentProfile;
use App\Models\StudentActivityLog;
use App\Models\StudentTrainingSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class InstructorStudentManagementController extends Controller
{
    private const DEFAULT_STUDENT_PASSWORD = 'Password123!';

    private function currentUser()
    {
        $user = Auth::user();

        abort_unless($user && in_array($user->role, ['instructor', 'department_head'], true), 403);

        return $user;
    }

    public function index(Request $request)
    {
        $user = $this->currentUser();
        $students = $this->queryStudents($user, $request)->paginate(12)->withQueryString();
        $archivedStudents = $user->role === 'department_head' && Schema::hasTable('students')
            ? ManagedStudent::withArchived()->archived()->latest()->take(10)->get()
            : collect();

        $sections = $this->availableSections($user);
        $totalStudents = $this->totalActiveStudents($user);

        return view('Instructor.manage-students', [
            'students' => $students,
            'archivedStudents' => $archivedStudents,
            'showArchivedStudents' => $user->role === 'department_head',
            'filters' => [
                'q' => $request->string('q')->toString(),
                'section' => $request->string('section')->toString(),
                'enrollment_status' => $request->string('enrollment_status')->toString(),
                'activity_status' => $request->string('activity_status')->toString(),
            ],
            'sections' => $sections,
            'totalStudents' => $totalStudents,
            'summary' => session('student_import_summary'),
            'importBatch' => session('student_import_batch'),
        ]);
    }

    private function availableSections($user)
    {
        if (!Schema::hasTable('students')) {
            return [];
        }

        $query = ManagedStudent::query()
            ->whereNotNull('section')
            ->where('section', '!=', '');

        if ($user->role === 'instructor') {
            $query->where('instructor_user_id', $user->id);
        }

        return $query
            ->select('section')
            ->distinct()
            ->orderBy('section')
            ->pluck('section')
            ->all();
    }

    private function totalActiveStudents($user)
    {
        if (!Schema::hasTable('students')) {
            return 0;
        }

        $query = ManagedStudent::query()->active();

        if ($user->role === 'instructor') {
            $query->where('instructor_user_id', $user->id);
        }

        return $query->count();
    }

    public function manageMarksmanship(Request $request)
    {
        $user = $this->currentUser();

        if (Schema::hasTable('students')) {
            $students = ManagedStudent::query()
                ->whereHas('trainingSessions', function ($query) {
                    $query->where('module_key', 'module-1')
                        ->where('status', 'completed');
                })
                ->when($user->role === 'instructor', fn ($query) => $query->where('instructor_user_id', $user->id))
                ->latest('updated_at')
                ->paginate(12)
                ->withQueryString();
        } else {
            $students = StudentProfile::query()
                ->where('verification_status', 'verified')
                ->when($user->role === 'instructor', fn ($query) => $query->where('instructor_id', $user->id))
                ->latest('created_at')
                ->paginate(12)
                ->withQueryString();
        }

        return view('Instructor.manage-marksmanship', [
            'students' => $students,
        ]);
    }

    public function store(Request $request)
    {
        $user = $this->currentUser();

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:80'],
            'middle_name' => ['nullable', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'student_id_number' => ['required', 'string', 'max:60', 'unique:students,student_id_number'],
            'password' => ['nullable', 'string', 'min:8', 'max:255'],
        ]);

        $fullName = trim(implode(' ', array_filter([
            $data['first_name'],
            $data['middle_name'] ?? null,
            $data['last_name'],
        ], fn ($value) => filled($value))));

        $plainPassword = $data['password'] ?? self::DEFAULT_STUDENT_PASSWORD;

        DB::transaction(function () use ($user, $data, $fullName, $plainPassword) {
            ManagedStudent::create([
                'instructor_user_id' => $user->id,
                'student_id_number' => $data['student_id_number'],
                'password' => Hash::make($plainPassword),
                'status' => 'active',
                'full_name' => $fullName,
                'course' => null,
                'year_level' => null,
                'section' => null,
                'enrollment_status' => 'verified_enrolled',
                'module_access_status' => 'ready_for_training',
                'current_activity_status' => 'inactive',
                'verified_at' => now(),
            ]);
        });

        return redirect()
            ->route('instructor.manage-students')
            ->with('status', 'Student added successfully.')
            ->with('student_import_summary', null);
    }

    public function bulkImport(Request $request)
    {
        $user = $this->currentUser();

        if (! Schema::hasTable('students')) {
            return back()->withErrors(['import_file' => 'Run the student management migration before importing enrolled lists.'])->withInput();
        }

        $data = $request->validate([
            'import_file' => ['required', 'file', 'max:10240'],
        ]);

        $file = $request->file('import_file');
        $ext = strtolower($file->getClientOriginalExtension());

        if (!in_array($ext, ['csv', 'xlsx'], true)) {
            return back()->withErrors(['import_file' => 'Only CSV and XLSX files are supported in this build.'])->withInput();
        }

        $rows = $ext === 'csv' ? $this->parseCsvFile($file->getRealPath()) : $this->parseXlsxFile($file->getRealPath());

        $summary = [
            'total_uploaded' => count($rows),
            'successfully_imported' => 0,
            'duplicate_records' => 0,
            'invalid_entries' => 0,
            'details' => [],
        ];

        $existingIds = Schema::hasTable('students')
            ? ManagedStudent::where('instructor_user_id', $user->id)->pluck('student_id_number')->map(fn ($value) => Str::lower(trim($value)))->all()
            : [];

        $seenIds = [];

        DB::transaction(function () use ($rows, $user, &$summary, &$existingIds, &$seenIds, $file, $ext) {
            foreach ($rows as $index => $row) {
                $normalized = $this->normalizeImportRow($row);

                if (!$normalized['student_id_number'] || !$normalized['full_name']) {
                    $summary['invalid_entries']++;
                    continue;
                }

                $studentIdKey = Str::lower($normalized['student_id_number']);

                if (in_array($studentIdKey, $existingIds, true) || in_array($studentIdKey, $seenIds, true)) {
                    $summary['duplicate_records']++;
                    continue;
                }

                $seenIds[] = $studentIdKey;

                ManagedStudent::create([
                    'instructor_user_id' => $user->id,
                    'student_id_number' => $normalized['student_id_number'],
                    'password' => Hash::make('password'),
                    'status' => 'active',
                    'full_name' => $normalized['full_name'],
                    'course' => $normalized['course'],
                    'year_level' => $normalized['year_level'],
                    'section' => $normalized['section'],
                    'enrollment_status' => 'verified_enrolled',
                    'module_access_status' => 'ready_for_training',
                    'current_activity_status' => 'inactive',
                    'verified_at' => now(),
                    'metadata' => $normalized['metadata'],
                ]);

                $summary['successfully_imported']++;
            }

            $status = $summary['duplicate_records'] || $summary['invalid_entries'] ? 'completed_with_errors' : 'completed';

            $batch = EnrollmentImportBatch::create([
                'instructor_user_id' => $user->id,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $ext,
                'total_uploaded' => $summary['total_uploaded'],
                'successfully_imported' => $summary['successfully_imported'],
                'duplicate_records' => $summary['duplicate_records'],
                'invalid_entries' => $summary['invalid_entries'],
                'status' => $status,
                'summary' => $summary,
            ]);

            session()->flash('student_import_summary', $summary);
            session()->flash('student_import_batch', $batch);
        });

        return redirect()->route('instructor.manage-students')->with('status', 'Enrollment list imported successfully.');
    }

    public function downloadTemplate()
    {
        $user = $this->currentUser();

        $headers = [
            'student_id_number', 'first_name', 'middle_name', 'last_name', 'full_name', 'section', 'gender', 'metadata'
        ];

        $filename = 'student_import_template.csv';

        $callback = function () use ($headers) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $headers);

            // sample row
            fputcsv($out, ['20260001', 'Juan', 'Dela', 'Cruz', 'Juan Dela Cruz', 'A', 'M', '']);

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
            'status' => $data['enrollment_status'] === 'archived' ? 'archived' : 'active',
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
            'status' => 'archived',
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
        $section = trim($request->string('section')->toString());
        $enrollmentStatus = trim($request->string('enrollment_status')->toString());
        $activityStatus = trim($request->string('activity_status')->toString());

        if (Schema::hasTable('students')) {
            return ManagedStudent::query()
                ->active()
                ->when($user->role === 'instructor', fn ($query) => $query->where('instructor_user_id', $user->id))
                ->when($search, fn ($query) => $query->where(function ($nested) use ($search) {
                    $nested->where('student_id_number', 'like', '%' . $search . '%')
                        ->orWhere('full_name', 'like', '%' . $search . '%');
                }))
                ->when($section, fn ($query) => $query->where('section', $section))
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
            $student = ManagedStudent::withArchived()->findOrFail($studentId);

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
            'course' => $normalized['course'] ?? $normalized['program'] ?? 'BSCRIM',
            'year_level' => $normalized['year_level'] ?? $normalized['year'] ?? '2nd',
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
                ->where('enrollment_status', 'verified_enrolled')
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

    public function selectStudent(Request $request)
    {
        $user = $this->currentUser();

        $data = $request->validate([
            'student_profile_id' => ['required', 'integer'],
        ]);

        $student = $this->findStudent($data['student_profile_id'], $user);

        if (Schema::hasTable('students')) {
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
        } else {
            $request->session()->put('student_portal.selected_student', [
                'id' => $student->id,
                'student_id_number' => $student->student_number,
                'full_name' => $student->full_name,
                'course' => null,
                'year_level' => $student->year_level,
                'section' => $student->section,
                'enrollment_status' => $student->verification_status,
            ]);
            $request->session()->put('student_portal.selected_name', $student->full_name);
        }

        return response()->json(['message' => 'Student selected for the active portal session.']);
    }

    public function bindPortalSession(Request $request, string $module)
    {
        $user = $this->currentUser();
        $selectedStudent = $request->session()->get('student_portal.selected_student');

        abort_unless($selectedStudent, 403, 'Select a verified student to continue.');

        if (Schema::hasTable('students')) {
            $session = StudentTrainingSession::create([
                'student_id' => $selectedStudent['id'],
                'instructor_user_id' => $user->id,
                'module_key' => $module,
                'session_type' => $module,
                'status' => 'active',
                'started_at' => now(),
                'metadata' => [
                    'portal_session' => true,
                ],
            ]);

            StudentActivityLog::create([
                'student_id' => $selectedStudent['id'],
                'student_training_session_id' => $session->id,
                'instructor_user_id' => $user->id,
                'module_key' => $module,
                'activity_type' => 'portal_bound',
                'activity_status' => 'active',
                'payload' => ['student_id_number' => $selectedStudent['student_id_number'] ?? null],
            ]);
        }

        return response()->json(['message' => 'Portal session bound.']);
    }
}