<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\LessonPage;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

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
            ? StudentProfile::with(['verifier'])->where('instructor_id', $user->id)->latest()->get()
            : collect();

        // Instructor data
        $instructorData = [
            'name' => $user->name,
            'email' => $user->email,
            'total_students' => $students->count(),
            'avg_firing_score' => 76,
            'pending_evaluations' => 12,
            'live_sessions' => 1,
            'students' => $students,
            'stats' => [
                'total_students' => $students->count(),
                'sections' => 4,
                'avg_score' => 76,
                'score_change' => 4,
                'pending' => 12,
                'urgent' => 3,
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
        return view('Instructor.reports', ['name' => $user->name]);
    }
}
