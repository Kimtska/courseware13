<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('enrollment_import_batches', 'student_import_batches');
    }

    public function down(): void
    {
        Schema::rename('student_import_batches', 'enrollment_import_batches');
    }
};
