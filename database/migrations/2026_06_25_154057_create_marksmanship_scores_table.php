<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marksmanship_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('score_id')->nullable()->constrained('scores')->nullOnDelete();
            $table->foreignId('student_profile_id')->constrained('student_profiles')->cascadeOnDelete();
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
    }

    public function down(): void
    {
        Schema::dropIfExists('marksmanship_scores');
    }
};
