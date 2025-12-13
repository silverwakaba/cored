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
        Schema::create('payroll_details', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            // Tenant Reference
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->ulid('payroll_run_id');
            $table->foreign('payroll_run_id')->references('id')->on('payroll_runs')->onDelete('cascade');
            $table->ulid('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->ulid('component_id');
            $table->foreign('component_id')->references('id')->on('salary_components')->onDelete('cascade');
            
            // Amount
            $table->decimal('amount', 15, 2);
            
            // Audit
            $table->timestamp('created_at')->useCurrent();
            
            $table->index('tenant_id');
            $table->index('payroll_run_id');
            $table->index('employee_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_details');
    }
};

