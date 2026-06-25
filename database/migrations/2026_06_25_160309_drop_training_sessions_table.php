<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('scores', function (Blueprint $table) {
            $table->dropForeign('student_scores_training_session_id_foreign');
            $table->dropColumn('training_session_id');
        });

        Schema::table('module_participation_logs', function (Blueprint $table) {
            $table->dropForeign('module_participation_logs_training_session_id_foreign');
            $table->dropColumn('training_session_id');
        });

        Schema::dropIfExists('training_sessions');
    }

    public function down(): void
    {
        Schema::create('training_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained('users')->cascadeOnDelete();
            $table->string('module_key', 80);
            $table->string('title')->nullable();
            $table->enum('status', ['draft', 'active', 'completed', 'cancelled'])->default('draft');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index(['instructor_id', 'module_key', 'status'], 'training_sessions_inst_module_status_idx');
        });

        Schema::table('scores', function (Blueprint $table) {
            $table->foreignId('training_session_id')->nullable()->constrained('training_sessions')->cascadeOnDelete();
        });

        Schema::table('module_participation_logs', function (Blueprint $table) {
            $table->foreignId('training_session_id')->nullable()->constrained('training_sessions')->cascadeOnDelete();
        });
    }
};
