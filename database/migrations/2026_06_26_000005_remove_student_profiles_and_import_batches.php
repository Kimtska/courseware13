<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add year_level to students (needed for dashboard display)
        Schema::table('students', function (Blueprint $table) {
            $table->string('year_level', 20)->nullable()->after('section');
        });

        // Drop FK and column from scores
        Schema::table('scores', function (Blueprint $table) {
            $table->dropForeign('student_scores_student_profile_id_foreign');
            $table->dropColumn('student_profile_id');
        });

        // Drop FK and column from marksmanship_scores
        Schema::table('marksmanship_scores', function (Blueprint $table) {
            $table->dropForeign('marksmanship_scores_student_profile_id_foreign');
            $table->dropColumn('student_profile_id');
        });

        // Drop the legacy tables
        Schema::dropIfExists('student_profiles');
        Schema::dropIfExists('student_import_batches');
    }

    public function down(): void
    {
        // Recreate student_profiles
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained('users')->cascadeOnDelete();
            $table->string('student_number', 50)->unique();
            $table->string('first_name', 80);
            $table->string('middle_name', 80)->nullable();
            $table->string('last_name', 80);
            $table->string('year_level', 20)->nullable();
            $table->string('section', 50)->nullable();
            $table->enum('verification_status', ['pending','verified','rejected'])->default('pending');
            $table->foreignId('verified_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        // Recreate student_import_batches
        Schema::create('student_import_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('file_name', 255);
            $table->string('file_type', 20);
            $table->unsignedInteger('total_uploaded')->default(0);
            $table->unsignedInteger('successfully_imported')->default(0);
            $table->unsignedInteger('duplicate_records')->default(0);
            $table->unsignedInteger('invalid_entries')->default(0);
            $table->enum('status', ['completed', 'completed_with_errors', 'failed'])->default('completed');
            $table->json('summary')->nullable();
            $table->timestamps();
        });

        // Restore FK columns
        Schema::table('scores', function (Blueprint $table) {
            $table->foreignId('student_profile_id')->nullable()->after('id');
            $table->foreign('student_profile_id')->references('id')->on('student_profiles')->cascadeOnDelete();
        });

        Schema::table('marksmanship_scores', function (Blueprint $table) {
            $table->foreignId('student_profile_id')->nullable()->after('score_id');
            $table->foreign('student_profile_id')->references('id')->on('student_profiles')->cascadeOnDelete();
        });

        // Drop year_level from students
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('year_level');
        });
    }
};
