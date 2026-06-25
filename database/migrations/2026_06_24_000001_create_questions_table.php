<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('module');
            $table->unsignedTinyInteger('question_number');
            $table->text('question_text');
            $table->json('options');
            $table->unsignedTinyInteger('correct_answer');
            $table->timestamps();

            $table->unique(['module', 'question_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
