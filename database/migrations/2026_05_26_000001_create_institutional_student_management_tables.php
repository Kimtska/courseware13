<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('student_id_number', 60)->unique();
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
            $table->timestamps();

            $table->index(['instructor_user_id', 'course', 'year_level'], 'students_inst_course_year_idx');
            $table->index(['enrollment_status', 'module_access_status', 'current_activity_status'], 'students_status_idx');
            $table->index(['full_name'], 'students_full_name_idx');
        });

        Schema::create('student_training_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('instructor_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('module_key', 80);
            $table->string('session_type', 80)->nullable();
            $table->enum('status', ['active', 'completed', 'cancelled', 'archived'])->default('active');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'module_key', 'status'], 'student_training_sessions_idx');
        });

        Schema::create('student_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('student_training_session_id')->nullable()->constrained('student_training_sessions')->nullOnDelete();
            $table->foreignId('instructor_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('module_key', 80);
            $table->string('activity_type', 80);
            $table->string('activity_status', 80)->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'module_key', 'activity_type'], 'student_activity_logs_idx');
        });

        Schema::create('enrollment_import_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('file_name');
            $table->string('file_type', 20);
            $table->unsignedInteger('total_uploaded')->default(0);
            $table->unsignedInteger('successfully_imported')->default(0);
            $table->unsignedInteger('duplicate_records')->default(0);
            $table->unsignedInteger('invalid_entries')->default(0);
            $table->enum('status', ['completed', 'completed_with_errors', 'failed'])->default('completed');
            $table->json('summary')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollment_import_batches');
        Schema::dropIfExists('student_activity_logs');
        Schema::dropIfExists('student_training_sessions');
        Schema::dropIfExists('students');
    }
};