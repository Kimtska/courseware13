<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Drop created_by_user_id from modules
        Schema::table('modules', function (Blueprint $table) {
            $table->dropForeign(['created_by_user_id']);
            $table->dropColumn('created_by_user_id');
        });

        // 2. Add student_id + progress columns
        Schema::table('modules', function (Blueprint $table) {
            $table->foreignId('student_id')->nullable()->after('sort_order')->constrained('students')->cascadeOnDelete();
            $table->string('status', 20)->default('not_started')->after('student_id');
            $table->foreignId('current_lesson_id')->nullable()->after('status')->constrained('lessons')->nullOnDelete();
            $table->unsignedSmallInteger('current_page')->nullable()->after('current_lesson_id');
            $table->timestamp('started_at')->nullable()->after('current_page');
            $table->timestamp('completed_at')->nullable()->after('started_at');
        });

        // 3. Drop module_student pivot
        Schema::dropIfExists('module_student');
    }

    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropForeign(['current_lesson_id']);
            $table->dropColumn(['student_id', 'status', 'current_lesson_id', 'current_page', 'started_at', 'completed_at']);
        });

        Schema::table('modules', function (Blueprint $table) {
            $table->foreignId('created_by_user_id')->nullable()->after('sort_order')->constrained('users')->nullOnDelete();
        });

        Schema::create('module_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('modules')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
            $table->unique(['module_id', 'student_id'], 'module_student_unique');
        });
    }
};
