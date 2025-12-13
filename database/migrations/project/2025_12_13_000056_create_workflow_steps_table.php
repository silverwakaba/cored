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
        Schema::create('workflow_steps', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->ulid('workflow_id');
            $table->foreign('workflow_id')->references('id')->on('workflows')->onDelete('cascade');
            
            // Step Details
            $table->integer('step_order');
            $table->string('step_name');
            
            // Approval
            $table->string('approver_rule')->nullable(); // 'manager', 'role:hr_manager', 'specific_user:ulid'
            
            // Conditions (JSON)
            $table->json('conditions')->nullable();
            
            // Timeout
            $table->integer('approval_timeout_days')->nullable();
            $table->boolean('auto_approve_on_timeout')->default(false);
            
            // Status
            $table->boolean('is_active')->default(true);
            
            // Audit
            $table->timestamp('created_at')->useCurrent();
            
            $table->unique(['tenant_id', 'workflow_id', 'step_order']);
            $table->index('tenant_id');
            $table->index('workflow_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_steps');
    }
};

