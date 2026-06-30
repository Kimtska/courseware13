<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shot_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_score_id')->constrained()->cascadeOnDelete();
            $table->foreignId('target_detail_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedSmallInteger('shot_number');
            $table->boolean('is_hit')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shot_results');
    }
};
