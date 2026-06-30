<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assessment_scores', function (Blueprint $table) {
            $table->dropForeign(['target_mode_id']);
            $table->dropForeign(['target_id']);
            $table->dropColumn(['target_mode_id', 'target_id']);
        });
    }

    public function down(): void
    {
        Schema::table('assessment_scores', function (Blueprint $table) {
            $table->foreignId('target_id')->nullable()->after('score_type')->constrained()->nullOnDelete();
            $table->foreignId('target_mode_id')->nullable()->after('target_id')->constrained()->nullOnDelete();
        });
    }
};
