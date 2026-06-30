<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Drop FK constraints referencing firearms (may already be gone)
        try {
            Schema::table('gun_parts', function (Blueprint $table) {
                $table->dropForeign(['firearm_id']);
            });
        } catch (\Throwable) {
            // FK may have been dropped in a previous migration
        }

        // 2. Rename firearms → assessment_simulations
        Schema::rename('firearms', 'assessment_simulations');

        // 3. Rename column in gun_parts (MariaDB compat)
        DB::statement('ALTER TABLE gun_parts CHANGE COLUMN firearm_id assessment_simulation_id BIGINT UNSIGNED NULL');

        // 4. Re-add FK constraint
        Schema::table('gun_parts', function (Blueprint $table) {
            $table->foreign('assessment_simulation_id')->references('id')->on('assessment_simulations')->cascadeOnDelete();
        });

        // 5. Add simulation-tracking columns
        Schema::table('assessment_simulations', function (Blueprint $table) {
            $table->foreignId('score_id')->nullable()->after('id')->constrained('scores')->cascadeOnDelete();
            $table->string('status', 20)->default('pending')->after('score_id');
            $table->timestamp('started_at')->nullable()->after('status');
            $table->timestamp('completed_at')->nullable()->after('started_at');
            $table->unsignedTinyInteger('attempt')->default(1)->after('completed_at');
            $table->boolean('passed')->default(false)->after('attempt');
        });
    }

    public function down(): void
    {
        Schema::table('gun_parts', function (Blueprint $table) {
            $table->dropForeign(['assessment_simulation_id']);
        });

        Schema::table('assessment_simulations', function (Blueprint $table) {
            $table->dropForeign(['score_id']);
            $table->dropColumn(['score_id', 'status', 'started_at', 'completed_at', 'attempt', 'passed']);
        });

        DB::statement('ALTER TABLE gun_parts CHANGE COLUMN assessment_simulation_id firearm_id BIGINT UNSIGNED NULL');

        Schema::rename('assessment_simulations', 'firearms');

        Schema::table('gun_parts', function (Blueprint $table) {
            $table->foreign('firearm_id')->references('id')->on('firearms')->cascadeOnDelete();
        });
    }
};
