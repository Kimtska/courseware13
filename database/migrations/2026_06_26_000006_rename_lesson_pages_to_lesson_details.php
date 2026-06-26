<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('lesson_pages', 'lesson_details');
    }

    public function down(): void
    {
        Schema::rename('lesson_details', 'lesson_pages');
    }
};
