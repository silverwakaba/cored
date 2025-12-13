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
        Schema::create('approvals', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            
            // Reference
            $table->string('approval_type', 100)->nullable(); // 'leave_request', 'expense', 'payroll', etc
            $table->ulid('approval_target_id');
            
            // Workflow
            $table->ulid('workflow_id')->nullable();
            $table->foreign('workflow_id')->references('id')->on('workflows')->onDelete('set null');
            $table->ulid('workflow_step_id')->nullable();
            $table->foreign('workflow_step_id')->references('id')->on('workflow_steps')->onDelete('set null');
            
            // Approver
            $table->ulid('approver_id');
            $table->foreign('approver_id')->references('id')->on('users')->onDelete('cascade');
            
            // Status
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('comments')->nullable();
            
            // Dates
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('responded_at')->nullable();
            
            // Audit
            $table->timestamp('created_at')->useCurrent();
            
            $table->index('tenant_id');
            $table->index(['approval_type', 'approval_target_id']);
            $table->index('approver_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approvals');
    }
};

