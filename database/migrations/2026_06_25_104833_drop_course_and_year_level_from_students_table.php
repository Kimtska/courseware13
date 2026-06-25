<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['instructor_user_id']);
            $table->dropIndex('students_inst_course_year_idx');
            $table->dropColumn(['course', 'year_level']);
            $table->index(['instructor_user_id'], 'students_instructor_user_id_idx');
            $table->foreign('instructor_user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['instructor_user_id']);
            $table->dropIndex('students_instructor_user_id_idx');
            $table->string('course', 120)->nullable()->after('full_name');
            $table->string('year_level', 20)->nullable()->after('course');
            $table->index(['instructor_user_id', 'course', 'year_level'], 'students_inst_course_year_idx');
            $table->foreign('instructor_user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};
