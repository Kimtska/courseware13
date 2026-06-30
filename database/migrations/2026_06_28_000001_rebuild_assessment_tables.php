<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ─── 1. activity_scores (per-question quiz results) ───
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

        // ─── 2. assessment_simulations (simulation attempt tracking) ───
        Schema::create('assessment_simulations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('module_id')->constrained('modules')->cascadeOnDelete();
            $table->string('status', 20)->default('pending');
            $table->unsignedSmallInteger('total_questions')->default(0);
            $table->unsignedSmallInteger('answered_questions')->default(0);
            $table->unsignedSmallInteger('score')->default(0);
            $table->unsignedSmallInteger('max_score')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'module_id', 'status'], 'assessment_simulations_lookup_idx');
        });

        // ─── 3. assessment_scores (child of scores, for assembly + marksmanship) ───
        Schema::create('assessment_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('score_id')->constrained('scores')->cascadeOnDelete();
            $table->string('score_type', 30); // 'assembly_disasembly', 'marksmanship'
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['score_id', 'score_type'], 'assessment_scores_type_idx');
        });

        // ─── 4. Migrate marksmanship_scores → scores + assessment_scores ───
        $marksmanshipRows = DB::table('marksmanship_scores')->get();

        foreach ($marksmanshipRows as $row) {
            $scoreId = DB::table('scores')->insertGetId([
                'module_id' => null,
                'student_id' => $row->student_id,
                'recorded_by_user_id' => $row->instructor_id,
                'module_key' => '',
                'score' => $row->total_score ?? 0,
                'max_score' => $row->max_score ?? 0,
                'recorded_at' => $row->completed_at ?? $row->created_at,
                'metadata' => json_encode(['accuracy' => $row->accuracy]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('assessment_scores')->insert([
                'score_id' => $scoreId,
                'score_type' => 'marksmanship',
                'metadata' => json_encode([
                    'weapon' => $row->weapon,
                    'time_limit' => $row->time_limit,
                    'target_mode' => $row->target_mode,
                    'total_shots' => $row->total_shots,
                    'max_shots' => $row->max_shots,
                    'bullseye_count' => $row->bullseye_count,
                    'alpha_count' => $row->alpha_count,
                    'bravo_count' => $row->bravo_count,
                    'charlie_count' => $row->charlie_count,
                    'delta_count' => $row->delta_count,
                    'miss_count' => $row->miss_count,
                    'started_at' => $row->started_at,
                    'completed_at' => $row->completed_at,
                    'accuracy' => $row->accuracy,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ─── 5. Drop marksmanship_scores ───
        Schema::dropIfExists('marksmanship_scores');
    }

    public function down(): void
    {
        // Recreate marksmanship_scores
        Schema::create('marksmanship_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('score_id')->nullable()->constrained('scores')->nullOnDelete();
            $table->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $table->foreignId('instructor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('weapon', 50)->nullable();
            $table->integer('time_limit')->nullable();
            $table->string('target_mode', 50)->nullable();
            $table->integer('total_shots')->default(0);
            $table->integer('max_shots')->default(0);
            $table->integer('bullseye_count')->default(0);
            $table->integer('alpha_count')->default(0);
            $table->integer('bravo_count')->default(0);
            $table->integer('charlie_count')->default(0);
            $table->integer('delta_count')->default(0);
            $table->integer('miss_count')->default(0);
            $table->integer('total_score')->default(0);
            $table->integer('max_score')->default(0);
            $table->decimal('accuracy', 5, 2)->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        // Restore marksmanship data from assessment_scores
        $assessmentRows = DB::table('assessment_scores')->where('score_type', 'marksmanship')->get();

        foreach ($assessmentRows as $row) {
            $score = DB::table('scores')->find($row->score_id);
            $meta = json_decode($row->metadata ?? '{}', true);

            DB::table('marksmanship_scores')->insert([
                'score_id' => $row->score_id,
                'student_id' => $score->student_id ?? null,
                'instructor_id' => $score->recorded_by_user_id ?? null,
                'weapon' => $meta['weapon'] ?? null,
                'time_limit' => $meta['time_limit'] ?? null,
                'target_mode' => $meta['target_mode'] ?? null,
                'total_shots' => $meta['total_shots'] ?? 0,
                'max_shots' => $meta['max_shots'] ?? 0,
                'bullseye_count' => $meta['bullseye_count'] ?? 0,
                'alpha_count' => $meta['alpha_count'] ?? 0,
                'bravo_count' => $meta['bravo_count'] ?? 0,
                'charlie_count' => $meta['charlie_count'] ?? 0,
                'delta_count' => $meta['delta_count'] ?? 0,
                'miss_count' => $meta['miss_count'] ?? 0,
                'total_score' => $score->score ?? 0,
                'max_score' => $score->max_score ?? 0,
                'accuracy' => $meta['accuracy'] ?? null,
                'started_at' => $meta['started_at'] ?? null,
                'completed_at' => $meta['completed_at'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::dropIfExists('assessment_scores');
        Schema::dropIfExists('assessment_simulations');
        Schema::dropIfExists('activity_scores');
    }
};
