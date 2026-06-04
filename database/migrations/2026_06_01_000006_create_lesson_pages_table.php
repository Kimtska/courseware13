<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lesson_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained('lessons')->cascadeOnDelete();
            $table->unsignedTinyInteger('lesson_index');
            $table->unsignedTinyInteger('page_index');
            $table->string('title');
            $table->longText('body_html');
            $table->timestamps();

            $table->unique(['lesson_id', 'page_index']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_pages');
    }
};
