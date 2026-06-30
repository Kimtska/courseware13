<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Must drop FK on score_id first — the composite unique index supports both FKs
        Schema::table('activity_scores', function (Blueprint $table) {
            $table->dropForeign(['score_id']);
        });

        // Now safe to drop the composite unique index
        Schema::table('activity_scores', function (Blueprint $table) {
            $table->dropUnique('activity_scores_score_activity_unique');
        });

        // Drop the activity_id FK index and column
        Schema::table('activity_scores', function (Blueprint $table) {
            $table->dropIndex('activity_scores_activity_id_foreign');
            $table->dropColumn('activity_id');
        });

        // Re-create FK on score_id (auto-creates a new index)
        Schema::table('activity_scores', function (Blueprint $table) {
            $table->foreign('score_id')->references('id')->on('scores')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('activity_scores', function (Blueprint $table) {
            $table->dropForeign(['score_id']);
            $table->foreignId('activity_id')->nullable()->after('score_id')->constrained('activity')->cascadeOnDelete();
            $table->foreign('score_id')->references('id')->on('scores')->cascadeOnDelete();
            $table->unique(['score_id', 'activity_id'], 'activity_scores_score_activity_unique');
        });
    }
};
