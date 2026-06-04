# VirtualArm Database Connection Implementation

## Overview
This document describes the implementation of database connectivity for the three user roles: **Student**, **Instructor**, and **Department Head**.

## Changes Made

### 1. User Model Update
**File:** `app/Models/User.php`
- Added `'role'` to the `$fillable` array
- The role field accepts three enum values: `student`, `instructor`, `department_head`

### 2. Database Migration
**File:** `database/migrations/2025_05_23_000000_add_role_to_users_table.php`
- Creates an ENUM column `role` in the users table
- Default value: `'student'`
- Stores user role for authentication and authorization

**To run migrations:**
```bash
php artisan migrate
```

### 3. Controllers Created

#### StudentController
**File:** `app/Http/Controllers/StudentController.php`
- `dashboard()` - Returns student dashboard with their data
- `getAllStudents()` - Fetch all students from database
- `getStudentById($id)` - Get specific student
- `createStudent($data)` - Create new student
- `updateStudent($id, $data)` - Update student info
- `deleteStudent($id)` - Delete student

#### InstructorController
**File:** `app/Http/Controllers/InstructorController.php`
- `dashboard()` - Returns instructor dashboard with classes and students
- `getAllInstructors()` - Fetch all instructors
- `getInstructorById($id)` - Get specific instructor
- `createInstructor($data)` - Create new instructor
- `updateInstructor($id, $data)` - Update instructor
- `deleteInstructor($id)` - Delete instructor
- `getStudents()` - Get students for instructor's classes

#### DepartmentHeadController
**File:** `app/Http/Controllers/DepartmentHeadController.php`
- `dashboard()` - Returns admin dashboard with system overview
- `getAllDepartmentHeads()` - Fetch all department heads
- `getDepartmentHeadById($id)` - Get specific department head
- `createDepartmentHead($data)` - Create new department head
- `updateDepartmentHead($id, $data)` - Update department head
- `deleteDepartmentHead($id)` - Delete department head
- `getSystemStats()` - Get system statistics and health
- `getAllUsers($role)` - Get users filtered by role

### 4. Routes Updated
**File:** `routes/web.php`

**Student Routes:**
```php
GET /student/dashboard          // View student dashboard
```

**Instructor Routes:**
```php
GET /instructor/dashboard       // View instructor dashboard
GET /instructor/students        // Get instructor's students
```

**Department Head Routes:**
```php
GET /department-head/dashboard  // View admin dashboard
GET /department-head/stats      // Get system statistics
GET /department-head/users      // Get all users (filtered by role)
```

### 5. Views Updated

#### Student Dashboard (`resources/views/Students/dashboard.blade.php`)
- Changed to use Blade template variables: `$name`, `$modules_completed`, `$total_modules`, `$overall_score`, etc.
- User avatar shows initials calculated from user name
- Removed localStorage dependency
- Added CSRF-aware logout function

#### Instructor Dashboard (`resources/views/Instructor/dashboard.blade.php`)
- Updated stats cards to use: `$stats['total_students']`, `$stats['avg_score']`, `$stats['pending']`, `$stats['urgent']`
- User avatar shows instructor initials
- Student performance table ready to populate with `$students` variable
- Removed localStorage, added proper logout

#### Department Head Dashboard (`resources/views/department-head/dashboard.blade.php`)
- Updated stats to use: `$stats['total_students']`, `$stats['total_instructors']`, `$stats['active_now']`, `$stats['uptime']`
- Avatar shows department head initials
- Ready to display recent registrations with `$recent_registrations`
- Removed localStorage, uses Laravel logout

## Usage Examples

### Access Student Dashboard
```php
// URL: /student/dashboard
// The StudentController@dashboard will return the view with student data
```

### Access Instructor Dashboard
```php
// URL: /instructor/dashboard
// Lists all students and their performance metrics
```

### Access Department Head Dashboard
```php
// URL: /department-head/dashboard
// Shows system overview with all users and statistics
```

## Creating Test Users

### Using PHP Artisan Tinker
```bash
php artisan tinker

// Create a student
$student = App\Models\User::create([
    'name' => 'Juan Dela Cruz',
    'email' => 'juan@example.com',
    'password' => bcrypt('password'),
    'role' => 'student'
]);

// Create an instructor
$instructor = App\Models\User::create([
    'name' => 'Maria Reyes',
    'email' => 'maria@example.com',
    'password' => bcrypt('password'),
    'role' => 'instructor'
]);

// Create a department head
$deptHead = App\Models\User::create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
    'role' => 'department_head'
]);
```

### Via Seed Factory
Create a seeder in `database/seeders/` to automatically populate test users.

## Authentication Middleware

All routes are protected with Laravel's built-in `['auth', 'verified']` middleware.

To ensure role-based access control, you can add custom middleware:

```php
// app/Http/Middleware/CheckRole.php
public function handle($request, Closure $next, $role)
{
    if (auth()->user()->role !== $role) {
        return redirect('/')->with('error', 'Unauthorized');
    }
    return $next($request);
}

// In routes:
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student/dashboard', [StudentController::class, 'dashboard']);
});
```

## Data Flow

```
User Login
    ↓
Authenticate (email + password)
    ↓
Check User Role (student/instructor/department_head)
    ↓
Load Appropriate Controller
    ↓
Fetch Data from Database
    ↓
Pass Data to Blade View
    ↓
Display Dashboard with Database Values
```

## Next Steps

1. **Run Migration:** `php artisan migrate`
2. **Create Test Users:** Use Tinker or create seeders
3. **Test Dashboards:** Login as each role to verify data display
4. **Add Validation:** Implement role-based access control middleware
5. **Database Relationships:** Add relationships between users, students, courses, and grades
6. **API Integration:** Create API endpoints for live data updates

## File Summary

| File | Purpose |
|------|---------|
| `app/Models/User.php` | User model with role support |
| `database/migrations/2025_05_23_000000_add_role_to_users_table.php` | Role column migration |
| `app/Http/Controllers/StudentController.php` | Student management |
| `app/Http/Controllers/InstructorController.php` | Instructor management |
| `app/Http/Controllers/DepartmentHeadController.php` | Admin management |
| `routes/web.php` | Route definitions |
| `resources/views/Students/dashboard.blade.php` | Student view |
| `resources/views/Instructor/dashboard.blade.php` | Instructor view |
| `resources/views/department-head/dashboard.blade.php` | Department head view |

## Support

For issues or questions about the implementation, refer to the Laravel documentation:
- [Authentication](https://laravel.com/docs/authentication)
- [Models](https://laravel.com/docs/eloquent)
- [Controllers](https://laravel.com/docs/controllers)
- [Blade Templates](https://laravel.com/docs/blade)
