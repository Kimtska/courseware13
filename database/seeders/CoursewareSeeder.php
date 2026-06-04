<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ManagedStudent;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CoursewareSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        // Create an instructor-managed admin account
        $admin = User::updateOrCreate([
            'email' => 'admin@courseware.local',
        ], [
            'name' => 'Admin User',
            'password' => Hash::make('password123'),
            'role' => 'department_head',
            'email_verified_at' => now(),
        ]);

        // Create an instructor account
        $instructor = User::updateOrCreate([
            'email' => 'instructor@courseware.local',
        ], [
            'name' => 'Maria Reyes',
            'password' => Hash::make('password123'),
            'role' => 'instructor',
            'email_verified_at' => now(),
        ]);

        ManagedStudent::updateOrCreate([
            'student_id_number' => '20260001',
        ], [
            'instructor_user_id' => $instructor->id,
            'password' => Hash::make('password'),
            'full_name' => 'Juan Dela Cruz',
            'course' => 'BS Criminology',
            'year_level' => '3rd',
            'section' => 'CRIM 3-1',
            'enrollment_status' => 'verified_enrolled',
            'module_access_status' => 'ready_for_training',
            'current_activity_status' => 'inactive',
            'verified_at' => now(),
            'metadata' => [
                'sample' => true,
            ],
        ]);

        User::updateOrCreate([
            'email' => '20260001',
        ], [
            'name' => 'Juan Dela Cruz',
            'password' => Hash::make('password'),
            'role' => 'student',
            'email_verified_at' => now(),
        ]);
    }
}
