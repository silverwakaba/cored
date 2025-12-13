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
        Schema::create('payroll_runs', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            // Tenant Reference
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->ulid('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            
            // Period
            $table->date('period_start');
            $table->date('period_end');
            $table->integer('month')->nullable();
            $table->integer('year')->nullable();
            
            // Status
            $table->enum('status', ['draft', 'processing', 'processed', 'approved', 'paid', 'closed'])->default('draft');
            
            // Summary
            $table->integer('total_employees')->nullable();
            $table->decimal('total_gross', 18, 2)->nullable();
            $table->decimal('total_deductions', 18, 2)->nullable();
            $table->decimal('total_tax', 18, 2)->nullable();
            $table->decimal('total_net', 18, 2)->nullable();
            
            // Approval
            $table->ulid('processed_by')->nullable();
            $table->foreign('processed_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamp('processed_at')->nullable();
            $table->ulid('approved_by')->nullable();
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            
            // Audit
            $table->ulid('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index('tenant_id');
            $table->index('company_id');
            $table->index('status');
            $table->index(['period_start', 'period_end']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_runs');
    }
};

