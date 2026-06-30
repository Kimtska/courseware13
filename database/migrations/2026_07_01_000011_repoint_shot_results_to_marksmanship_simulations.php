<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shot_results', function (Blueprint $table) {
            $table->dropForeign(['assessment_score_id']);
            $table->dropColumn('assessment_score_id');
        });

        Schema::table('shot_results', function (Blueprint $table) {
            $table->foreignId('marksmanship_simulation_id')->after('id')->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('shot_results', function (Blueprint $table) {
            $table->dropForeign(['marksmanship_simulation_id']);
            $table->dropColumn('marksmanship_simulation_id');
        });

        Schema::table('shot_results', function (Blueprint $table) {
            $table->foreignId('assessment_score_id')->after('id')->constrained()->cascadeOnDelete();
        });
    }
};
