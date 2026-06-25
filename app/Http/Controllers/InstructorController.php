<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\LessonPage;
use App\Models\ManagedStudent;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class InstructorController extends Controller
{
    /**
     * Show the instructor dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Verify user is an instructor
        if ($user->role !== 'instructor') {
            return redirect('/')->with('error', 'Unauthorized access');
        }

        // Get all managed student profiles for this instructor's classes when the new schema is available.
        $students = Schema::hasTable('student_profiles')
            ? StudentProfile::with(['verifier', 'scores'])->where('instructor_id', $user->id)->latest()->get()
            : collect();

        $completedActivities = $students->filter(fn($s) => $s->scores->isNotEmpty())->count();

        $sections = Schema::hasTable('students')
            ? ManagedStudent::query()
                ->whereNotNull('section')
                ->where('section', '!=', '')
                ->where('instructor_user_id', $user->id)
                ->select('section')
                ->distinct()
                ->orderBy('section')
                ->pluck('section')
                ->all()
            : [];

        // Instructor data
        $instructorData = [
            'name' => $user->name,
            'email' => $user->email,
            'total_students' => $students->count(),
            'avg_firing_score' => 76,
            'pending_evaluations' => 12,
            'live_sessions' => 1,
            'students' => $students,
            'sections' => $sections,
            'stats' => [
                'total_students' => $students->count(),
                'sections' => count($sections),
                'avg_score' => 76,
                'score_change' => 4,
                'pending' => 12,
                'urgent' => 3,
                'completed_activities' => $completedActivities,
            ]
        ];

        return view('Instructor.dashboard', $instructorData);
    }

    /**
     * Get all instructors.
     */
    public static function getAllInstructors()
    {
        return User::where('role', 'instructor')->get();
    }

    /**
     * Get instructor by ID.
     */
    public static function getInstructorById($id)
    {
        return User::where('id', $id)->where('role', 'instructor')->first();
    }

    /**
     * Create a new instructor.
     */
    public static function createInstructor($data)
    {
        $data['role'] = 'instructor';
        return User::create($data);
    }

    /**
     * Update instructor information.
     */
    public function updateInstructor($id, $data)
    {
        $instructor = User::where('id', $id)->where('role', 'instructor')->first();
        if ($instructor) {
            $instructor->update($data);
            return $instructor;
        }
        return null;
    }

    /**
     * Delete instructor.
     */
    public function deleteInstructor($id)
    {
        $instructor = User::where('id', $id)->where('role', 'instructor')->first();
        if ($instructor) {
            $instructor->delete();
            return true;
        }
        return false;
    }

    /**
     * Get students for instructor's classes.
     */
    public function getStudents()
    {
        if (! Schema::hasTable('student_profiles')) {
            return collect();
        }

        return StudentProfile::with(['verifier'])->where('instructor_id', Auth::id())->latest()->get();
    }

    public function reports()
    {
        $user = Auth::user();
        if ($user->role !== 'instructor') {
            return redirect('/')->with('error', 'Unauthorized access');
        }

        $students = StudentProfile::where('instructor_id', $user->id)
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

        $module1Finished = $studentReports->filter(fn($r) => $r['module_1'] !== null)->count();
        $module2Finished = $studentReports->filter(fn($r) => $r['module_2'] !== null)->count();
        $module3Finished = $studentReports->filter(fn($r) => $r['module_3'] !== null)->count();
        $module3Pending = $students->count() - $module3Finished;
        $marksmanshipFinished = $studentReports->filter(fn($r) => $r['marksmanship'] !== null)->count();
        $marksmanshipPending = $students->count() - $marksmanshipFinished;

        $leaderboard = $studentReports
            ->filter(fn($r) => $r['module_1'] !== null && $r['module_2'] !== null && $r['module_3'] !== null && $r['marksmanship'] !== null)
            ->map(function ($r) {
                $avg = round(($r['module_1']['percentage'] + $r['module_2']['percentage'] + $r['module_3']['percentage'] + $r['marksmanship']['percentage']) / 4);
                return array_merge($r, ['average' => $avg]);
            })
            ->sortByDesc('average')
            ->take(10)
            ->values();

        return view('Instructor.reports', [
            'name' => $user->name,
            'students' => $students,
            'studentReports' => $studentReports,
            'leaderboard' => $leaderboard,
            'stats' => [
                'total_students' => $students->count(),
                'module1_finished' => $module1Finished,
                'module2_finished' => $module2Finished,
                'module3_finished' => $module3Finished,
                'module3_pending' => $module3Pending,
                'marksmanship_finished' => $marksmanshipFinished,
                'marksmanship_pending' => $marksmanshipPending,
            ],
        ]);
    }

    public function updateProfileName(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $user = Auth::user();
        $user->name = $request->name;
        $user->save();
        return back()->with('success', 'Profile name updated successfully.');
    }

    public function updateProfilePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);
        $user = Auth::user();
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }
        $user->password = Hash::make($request->new_password);
        $user->save();
        return back()->with('success', 'Password changed successfully.');
    }

    public function updateProfilePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);
        $user = Auth::user();
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }
        $path = $request->file('photo')->store('profile-photos', 'public');
        $user->profile_photo_path = $path;
        $user->save();
        return back()->with('success', 'Profile photo updated successfully.');
    }
}
