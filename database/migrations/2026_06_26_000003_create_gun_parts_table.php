<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gun_parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('firearm_id')->constrained()->cascadeOnDelete();
            $table->string('slug', 30);
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('sort_order');
            $table->unsignedTinyInteger('z_order');
            $table->string('image_path', 255)->nullable();
            $table->string('glow_image_path', 255)->nullable();
            $table->unsignedSmallInteger('zone_x');
            $table->unsignedSmallInteger('zone_y');
            $table->unsignedSmallInteger('zone_w');
            $table->unsignedSmallInteger('zone_h');
            $table->timestamps();

            $table->unique(['firearm_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gun_parts');
    }
};
