<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['student_id', 'year_level', 'gender']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('student_id', 50)->nullable()->unique()->after('id');
            $table->string('year_level', 20)->nullable()->after('role');
            $table->string('gender', 30)->nullable()->after('year_level');
        });
    }
};
