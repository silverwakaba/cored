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
        Schema::create('positions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            // Tenant Reference
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->ulid('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            
            // Position Info
            $table->string('code', 50);
            $table->string('title');
            $table->text('description')->nullable();
            
            // Hierarchy
            $table->enum('level', ['entry', 'junior', 'senior', 'lead', 'manager', 'director', 'c_suite'])->default('junior');
            $table->ulid('department_id')->nullable();
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            
            // Reporting
            $table->ulid('parent_position_id')->nullable();
            $table->foreign('parent_position_id')->references('id')->on('positions')->onDelete('set null');
            
            // Salary
            $table->ulid('salary_grade_id')->nullable();
            $table->foreign('salary_grade_id')->references('id')->on('salary_grades')->onDelete('set null');
            $table->decimal('base_salary_min', 15, 2)->nullable();
            $table->decimal('base_salary_max', 15, 2)->nullable();
            
            // Status
            $table->boolean('is_active')->default(true);
            
            // Audit
            $table->timestamps();
            
            $table->unique(['tenant_id', 'company_id', 'code']);
            $table->index('tenant_id');
            $table->index('company_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};

