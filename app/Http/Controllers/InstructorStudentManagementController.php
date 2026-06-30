<?php

namespace App\Http\Controllers;

use App\Models\ManagedStudent;
use App\Models\StudentActivityLog;
use App\Models\StudentTrainingSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        $archivedStudents = $user->role === 'department_head'
            ? ManagedStudent::withArchived()->archived()->latest()->take(10)->get()
            : collect();

        $sections = $this->availableSections($user);

        $fiveMonthOld = ManagedStudent::withArchived()
            ->where('status', 'archived')
            ->when($user->role === 'instructor', fn ($q) => $q->where('instructor_user_id', $user->id))
            ->latest('archived_at')
            ->get();

        $students = $this->queryStudents($user, $request)
            ->paginate(6)
            ->withQueryString();

        $totalStudents = $this->totalActiveStudents($user);

        return view('Instructor.manage-students', [
            'students' => $students,
            'archivedStudents' => $archivedStudents,
            'showArchivedStudents' => $user->role === 'department_head',
            'filters' => [
                'q' => $request->string('q')->toString(),
                'section' => $request->string('section')->toString(),
            ],
            'sections' => $sections,
            'totalStudents' => $totalStudents,
            'fiveMonthOld' => $fiveMonthOld,
            'summary' => session('student_import_summary'),
            'importBatch' => session('student_import_batch'),
        ]);
    }

    private function availableSections($user)
    {
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
        $query = ManagedStudent::query()->active();

        if ($user->role === 'instructor') {
            $query->where('instructor_user_id', $user->id);
        }

        return $query->count();
    }

    public function manageMarksmanship(Request $request)
    {
        $user = $this->currentUser();

        $students = ManagedStudent::query()
            ->whereHas('modules', function ($query) {
                $query->where('module_key', 'module-1')
                    ->where('status', 'completed');
            })
            ->when($user->role === 'instructor', fn ($query) => $query->where('instructor_user_id', $user->id))
            ->latest('updated_at')
            ->paginate(12)
            ->withQueryString();

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
            'section' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
        ]);

        $plainPassword = $data['password'] ?? self::DEFAULT_STUDENT_PASSWORD;

        DB::transaction(function () use ($user, $data, $plainPassword) {
            ManagedStudent::create([
                'instructor_user_id' => $user->id,
                'student_id_number' => $data['student_id_number'],
                'password' => Hash::make($plainPassword),
                'status' => 'active',
                'first_name' => $data['first_name'],
                'middle_name' => $data['middle_name'] ?? null,
                'last_name' => $data['last_name'],
                'section' => $data['section'] ?? null,
                'email' => $data['email'] ?? null,
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

        $existingIds = ManagedStudent::where('instructor_user_id', $user->id)->pluck('student_id_number')->map(fn ($value) => Str::lower(trim($value)))->all();

        $seenIds = [];

        DB::transaction(function () use ($rows, $user, &$summary, &$existingIds, &$seenIds, $file, $ext) {
            foreach ($rows as $index => $row) {
                $normalized = $this->normalizeImportRow($row);

                if (!$normalized['student_id_number'] || !$normalized['last_name']) {
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
                    'first_name' => $normalized['first_name'],
                    'middle_name' => $normalized['middle_name'],
                    'last_name' => $normalized['last_name'],
                    'section' => $normalized['section'],
                    'email' => $normalized['email'],
                    'verified_at' => now(),
                    'metadata' => $normalized['metadata'],
                ]);

                $summary['successfully_imported']++;
            }

            session()->flash('student_import_summary', $summary);
        });

        return redirect()->route('instructor.manage-students')->with('status', 'Enrollment list imported successfully.');
    }

    public function downloadTemplate()
    {
        $user = $this->currentUser();

        $headers = [
            'student_id_number', 'first_name', 'middle_name', 'last_name', 'section', 'metadata'
        ];

        $filename = 'student_import_template.csv';

        $callback = function () use ($headers) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $headers);

            // sample row
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
        $student = $this->findStudent($studentId, $user);

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:80'],
            'middle_name' => ['nullable', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'section' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'max:255'],
        ]);

        $updateData = $data;

        if (filled($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $student->update($updateData);

        return back()->with('status', 'Student record updated.');
    }

    public function archive(int $studentId)
    {
        $user = $this->currentUser();
        $student = $this->findStudent($studentId, $user);

        $student->update([
            'status' => 'archived',
            'archived_at' => now(),
        ]);

        return back()->with('status', 'Student archived.');
    }

    public function toggleStatus(int $studentId)
    {
        $user = $this->currentUser();
        $student = $this->findStudent($studentId, $user);

        $isActive = ($student->status ?? 'active') === 'active';

        if ($isActive) {
            $student->update([
                'status' => 'archived',
                'archived_at' => now(),
            ]);
        } else {
            $student->update([
                'status' => 'active',
                'archived_at' => null,
            ]);
        }

        return back()->with('status', 'Student status updated.');
    }

    private function queryStudents($user, Request $request)
    {
        $search = trim($request->string('q')->toString());
        $section = trim($request->string('section')->toString());

        return ManagedStudent::query()
            ->active()
            ->when($user->role === 'instructor', fn ($query) => $query->where('instructor_user_id', $user->id))
            ->when($search, fn ($query) => $query->where(function ($nested) use ($search) {
                $terms = explode(' ', $search);
                if (count($terms) === 1 && strlen($search) > 2) {
                    $nested->whereRaw('MATCH(first_name, middle_name, last_name, student_id_number) AGAINST(? IN BOOLEAN MODE)', [$search . '*']);
                } else {
                    $nested->where('student_id_number', 'like', '%' . $search . '%')
                        ->orWhere('first_name', 'like', '%' . $search . '%')
                        ->orWhere('middle_name', 'like', '%' . $search . '%')
                        ->orWhere('last_name', 'like', '%' . $search . '%');
                }
            }))
            ->when($section, fn ($query) => $query->where('section', $section))
            ->latest();
    }

    private function findStudent(int $studentId, $user)
    {
        $student = ManagedStudent::withArchived()->findOrFail($studentId);

        if ($user->role === 'instructor' && $student->instructor_user_id !== $user->id) {
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

        if (!isset($normalized['first_name']) && isset($normalized['full_name'])) {
            $parts = array_filter(explode(' ', $normalized['full_name']));
            $normalized['first_name'] = count($parts) > 0 ? array_shift($parts) : '';
            $normalized['last_name'] = count($parts) > 0 ? array_pop($parts) : '';
            $normalized['middle_name'] = count($parts) > 0 ? implode(' ', $parts) : null;
        }

        return [
            'student_id_number' => $studentId,
            'first_name' => $normalized['first_name'] ?? '',
            'middle_name' => $normalized['middle_name'] ?? null,
            'last_name' => $normalized['last_name'] ?? '',
            'section' => $normalized['section'] ?? null,
            'email' => $normalized['email'] ?? null,
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

        $query = ManagedStudent::query()
            ->when($user->role === 'instructor', fn ($builder) => $builder->where('instructor_user_id', $user->id))
            ->when($term, fn ($builder) => $builder->where(function ($nested) use ($term) {
                $terms = explode(' ', $term);
                if (count($terms) === 1 && strlen($term) > 2) {
                    $nested->whereRaw('MATCH(first_name, middle_name, last_name, student_id_number) AGAINST(? IN BOOLEAN MODE)', [$term . '*']);
                } else {
                    $nested->where('student_id_number', 'like', '%' . $term . '%')
                        ->orWhere('first_name', 'like', '%' . $term . '%')
                        ->orWhere('middle_name', 'like', '%' . $term . '%')
                        ->orWhere('last_name', 'like', '%' . $term . '%');
                }
            }))
            ->limit(10)
            ->get();

        return response()->json(['data' => $query]);
    }

    public function selectStudent(Request $request)
    {
        $user = $this->currentUser();

        $data = $request->validate([
            'student_profile_id' => ['required', 'integer'],
        ]);

        $student = $this->findStudent($data['student_profile_id'], $user);

        $request->session()->put('student_portal.selected_student', [
            'id' => $student->id,
            'student_id_number' => $student->student_id_number,
            'full_name' => $student->full_name,
            'section' => $student->section,
        ]);
        $request->session()->put('student_portal.selected_name', $student->full_name);

        return response()->json(['message' => 'Student selected for the active portal session.']);
    }

    public function bindPortalSession(Request $request, string $module)
    {
        $user = $this->currentUser();
        $selectedStudent = $request->session()->get('student_portal.selected_student');

        abort_unless($selectedStudent, 403, 'Select a verified student to continue.');

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

        return response()->json(['message' => 'Portal session bound.']);
    }
}
