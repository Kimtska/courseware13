<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ─── 1. Connect activity → lesson_details ───
        if (Schema::hasColumn('activity', 'module_id')) {
            Schema::table('activity', function (Blueprint $table) {
                $table->dropUnique('activity_module_question_unique');
                $table->dropColumn('module_id');
            });
        }

        if (!Schema::hasColumn('activity', 'lesson_detail_id')) {
            Schema::table('activity', function (Blueprint $table) {
                $table->foreignId('lesson_detail_id')->nullable()->after('id')->constrained('lesson_details')->cascadeOnDelete();
            });
        }

        // Re-add unique on (lesson_detail_id, question_number)
        try {
            Schema::table('activity', function (Blueprint $table) {
                $table->unique(['lesson_detail_id', 'question_number'], 'activity_lesson_question_unique');
            });
        } catch (\Throwable) {
            // May already exist
        }

        // ─── 2. Clean scores — remove student_id, recorded_by_user_id, module_key, module_id ───
        $this->dropFkIfExists('scores', 'scores_student_id_foreign');
        $this->dropFkIfExists('scores', 'student_scores_recorded_by_user_id_foreign');

        $dropScoreCols = [];
        if (Schema::hasColumn('scores', 'student_id')) $dropScoreCols[] = 'student_id';
        if (Schema::hasColumn('scores', 'recorded_by_user_id')) $dropScoreCols[] = 'recorded_by_user_id';
        if (Schema::hasColumn('scores', 'module_key')) $dropScoreCols[] = 'module_key';
        if (Schema::hasColumn('scores', 'module_id')) $dropScoreCols[] = 'module_id';

        if (!empty($dropScoreCols)) {
            Schema::table('scores', function (Blueprint $table) use ($dropScoreCols) {
                $table->dropColumn($dropScoreCols);
            });
        }

        // ─── 3. Drop unused tables ───
        Schema::dropIfExists('activity_scores');
        Schema::dropIfExists('student_activity_logs');
        Schema::dropIfExists('student_training_sessions');
        Schema::dropIfExists('assessment_simulations');
    }

    public function down(): void
    {
        // Not implemented — too complex to reverse
    }

    private function dropFkIfExists(string $table, string $constraint): void
    {
        try {
            Schema::table($table, fn(Blueprint $t) => $t->dropForeign($constraint));
        } catch (\Throwable) {
            // May already be dropped
        }
    }
};
