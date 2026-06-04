<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained('users')->cascadeOnDelete();
            $table->string('student_number', 50)->unique();
            $table->string('school_name')->default('SPC School');
            $table->string('first_name', 80);
            $table->string('middle_name', 80)->nullable();
            $table->string('last_name', 80);
            $table->string('year_level', 20)->nullable();
            $table->string('section', 50)->nullable();
            $table->string('gender', 30)->nullable();
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->foreignId('verified_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['instructor_id', 'verification_status'], 'student_profiles_inst_verif_idx');
            $table->index(['last_name', 'first_name'], 'student_profiles_name_idx');
        });

        Schema::create('training_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained('users')->cascadeOnDelete();
            $table->string('module_key', 80);
            $table->string('title')->nullable();
            $table->enum('status', ['draft', 'active', 'completed', 'cancelled'])->default('draft');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['instructor_id', 'module_key', 'status'], 'training_sessions_inst_module_status_idx');
        });

        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_session_id')->constrained('training_sessions')->cascadeOnDelete();
            $table->foreignId('student_profile_id')->constrained('student_profiles')->cascadeOnDelete();
            $table->foreignId('marked_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('checked_out_at')->nullable();
            $table->enum('status', ['present', 'absent', 'excused', 'attached'])->default('attached');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['training_session_id', 'student_profile_id']);
        });

        Schema::create('student_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_session_id')->constrained('training_sessions')->cascadeOnDelete();
            $table->foreignId('student_profile_id')->constrained('student_profiles')->cascadeOnDelete();
            $table->foreignId('recorded_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('module_key', 80);
            $table->decimal('score', 8, 2)->default(0);
            $table->decimal('max_score', 8, 2)->default(100);
            $table->timestamp('recorded_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['student_profile_id', 'module_key']);
        });

        Schema::create('module_participation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_session_id')->constrained('training_sessions')->cascadeOnDelete();
            $table->foreignId('student_profile_id')->constrained('student_profiles')->cascadeOnDelete();
            $table->foreignId('recorded_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('module_key', 80);
            $table->string('event_type', 80);
            $table->json('payload')->nullable();
            $table->timestamps();

            $table->index(['student_profile_id', 'module_key', 'event_type'], 'module_part_logs_student_module_event_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('module_participation_logs');
        Schema::dropIfExists('student_scores');
        Schema::dropIfExists('attendance_records');
        Schema::dropIfExists('training_sessions');
        Schema::dropIfExists('student_profiles');
    }
};