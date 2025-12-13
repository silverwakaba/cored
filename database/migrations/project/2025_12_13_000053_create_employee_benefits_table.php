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
        Schema::create('employee_benefits', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->ulid('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->ulid('benefit_plan_id');
            $table->foreign('benefit_plan_id')->references('id')->on('benefits_plans')->onDelete('cascade');
            
            // Enrollment
            $table->date('enrollment_date');
            $table->enum('enrollment_status', ['active', 'suspended', 'terminated'])->default('active');
            
            // Coverage Info
            $table->integer('dependent_count')->default(0);
            
            // Audit
            $table->timestamps();
            
            $table->unique(['tenant_id', 'employee_id', 'benefit_plan_id']);
            $table->index('tenant_id');
            $table->index('employee_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_benefits');
    }
};

