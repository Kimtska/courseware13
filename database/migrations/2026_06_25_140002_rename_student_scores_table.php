<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('student_scores', 'scores');
    }

    public function down(): void
    {
        Schema::rename('scores', 'student_scores');
    }
};
