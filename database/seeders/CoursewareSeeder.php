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
            'first_name' => 'Juan',
            'middle_name' => null,
            'last_name' => 'Dela Cruz',
            'section' => 'CRIM 3-1',
            'verified_at' => now(),
            'current_progress' => ['module_key' => null, 'activity_type' => 'not_started'],
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
