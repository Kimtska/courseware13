<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ─── lessons ───
        if (!Schema::hasColumn('lessons', 'module_id')) {
            Schema::table('lessons', function (Blueprint $table) {
                $table->foreignId('module_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            });
        }

        DB::statement('UPDATE lessons SET module_id = (SELECT id FROM modules WHERE modules.module_key = lessons.module_key)');

        // ─── scores ───
        if (!Schema::hasColumn('scores', 'module_id')) {
            Schema::table('scores', function (Blueprint $table) {
                $table->foreignId('module_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            });
        }

        DB::statement('UPDATE scores SET module_id = (SELECT id FROM modules WHERE modules.module_key = scores.module_key)');

        // ─── student_training_sessions ───
        if (!Schema::hasColumn('student_training_sessions', 'module_id')) {
            Schema::table('student_training_sessions', function (Blueprint $table) {
                $table->foreignId('module_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            });
        }

        DB::statement('UPDATE student_training_sessions SET module_id = (SELECT id FROM modules WHERE modules.module_key = student_training_sessions.module_key)');

        // ─── student_activity_logs ───
        if (!Schema::hasColumn('student_activity_logs', 'module_id')) {
            Schema::table('student_activity_logs', function (Blueprint $table) {
                $table->foreignId('module_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            });
        }

        DB::statement('UPDATE student_activity_logs SET module_id = (SELECT id FROM modules WHERE modules.module_key = student_activity_logs.module_key)');
    }

    public function down(): void
    {
        foreach (['lessons', 'scores', 'student_training_sessions', 'student_activity_logs'] as $table) {
            if (Schema::hasColumn($table, 'module_id')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->dropForeign(['module_id']);
                    $t->dropColumn('module_id');
                });
            }
        }
    }
};
