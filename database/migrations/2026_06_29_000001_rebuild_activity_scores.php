<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Migrate existing activity_scores data into scores first
        $rows = DB::table('activity_scores')->get();
        $inserts = [];

        foreach ($rows as $row) {
            $scoreId = DB::table('scores')->insertGetId([
                'module_id' => $row->module_id,
                'student_id' => $row->student_id,
                'recorded_by_user_id' => null,
                'module_key' => '',
                'score' => $row->score,
                'max_score' => $row->max_score,
                'recorded_at' => $row->recorded_at ?? $row->created_at,
                'metadata' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $inserts[] = [
                'score_id' => $scoreId,
                'activity_id' => $row->activity_id,
                'answered_option' => $row->answered_option,
                'is_correct' => $row->is_correct,
                'score' => $row->score,
                'max_score' => $row->max_score,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Drop old table
        Schema::dropIfExists('activity_scores');

        // Recreate with score_id FK
        Schema::create('activity_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('score_id')->constrained('scores')->cascadeOnDelete();
            $table->foreignId('activity_id')->constrained('activity')->cascadeOnDelete();
            $table->unsignedTinyInteger('answered_option');
            $table->boolean('is_correct');
            $table->unsignedTinyInteger('score')->default(0);
            $table->unsignedTinyInteger('max_score')->default(1);
            $table->timestamps();

            $table->unique(['score_id', 'activity_id'], 'activity_scores_score_activity_unique');
        });

        // Re-insert migrated data
        if (!empty($inserts)) {
            DB::table('activity_scores')->insert($inserts);
        }
    }

    public function down(): void
    {
        // Migrate back
        $rows = DB::table('activity_scores')->get();
        $inserts = [];

        foreach ($rows as $row) {
            $score = DB::table('scores')->find($row->score_id);

            $inserts[] = [
                'student_id' => $score->student_id ?? null,
                'activity_id' => $row->activity_id,
                'module_id' => $score->module_id ?? null,
                'answered_option' => $row->answered_option,
                'is_correct' => $row->is_correct,
                'score' => $row->score,
                'max_score' => $row->max_score,
                'recorded_at' => $score->recorded_at ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Schema::dropIfExists('activity_scores');

        Schema::create('activity_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('activity_id')->constrained('activity')->cascadeOnDelete();
            $table->foreignId('module_id')->constrained('modules')->cascadeOnDelete();
            $table->unsignedTinyInteger('answered_option');
            $table->boolean('is_correct');
            $table->unsignedTinyInteger('score')->default(0);
            $table->unsignedTinyInteger('max_score')->default(1);
            $table->timestamp('recorded_at')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'activity_id'], 'activity_scores_student_question_unique');
        });

        if (!empty($inserts)) {
            DB::table('activity_scores')->insert($inserts);
        }
    }
};
