<?php

namespace App\Http\Controllers;

use App\Models\ManagedStudent;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
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
    public function manageStudents(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'department_head') {
            return redirect('/')->with('error', 'Unauthorized access');
        }

        $search = trim($request->input('q', ''));
        $section = trim($request->input('section', ''));
        $activityStatus = trim($request->input('activity_status', ''));

        if (Schema::hasTable('students')) {
            ManagedStudent::query()
                ->active()
                ->where('created_at', '<', now()->subMonths(5))
                ->update([
                    'status' => 'archived',
                    'archived_at' => now(),
                ]);

            $students = ManagedStudent::with('latestTrainingSession')
                ->active()
                ->when($search, fn ($query) => $query->where(function ($nested) use ($search) {
                    $nested->where('student_id_number', 'like', '%' . $search . '%')
                        ->orWhere('full_name', 'like', '%' . $search . '%');
                }))
                ->when($section, fn ($query) => $query->where('section', $section))
                ->when($activityStatus, fn ($query) => $query->where('current_activity_status', $activityStatus))
                ->latest()
                ->paginate(6)
                ->withQueryString();

            $archivedStudents = ManagedStudent::withArchived()->archived()->latest()->take(10)->get();
            $fiveMonthOld = ManagedStudent::withArchived()
                ->where('status', 'archived')
                ->latest('archived_at')
                ->get();
            $sections = ManagedStudent::query()
                ->whereNotNull('section')
                ->where('section', '!=', '')
                ->select('section')
                ->distinct()
                ->orderBy('section')
                ->pluck('section')
                ->all();
            $totalStudents = ManagedStudent::query()->active()->count();
        } elseif (Schema::hasTable('student_profiles')) {
            $students = StudentProfile::query()
                ->when($search, fn ($query) => $query->where(function ($nested) use ($search) {
                    $nested->where('student_number', 'like', '%' . $search . '%')
                        ->orWhere('first_name', 'like', '%' . $search . '%')
                        ->orWhere('last_name', 'like', '%' . $search . '%');
                }))
                ->latest()
                ->paginate(6)
                ->withQueryString();

            $archivedStudents = collect();
            $fiveMonthOld = collect();
            $sections = StudentProfile::query()
                ->whereNotNull('section')
                ->where('section', '!=', '')
                ->select('section')
                ->distinct()
                ->orderBy('section')
                ->pluck('section')
                ->all();
            $totalStudents = StudentProfile::count();
        } else {
            $students = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 6);
            $archivedStudents = collect();
            $fiveMonthOld = collect();
            $sections = [];
            $totalStudents = 0;
        }

        return view('department-head.list-of-student', [
            'name' => $user->name,
            'email' => $user->email,
            'students' => $students,
            'archivedStudents' => $archivedStudents,
            'showArchivedStudents' => true,
            'fiveMonthOld' => $fiveMonthOld,
            'filters' => [
                'q' => $request->input('q', ''),
                'section' => $request->input('section', ''),
                'activity_status' => $request->input('activity_status', ''),
            ],
            'sections' => $sections,
            'totalStudents' => $totalStudents,
        ]);
    }

    /**
     * Show faculty accounts and allow creating instructor accounts.
     */
    public function manageInstructors(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'department_head') {
            return redirect('/')->with('error', 'Unauthorized access');
        }

        $search = trim($request->input('q', ''));
        $status = trim($request->input('status', ''));
        $dateRange = trim($request->input('date_range', ''));

        $instructors = User::withTrashed()->where('role', 'instructor')
            ->when($search, fn ($query) => $query->where(function ($nested) use ($search) {
                $nested->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            }))
            ->when($status === 'active', fn ($query) => $query->whereNull('deleted_at'))
            ->when($status === 'inactive', fn ($query) => $query->whereNotNull('deleted_at'))
            ->when($dateRange === 'today', fn ($query) => $query->whereDate('created_at', today()))
            ->when($dateRange === 'this_week', fn ($query) => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]))
            ->when($dateRange === 'this_month', fn ($query) => $query->whereMonth('created_at', now()->month))
            ->latest()
            ->paginate(20);

        $sectionCountsByInstructor = ManagedStudent::whereIn('instructor_user_id', $instructors->pluck('id'))
            ->whereNotNull('section')
            ->where('section', '!=', '')
            ->selectRaw('instructor_user_id, section, COUNT(*) as count')
            ->groupBy('instructor_user_id', 'section')
            ->get()
            ->groupBy('instructor_user_id')
            ->map(fn ($group) => $group->keyBy('section')->map->count);

        return view('department-head.manage-instructors', [
            'name' => $user->name,
            'email' => $user->email,
            'instructors' => $instructors,
            'sectionCountsByInstructor' => $sectionCountsByInstructor,
            'filters' => [
                'q' => $request->input('q', ''),
                'status' => $request->input('status', ''),
                'date_range' => $request->input('date_range', ''),
            ],
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
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $fullName = trim($validated['first_name'] . ' ' . ($validated['middle_name'] ? $validated['middle_name'] . ' ' : '') . $validated['last_name']);

        User::create([
            'name' => $fullName,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'instructor',
        ]);

        return redirect()->route('department-head.manage-instructors')->with([
            'created_email' => $validated['email'],
            'created_password' => $validated['password'],
        ]);
    }

    /**
     * Toggle instructor active/inactive status.
     */
    public function toggleInstructorStatus($instructorId)
    {
        $user = Auth::user();

        if ($user->role !== 'department_head') {
            return redirect('/')->with('error', 'Unauthorized access');
        }

        $instructor = User::withTrashed()->where('role', 'instructor')->findOrFail($instructorId);

        if ($instructor->deleted_at) {
            $instructor->restore();
            $message = 'Instructor account activated successfully.';
        } else {
            $instructor->delete();
            $message = 'Instructor account deactivated successfully.';
        }

        return redirect()->route('department-head.manage-instructors')->with('success', $message);
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

    /**
     * Update the authenticated department head's display name.
     */
    public function updateProfileName(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->save();

        return back()->with('success', 'Profile name updated successfully.');
    }

    /**
     * Update the authenticated department head's password.
     */
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

    /**
     * Update the authenticated department head's profile photo.
     */
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
