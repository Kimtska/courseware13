<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->index('status', 'idx_students_status');
            $table->index(['instructor_user_id', 'status'], 'idx_students_instructor_status');
        });

        Schema::table('scores', function (Blueprint $table) {
            $table->index('student_id', 'idx_scores_student_id');
            $table->index('activity_id', 'idx_scores_activity_id');
        });

        Schema::table('activity_scores', function (Blueprint $table) {
            $table->index('score_id', 'idx_activity_scores_score_id');
        });

        Schema::table('assessment_scores', function (Blueprint $table) {
            $table->index('score_id', 'idx_assessment_scores_score_id');
        });

        Schema::table('assessment_simulations', function (Blueprint $table) {
            $table->index('score_id', 'idx_assessment_simulations_score_id');
        });

        Schema::table('marksmanship_simulations', function (Blueprint $table) {
            $table->index('status', 'idx_marksmanship_simulations_status');
            $table->index('assessment_score_id', 'idx_ms_assessment_score_id');
        });

        Schema::table('shot_results', function (Blueprint $table) {
            $table->index(['marksmanship_simulation_id', 'shot_number'], 'idx_shot_results_sim_shot');
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->index('module_id', 'idx_lessons_module_id');
        });

        Schema::table('lesson_details', function (Blueprint $table) {
            $table->index('lesson_id', 'idx_lesson_details_lesson_id');
        });

        Schema::table('activity', function (Blueprint $table) {
            $table->index('lesson_detail_id', 'idx_activity_detail_id');
        });

        DB::statement('ALTER TABLE students ADD FULLTEXT INDEX idx_students_fulltext_search (first_name, middle_name, last_name, student_id_number)');
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex('idx_students_status');
            $table->dropIndex('idx_students_instructor_status');
            $table->dropIndex('idx_students_fulltext_search');
        });

        Schema::table('scores', function (Blueprint $table) {
            $table->dropIndex('idx_scores_student_id');
            $table->dropIndex('idx_scores_activity_id');
        });

        Schema::table('activity_scores', function (Blueprint $table) {
            $table->dropIndex('idx_activity_scores_score_id');
        });

        Schema::table('assessment_scores', function (Blueprint $table) {
            $table->dropIndex('idx_assessment_scores_score_id');
        });

        Schema::table('assessment_simulations', function (Blueprint $table) {
            $table->dropIndex('idx_assessment_simulations_score_id');
        });

        Schema::table('marksmanship_simulations', function (Blueprint $table) {
            $table->dropIndex('idx_marksmanship_simulations_status');
            $table->dropIndex('idx_ms_assessment_score_id');
        });

        Schema::table('shot_results', function (Blueprint $table) {
            $table->dropIndex('idx_shot_results_sim_shot');
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->dropIndex('idx_lessons_module_id');
        });

        Schema::table('lesson_details', function (Blueprint $table) {
            $table->dropIndex('idx_lesson_details_lesson_id');
        });

        Schema::table('activity', function (Blueprint $table) {
            $table->dropIndex('idx_activity_detail_id');
        });
    }
};
