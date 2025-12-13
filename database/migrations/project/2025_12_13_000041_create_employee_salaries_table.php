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
        Schema::create('employee_salaries', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            // Tenant Reference
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->ulid('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->ulid('component_id');
            $table->foreign('component_id')->references('id')->on('salary_components')->onDelete('cascade');
            
            // Amount
            $table->decimal('amount', 15, 2);
            
            // Effective Date
            $table->date('effective_date');
            $table->date('end_date')->nullable();
            
            // Reason for Change
            $table->string('change_reason')->nullable();
            
            // Audit
            $table->ulid('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index('tenant_id');
            $table->index('employee_id');
            $table->index('effective_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_salaries');
    }
};

