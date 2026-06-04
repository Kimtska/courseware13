<?php

namespace App\Http\Controllers;

use App\Models\ManagedStudent;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

class DepartmentHeadController extends Controller
{
    /**
     * Show the department head dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Verify user is a department head
        if ($user->role !== 'department_head') {
            return redirect('/')->with('error', 'Unauthorized access');
        }

        // Get all managed students and instructor accounts when the workflow tables exist.
        $students = Schema::hasTable('students')
            ? ManagedStudent::latest()->get()
            : (Schema::hasTable('student_profiles')
                ? StudentProfile::with(['instructor', 'verifier'])->latest()->get()
                : collect());
        $instructors = User::where('role', 'instructor')->get();
        $recentRegistrations = Schema::hasTable('students')
            ? ManagedStudent::latest()->take(10)->get()
            : (Schema::hasTable('student_profiles')
                ? StudentProfile::latest()->take(10)->get()
                : collect());

        // Department head data
        $deptData = [
            'name' => $user->name,
            'email' => $user->email,
            'total_students' => $students->count(),
            'total_instructors' => $instructors->count(),
            'active_users' => User::whereIn('role', ['instructor', 'department_head'])->count(),
            'system_uptime' => 99.9,
            'students' => $students,
            'instructors' => $instructors,
            'recent_registrations' => $recentRegistrations,
            'stats' => [
                'total_students' => $students->count(),
                'students_change' => 24,
                'total_instructors' => $instructors->count(),
                'active_now' => 37,
                'in_range' => 4,
                'uptime' => 99.9,
            ]
        ];

        return view('department-head.dashboard', $deptData);
    }

    /**
     * Show student performance for the department head.
     */
    public function manageStudents()
    {
        $user = Auth::user();

        if ($user->role !== 'department_head') {
            return redirect('/')->with('error', 'Unauthorized access');
        }

        $students = Schema::hasTable('student_profiles')
            ? StudentProfile::with(['scores', 'attendanceRecords'])->latest()->get()
            : collect();

        return view('department-head.manage-students', [
            'name' => $user->name,
            'email' => $user->email,
            'students' => $students,
        ]);
    }

    /**
     * Show faculty accounts and allow creating instructor accounts.
     */
    public function manageInstructors()
    {
        $user = Auth::user();

        if ($user->role !== 'department_head') {
            return redirect('/')->with('error', 'Unauthorized access');
        }

        $instructors = User::where('role', 'instructor')->latest()->get();

        return view('department-head.manage-instructors', [
            'name' => $user->name,
            'email' => $user->email,
            'instructors' => $instructors,
        ]);
    }

    /**
     * Create a faculty instructor account.
     */
    public function storeInstructorAccount(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'department_head') {
            return redirect('/')->with('error', 'Unauthorized access');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'instructor',
        ]);

        return redirect()->route('department-head.manage-instructors')->with('success', 'Instructor account created successfully.');
    }

    /**
     * Get all department heads.
     */
    public static function getAllDepartmentHeads()
    {
        return User::where('role', 'department_head')->get();
    }

    /**
     * Get department head by ID.
     */
    public static function getDepartmentHeadById($id)
    {
        return User::where('id', $id)->where('role', 'department_head')->first();
    }

    /**
     * Create a new department head.
     */
    public static function createDepartmentHead($data)
    {
        $data['role'] = 'department_head';
        return User::create($data);
    }

    /**
     * Update department head information.
     */
    public function updateDepartmentHead($id, $data)
    {
        $deptHead = User::where('id', $id)->where('role', 'department_head')->first();
        if ($deptHead) {
            $deptHead->update($data);
            return $deptHead;
        }
        return null;
    }

    /**
     * Delete department head.
     */
    public function deleteDepartmentHead($id)
    {
        $deptHead = User::where('id', $id)->where('role', 'department_head')->first();
        if ($deptHead) {
            $deptHead->delete();
            return true;
        }
        return false;
    }

    /**
     * Get system statistics.
     */
    public function getSystemStats()
    {
        return [
            'total_students' => Schema::hasTable('students') ? ManagedStudent::count() : (Schema::hasTable('student_profiles') ? StudentProfile::count() : 0),
            'total_instructors' => User::where('role', 'instructor')->count(),
            'total_department_heads' => User::where('role', 'department_head')->count(),
            'total_users' => User::whereIn('role', ['instructor', 'department_head'])->count(),
            'server_load' => 42,
            'database_usage' => 67,
            'storage' => 81,
            'uptime' => 99.9,
        ];
    }

    /**
     * Get all users with role filtering.
     */
    public function getAllUsers($role = null)
    {
        if ($role) {
            if ($role === 'student') {
                return Schema::hasTable('students')
                    ? ManagedStudent::get()
                    : (Schema::hasTable('student_profiles') ? StudentProfile::with(['instructor', 'verifier'])->get() : collect());
            }

            return User::where('role', $role)->get();
        }
        return User::all();
    }
}
