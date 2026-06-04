<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\DepartmentHeadController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\InstructorStudentProfileController;
use App\Http\Controllers\InstructorStudentManagementController;
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
});

// Authentication Routes
// Allow GET /login to render the login view even if a user session exists
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
// Keep POST /login guarded by guest to prevent already-authenticated users from attempting to re-authenticate
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store')->middleware('guest');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');

// Instructor Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/instructor/dashboard', [InstructorController::class, 'dashboard'])->name('instructor.dashboard');
    Route::get('/instructor/manage-students', [InstructorStudentManagementController::class, 'index'])->name('instructor.manage-students');
    Route::post('/instructor/manage-students', [InstructorStudentManagementController::class, 'store'])->name('instructor.manage-students.store');
    Route::post('/instructor/manage-students/import', [InstructorStudentManagementController::class, 'bulkImport'])->name('instructor.manage-students.import');
    Route::patch('/instructor/manage-students/{studentId}', [InstructorStudentManagementController::class, 'update'])->name('instructor.manage-students.update');
        Route::get('/instructor/manage-students/template', [InstructorStudentManagementController::class, 'downloadTemplate'])->name('instructor.manage-students.template');
    Route::post('/instructor/manage-students/{studentId}/archive', [InstructorStudentManagementController::class, 'archive'])->name('instructor.manage-students.archive');
    Route::get('/instructor/students', [InstructorController::class, 'getStudents'])->name('instructor.students');
    Route::get('/instructor/manage-portal', [InstructorStudentProfileController::class, 'portal'])->name('instructor.manage-portal');
    Route::post('/instructor/manage-portal/select', [InstructorStudentProfileController::class, 'selectStudent'])->name('instructor.manage-portal.select');
    Route::get('/instructor/manage-portal/module-1', [InstructorStudentProfileController::class, 'moduleOne'])->name('instructor.manage-portal.module-1');
    Route::get('/instructor/manage-portal/module-3', [InstructorStudentProfileController::class, 'moduleThree'])->name('instructor.manage-portal.module-3');
    Route::get('/instructor/manage-portal/module-4', [InstructorStudentProfileController::class, 'moduleFour'])->name('instructor.manage-portal.module-4');
    Route::post('/instructor/manage-portal/{module}/unlock', [InstructorStudentProfileController::class, 'unlockModule'])->name('instructor.manage-portal.unlock');
    Route::get('/instructor/reports', [InstructorController::class, 'reports'])->name('instructor.reports');
    Route::get('/instructor/student-profiles', [InstructorStudentProfileController::class, 'index'])->name('instructor.student-profiles.index');
    Route::post('/instructor/student-profiles', [InstructorStudentProfileController::class, 'store'])->name('instructor.student-profiles.store');
    Route::get('/instructor/student-profiles/search', [InstructorStudentProfileController::class, 'search'])->name('instructor.student-profiles.search');
    Route::patch('/instructor/student-profiles/{studentProfile}/verify', [InstructorStudentProfileController::class, 'verify'])->name('instructor.student-profiles.verify');
    Route::post('/instructor/modules/{module}/sessions', [InstructorStudentProfileController::class, 'startSession'])->name('instructor.modules.sessions.store');
    Route::post('/instructor/modules/{module}/sessions/{trainingSession}/attach-student', [InstructorStudentProfileController::class, 'attachStudent'])->name('instructor.modules.sessions.attach-student');
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
});

// Department Head Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/department-head/dashboard', [DepartmentHeadController::class, 'dashboard'])->name('department-head.dashboard');
    Route::get('/department-head/manage-students', [DepartmentHeadController::class, 'manageStudents'])->name('department-head.manage-students');
    Route::get('/department-head/manage-instructors', [DepartmentHeadController::class, 'manageInstructors'])->name('department-head.manage-instructors');
    Route::post('/department-head/manage-instructors', [DepartmentHeadController::class, 'storeInstructorAccount'])->name('department-head.manage-instructors.store');
    Route::get('/department-head/stats', [DepartmentHeadController::class, 'getSystemStats'])->name('department-head.stats');
    Route::get('/department-head/users', [DepartmentHeadController::class, 'getAllUsers'])->name('department-head.users');
});
