<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->dropColumn(['gender', 'school_name', 'notes']);
        });
    }

    public function down(): void
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->string('gender', 30)->nullable()->after('section');
            $table->string('school_name')->default('SPC School')->after('student_number');
            $table->text('notes')->nullable()->after('verified_at');
        });
    }
};
