<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('targets');

        Schema::create('targets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('image_path')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('target_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('target_id')->constrained('targets')->cascadeOnDelete();
            $table->string('name');
            $table->string('display_name');
            $table->integer('points');
            $table->string('color', 20);
            $table->string('image_path')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('target_modes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('target_id')->constrained('targets')->cascadeOnDelete();
            $table->string('name');
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('target_modes');
        Schema::dropIfExists('target_details');
        Schema::dropIfExists('targets');
    }
};
