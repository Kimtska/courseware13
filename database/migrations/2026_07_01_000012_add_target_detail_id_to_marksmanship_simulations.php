<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('marksmanship_simulations', function (Blueprint $table) {
            $table->foreignId('target_detail_id')->nullable()->after('target_mode_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('marksmanship_simulations', function (Blueprint $table) {
            $table->dropForeign(['target_detail_id']);
            $table->dropColumn('target_detail_id');
        });
    }
};
