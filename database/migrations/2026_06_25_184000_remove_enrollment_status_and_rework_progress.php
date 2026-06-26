<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex('students_status_idx');
            $table->dropColumn(['enrollment_status', 'module_access_status', 'current_activity_status']);
            $table->json('current_progress')->nullable()->after('status');
        });

        Schema::dropIfExists('module_access_controls');
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('current_progress');
            $table->enum('enrollment_status', ['verified_enrolled', 'pending', 'rejected', 'archived'])->default('verified_enrolled')->after('status');
            $table->enum('module_access_status', ['ready_for_training', 'locked', 'active_in_firing_range', 'completed_session', 'archived'])->default('ready_for_training');
            $table->enum('current_activity_status', ['inactive', 'active_in_firing_range', 'active_in_assembly', 'completed_session', 'archived'])->default('inactive');
            $table->index(['enrollment_status', 'module_access_status', 'current_activity_status'], 'students_status_idx');
        });

        Schema::create('module_access_controls', function (Blueprint $table) {
            $table->id();
            $table->string('module_key', 80)->unique();
            $table->boolean('is_unlocked')->default(false);
            $table->foreignId('last_action_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('locked_at')->nullable();
            $table->timestamp('unlocked_at')->nullable();
            $table->timestamps();
        });
    }
};
