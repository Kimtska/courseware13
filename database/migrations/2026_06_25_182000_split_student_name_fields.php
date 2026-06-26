<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('first_name', 80)->after('full_name');
            $table->string('middle_name', 80)->nullable()->after('first_name');
            $table->string('last_name', 80)->after('middle_name');
        });

        DB::statement("
            UPDATE students
            SET first_name = TRIM(SUBSTRING_INDEX(full_name, ' ', 1)),
                last_name = TRIM(SUBSTRING_INDEX(full_name, ' ', -1)),
                middle_name = TRIM(
                    SUBSTRING(
                        full_name,
                        LENGTH(TRIM(SUBSTRING_INDEX(full_name, ' ', 1))) + 2,
                        GREATEST(0, LENGTH(full_name) - LENGTH(TRIM(SUBSTRING_INDEX(full_name, ' ', 1))) - LENGTH(TRIM(SUBSTRING_INDEX(full_name, ' ', -1))) - 2)
                    )
                )
            WHERE full_name IS NOT NULL AND full_name != ''
        ");

        DB::statement("
            UPDATE students
            SET middle_name = NULL
            WHERE middle_name = ''
        ");

        DB::statement("
            UPDATE students
            SET last_name = first_name
            WHERE last_name = '' AND first_name != ''
        ");

        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('full_name');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->index(['last_name', 'first_name'], 'students_name_idx');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('full_name', 255)->after('last_name');
        });

        DB::statement("
            UPDATE students
            SET full_name = TRIM(
                CONCAT(
                    COALESCE(first_name, ''),
                    CASE WHEN COALESCE(middle_name, '') != '' THEN CONCAT(' ', middle_name) ELSE '' END,
                    ' ',
                    COALESCE(last_name, '')
                )
            )
        ");

        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex('students_name_idx');
            $table->dropColumn(['first_name', 'middle_name', 'last_name']);
        });

        Schema::table('students', function (Blueprint $table) {
            $table->index('full_name', 'students_full_name_idx');
        });
    }
};
