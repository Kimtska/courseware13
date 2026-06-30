<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Drop FK constraints to modules (keep columns, remove DB-level connections)
        $this->dropFkIfExists('activity', 'activity_module_id_foreign');
        $this->dropFkIfExists('assessment_simulations', 'assessment_simulations_module_id_foreign');
        $this->dropFkIfExists('scores', 'scores_module_id_foreign');
        $this->dropFkIfExists('scores', 'scores_module_key_foreign');
        $this->dropFkIfExists('student_activity_logs', 'student_activity_logs_module_id_foreign');
        $this->dropFkIfExists('student_activity_logs', 'student_activity_logs_module_key_foreign');
        $this->dropFkIfExists('student_training_sessions', 'student_training_sessions_module_id_foreign');
        $this->dropFkIfExists('student_training_sessions', 'student_training_sessions_module_key_foreign');
        $this->dropFkIfExists('lessons', 'lessons_module_key_foreign');

        // 2. Drop module_firearm pivot table (only purpose was linking modules ↔ firearms)
        Schema::dropIfExists('module_firearm');

        // 3. Add created_by_user_id to modules (FK to users)
        if (!Schema::hasColumn('modules', 'created_by_user_id')) {
            Schema::table('modules', function (Blueprint $table) {
                $table->foreignId('created_by_user_id')->nullable()->after('sort_order')->constrained('users')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        // Reverse not implemented — too complex to restore dropped pivots
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
