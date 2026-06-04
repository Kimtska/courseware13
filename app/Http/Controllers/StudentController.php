<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\ManagedStudent;
use App\Models\StudentProfile;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * Show the student dashboard.
     */
    public function dashboard()
    {
        return view('Students.dashboard', $this->studentViewData());
    }

    /**
     * Show the student firing range.
     */
    public function firingRange()
    {
        return view('Students.firing-range', $this->studentViewData());
    }

    /**
     * Show the student assembly courseware.
     */
    public function assembly()
    {
        return view('Students.assembly', $this->studentViewData());
    }

    /**
     * Show the student gun parts courseware.
     */
    public function gunParts()
    {
        $lesson = Lesson::where('key', 'gun-parts')
            ->with(['pages' => function ($query) {
                $query->orderBy('page_index');
            }])
            ->first();

        $lessonPages = $lesson?->pages ?? collect();

        return view('Students.gun-parts', array_merge($this->studentViewData(), [
            'lesson' => $lesson,
            'lessonPages' => $lessonPages,
        ]));
    }

    /**
     * Show the student progress page.
     */
    public function progress()
    {
        return view('Students.progress', $this->studentViewData());
    }

    /**
     * Show the student leaderboard page.
     */
    public function leaderboard()
    {
        return view('Students.leaderboard', $this->studentViewData());
    }

    /**
     * Show the student reports page (assessment summaries).
     */
    public function reports()
    {
        $data = $this->studentViewData();

        // Determine the managed student id number from the current context
        $studentIdNumber = $data['selectedStudent']['student_id_number'] ?? null;

        $profile = null;
        if ($studentIdNumber) {
            $profile = StudentProfile::where('student_number', $studentIdNumber)->first();
        }

        $scores = [];
        if ($profile) {
            $scores = \App\Models\StudentScore::where('student_profile_id', $profile->id)->orderBy('recorded_at', 'desc')->get();
        }

        return view('Students.reports', array_merge($data, [
            'profile' => $profile,
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
                'year_level' => $student?->year_level ?? 'N/A',
                'section' => $student?->section ?? 'Student Portal',
            ],
        ];
    }

    /**
     * Get all students (for admin purposes).
     */
    public static function getAllStudents()
    {
        return StudentProfile::with(['instructor', 'verifier'])->latest()->get();
    }

    /**
     * Get student by ID.
     */
    public static function getStudentById($id)
    {
        return StudentProfile::with(['instructor', 'verifier'])->find($id);
    }

    /**
     * Create a new student.
     */
    public static function createStudent($data)
    {
        return StudentProfile::create($data);
    }

    /**
     * Update student information.
     */
    public function updateStudent($id, $data)
    {
        $student = StudentProfile::find($id);
        if ($student) {
            $student->update($data);
            return $student;
        }
        return null;
    }

    /**
     * Delete student.
     */
    public function deleteStudent($id)
    {
        $student = StudentProfile::find($id);
        if ($student) {
            $student->delete();
            return true;
        }
        return false;
    }
}
