<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Always drop the old unique index first — it blocks dropping 'module'
        try {
            Schema::table('activity', function (Blueprint $table) {
                $table->dropUnique('questions_module_question_number_unique');
            });
        } catch (\Throwable) {
            // May already be dropped
        }

        if (!Schema::hasColumn('activity', 'module_id')) {
            Schema::table('activity', function (Blueprint $table) {
                $table->foreignId('module_id')->nullable()->after('id')->constrained('modules')->cascadeOnDelete();
            });

            \Illuminate\Support\Facades\DB::statement('UPDATE activity SET module_id = module WHERE module BETWEEN 1 AND 3');
        }

        if (Schema::hasColumn('activity', 'module')) {
            Schema::table('activity', function (Blueprint $table) {
                $table->dropColumn('module');
            });
        }

        try {
            Schema::table('activity', function (Blueprint $table) {
                $table->unique(['module_id', 'question_number'], 'activity_module_question_unique');
            });
        } catch (\Throwable) {
            // Index may already exist
        }
    }

    public function down(): void
    {
        try {
            Schema::table('activity', function (Blueprint $table) {
                $table->dropUnique('activity_module_question_unique');
            });
        } catch (\Throwable) {
            // Index may not exist
        }

        if (!Schema::hasColumn('activity', 'module')) {
            Schema::table('activity', function (Blueprint $table) {
                $table->tinyInteger('module')->unsigned()->nullable()->after('id');
            });

            \Illuminate\Support\Facades\DB::statement('UPDATE activity SET module = module_id WHERE module_id IS NOT NULL');
        }

        if (Schema::hasColumn('activity', 'module_id')) {
            Schema::table('activity', function (Blueprint $table) {
                $table->dropForeign(['module_id']);
                $table->dropColumn('module_id');
            });
        }

        try {
            Schema::table('activity', function (Blueprint $table) {
                $table->unique(['module', 'question_number'], 'questions_module_question_number_unique');
            });
        } catch (\Throwable) {
            // Index may already exist
        }
    }
};
