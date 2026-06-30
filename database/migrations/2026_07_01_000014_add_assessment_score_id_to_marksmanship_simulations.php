<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('marksmanship_simulations', function (Blueprint $table) {
            $table->foreignId('assessment_score_id')->nullable()->after('assessment_simulation_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('marksmanship_simulations', function (Blueprint $table) {
            $table->dropForeign(['assessment_score_id']);
            $table->dropColumn('assessment_score_id');
        });
    }
};
