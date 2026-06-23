<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('old_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('student_id_number', 60)->unique();
            $table->string('password');
            $table->enum('status', ['active', 'archived'])->default('active');
            $table->string('full_name');
            $table->string('course', 120)->nullable();
            $table->string('year_level', 20)->nullable();
            $table->string('section', 50)->nullable();
            $table->enum('enrollment_status', ['verified_enrolled', 'pending', 'rejected', 'archived'])->default('verified_enrolled');
            $table->enum('module_access_status', ['ready_for_training', 'locked', 'active_in_firing_range', 'completed_session', 'archived'])->default('ready_for_training');
            $table->enum('current_activity_status', ['inactive', 'active_in_firing_range', 'active_in_assembly', 'completed_session', 'archived'])->default('inactive');
            $table->timestamp('archived_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('moved_at')->useCurrent();
            $table->timestamps();

            $table->index(['instructor_user_id', 'course', 'year_level'], 'old_students_inst_course_year_idx');
            $table->index(['enrollment_status', 'module_access_status', 'current_activity_status'], 'old_students_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('old_students');
    }
};
