<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\DepartmentHeadController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\InstructorStudentProfileController;
use App\Http\Controllers\InstructorStudentManagementController;
use App\Http\Controllers\LessonActivityController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\StudentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->middleware('guest:web,student');

// Authentication Routes
// Allow GET /login to render the login view even if a user session exists
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login')->middleware('guest:web,student');
// Keep POST /login guarded by guest to prevent already-authenticated users from attempting to re-authenticate
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store')->middleware('guest');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth:web,student')->name('logout');

Route::get('/login/verify', [AuthenticatedSessionController::class, 'showVerifyForm'])->name('login.verify');
Route::post('/login/verify', [AuthenticatedSessionController::class, 'verify'])->name('login.verify.submit');

// Instructor Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/instructor/dashboard', [InstructorController::class, 'dashboard'])->name('instructor.dashboard');
    Route::get('/instructor/manage-students', [InstructorStudentManagementController::class, 'index'])->name('instructor.manage-students');
    Route::get('/instructor/manage-marksmanship', [InstructorStudentManagementController::class, 'manageMarksmanship'])->name('instructor.manage-marksmanship');
    Route::post('/instructor/manage-students', [InstructorStudentManagementController::class, 'store'])->name('instructor.manage-students.store');
    Route::post('/instructor/manage-students/import', [InstructorStudentManagementController::class, 'bulkImport'])->name('instructor.manage-students.import');
    Route::patch('/instructor/manage-students/{studentId}', [InstructorStudentManagementController::class, 'update'])->name('instructor.manage-students.update');
        Route::get('/instructor/manage-students/template', [InstructorStudentManagementController::class, 'downloadTemplate'])->name('instructor.manage-students.template');
    Route::post('/instructor/manage-students/{studentId}/archive', [InstructorStudentManagementController::class, 'archive'])->name('instructor.manage-students.archive');
    Route::patch('/instructor/manage-students/{studentId}/toggle-status', [InstructorStudentManagementController::class, 'toggleStatus'])->name('instructor.manage-students.toggle-status');
    Route::get('/instructor/students', [InstructorController::class, 'getStudents'])->name('instructor.students');
    Route::get('/instructor/manage-module', [InstructorStudentProfileController::class, 'portal'])->name('instructor.manage-module');
    Route::post('/instructor/manage-module/select', [InstructorStudentProfileController::class, 'selectStudent'])->name('instructor.manage-module.select');
    Route::get('/instructor/manage-module/module-1', [InstructorStudentProfileController::class, 'moduleOne'])->name('instructor.manage-module.module-1');
    Route::get('/instructor/manage-module/module-3', [InstructorStudentProfileController::class, 'moduleThree'])->name('instructor.manage-module.module-3');
    Route::get('/instructor/manage-module/module-4', [InstructorStudentProfileController::class, 'moduleFour'])->name('instructor.manage-module.module-4');
    Route::post('/instructor/firing-range/save-score', [InstructorStudentProfileController::class, 'saveFiringRangeScore'])->name('instructor.firing-range.save-score');
    Route::get('/instructor/lesson-activity', [LessonActivityController::class, 'index'])->name('instructor.lesson-activity');
    Route::get('/instructor/lesson-activity/active', [LessonActivityController::class, 'activeStudents'])->name('instructor.lesson-activity.active');
    Route::get('/instructor/reports', [InstructorController::class, 'reports'])->name('instructor.reports');
    Route::patch('/instructor/profile/name', [InstructorController::class, 'updateProfileName'])->name('instructor.profile.name');
    Route::patch('/instructor/profile/password', [InstructorController::class, 'updateProfilePassword'])->name('instructor.profile.password');
    Route::post('/instructor/profile/photo', [InstructorController::class, 'updateProfilePhoto'])->name('instructor.profile.photo');
    Route::get('/instructor/student-profiles', [InstructorStudentProfileController::class, 'index'])->name('instructor.student-profiles.index');
    Route::post('/instructor/student-profiles', [InstructorStudentProfileController::class, 'store'])->name('instructor.student-profiles.store');
    Route::get('/instructor/student-profiles/search', [InstructorStudentProfileController::class, 'search'])->name('instructor.student-profiles.search');
    Route::patch('/instructor/student-profiles/{studentProfile}/verify', [InstructorStudentProfileController::class, 'verify'])->name('instructor.student-profiles.verify');


    // Activity Management
    Route::get('/instructor/activity/{module}', [ActivityController::class, 'index'])->name('instructor.activity.index');
    Route::post('/instructor/activity', [ActivityController::class, 'store'])->name('instructor.activity.store');
    Route::put('/instructor/activity/{id}', [ActivityController::class, 'update'])->name('instructor.activity.update');
    Route::delete('/instructor/activity/{id}', [ActivityController::class, 'destroy'])->name('instructor.activity.destroy');
    Route::post('/instructor/activity/reorder', [ActivityController::class, 'reorder'])->name('instructor.activity.reorder');
});

Route::middleware(['auth:web,student', 'student.active'])->group(function () {
    Route::get('/student/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/student/gun-parts', [StudentController::class, 'gunParts'])->name('student.gun-parts')->middleware('module.unlocked:module-1');
    Route::get('/student/assembly', [StudentController::class, 'assembly'])->name('student.assembly')->middleware('module.unlocked:module-3');
    Route::get('/student/reports', [StudentController::class, 'reports'])->name('student.reports');
    Route::get('/student/progress', [StudentController::class, 'progress'])->name('student.progress')->middleware('module.unlocked:module-2');
    Route::get('/student/leaderboard', [StudentController::class, 'leaderboard'])->name('student.leaderboard');
    // AJAX endpoint for student pages to poll module unlock states
    Route::get('/student/module-states', [\App\Http\Controllers\ModuleAccessController::class, 'index'])->name('student.module-states');
    // Lesson activity presence (heartbeat + leave)
    Route::post('/api/lesson/heartbeat', [LessonActivityController::class, 'heartbeat'])->name('api.lesson.heartbeat');
    Route::post('/api/lesson/leave', [LessonActivityController::class, 'leave'])->name('api.lesson.leave');
    Route::post('/student/assessment/save-score', [StudentController::class, 'saveAssessmentScore'])->name('student.assessment.save-score');
    Route::post('/student/progress/update', [StudentController::class, 'updateProgress'])->name('student.progress.update');
});

Route::middleware(['auth:web,student'])->group(function () {
    // Lesson activity: list active students (read-only, accessible to both instructors and students)
    Route::get('/api/lesson/active-students', [LessonActivityController::class, 'activeStudentsApi'])->name('api.lesson.active-students');
});

// Department Head Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/department-head/dashboard', [DepartmentHeadController::class, 'dashboard'])->name('department-head.dashboard');
    Route::get('/department-head/manage-students', [DepartmentHeadController::class, 'manageStudents'])->name('department-head.manage-students');
    Route::get('/department-head/manage-instructors', [DepartmentHeadController::class, 'manageInstructors'])->name('department-head.manage-instructors');
    Route::post('/department-head/manage-instructors', [DepartmentHeadController::class, 'storeInstructorAccount'])->name('department-head.manage-instructors.store');
    Route::patch('/department-head/manage-instructors/{instructor}/toggle-status', [DepartmentHeadController::class, 'toggleInstructorStatus'])->name('department-head.manage-instructors.toggle-status');
    Route::get('/department-head/stats', [DepartmentHeadController::class, 'getSystemStats'])->name('department-head.stats');
    Route::get('/department-head/users', [DepartmentHeadController::class, 'getAllUsers'])->name('department-head.users');
    Route::patch('/department-head/profile/name', [DepartmentHeadController::class, 'updateProfileName'])->name('department-head.profile.name');
    Route::patch('/department-head/profile/password', [DepartmentHeadController::class, 'updateProfilePassword'])->name('department-head.profile.password');
    Route::post('/department-head/profile/photo', [DepartmentHeadController::class, 'updateProfilePhoto'])->name('department-head.profile.photo');
});
