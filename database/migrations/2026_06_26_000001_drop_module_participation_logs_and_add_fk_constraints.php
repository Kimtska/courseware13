<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('module_participation_logs');

        // Drop then re-add to handle partial state from previous attempt
        $this->dropFkIfExists('lessons', 'lessons_module_key_foreign');
        $this->dropFkIfExists('scores', 'scores_module_key_foreign');
        $this->dropFkIfExists('student_activity_logs', 'student_activity_logs_module_key_foreign');
        $this->dropFkIfExists('student_training_sessions', 'student_training_sessions_module_key_foreign');

        Schema::table('lessons', function (Blueprint $table) {
            $table->foreign('module_key')->references('module_key')->on('modules')->nullOnDelete();
        });

        Schema::table('scores', function (Blueprint $table) {
            $table->foreign('module_key')->references('module_key')->on('modules')->cascadeOnDelete();
        });

        Schema::table('student_activity_logs', function (Blueprint $table) {
            $table->foreign('module_key')->references('module_key')->on('modules')->cascadeOnDelete();
        });

        Schema::table('student_training_sessions', function (Blueprint $table) {
            $table->foreign('module_key')->references('module_key')->on('modules')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        $this->dropFkIfExists('lessons', 'lessons_module_key_foreign');
        $this->dropFkIfExists('scores', 'scores_module_key_foreign');
        $this->dropFkIfExists('student_activity_logs', 'student_activity_logs_module_key_foreign');
        $this->dropFkIfExists('student_training_sessions', 'student_training_sessions_module_key_foreign');

        if (!Schema::hasTable('module_participation_logs')) {
            Schema::create('module_participation_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_profile_id')->constrained('student_profiles')->cascadeOnDelete();
                $table->foreignId('recorded_by_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('module_key', 80);
                $table->string('event_type', 80);
                $table->json('payload')->nullable();
                $table->timestamps();
                $table->index(['student_profile_id', 'module_key', 'event_type'], 'module_part_logs_student_module_event_idx');
            });
        }
    }

    private function dropFkIfExists(string $table, string $constraint): void
    {
        try {
            Schema::table($table, function (Blueprint $t) use ($constraint) {
                $t->dropForeign($constraint);
            });
        } catch (\Throwable) {
            // FK didn't exist, that's fine
        }
    }
};
