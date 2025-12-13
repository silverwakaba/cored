<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tenant_onboarding', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('tenant_id')->unique();
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->boolean('step_1_company_info_completed')->default(false);
            $table->boolean('step_2_admin_user_created')->default(false);
            $table->boolean('step_3_employee_data_imported')->default(false);
            $table->boolean('step_4_access_configured')->default(false);
            $table->boolean('step_5_initial_setup_complete')->default(false);
            $table->integer('overall_completion_percent')->default(0);
            $table->timestamp('onboarding_started_at')->useCurrent();
            $table->timestamp('onboarding_completed_at')->nullable();
            $table->boolean('is_high_touch')->default(false);
            $table->ulid('assigned_onboarding_specialist')->nullable();
            $table->foreign('assigned_onboarding_specialist')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_onboarding');
    }
};

