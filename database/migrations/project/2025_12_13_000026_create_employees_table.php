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
        Schema::create('employees', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            // Tenant Reference
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->ulid('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            
            // Employee Number
            $table->string('employee_number', 50);
            
            // Personal Information
            $table->string('first_name', 100);
            $table->string('last_name', 100)->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            
            // ID & Nationality
            $table->enum('id_type', ['ktp', 'passport', 'sim'])->nullable();
            $table->string('id_number', 50)->nullable();
            $table->string('nationality', 2)->nullable();
            
            // Employment Details
            $table->ulid('position_id')->nullable();
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('set null');
            $table->ulid('department_id')->nullable();
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            $table->ulid('manager_id')->nullable();
            // Self-referencing foreign key will be added after table creation
            
            // Employment Status
            $table->enum('employment_status', ['active', 'resigned', 'suspended', 'on_leave', 'retired'])->default('active');
            $table->enum('employment_type', ['permanent', 'contract', 'intern', 'freelance'])->default('permanent');
            
            // Dates
            $table->date('start_date');
            $table->date('end_date')->nullable();
            
            // Salary Grade
            $table->ulid('salary_grade_id')->nullable();
            $table->foreign('salary_grade_id')->references('id')->on('salary_grades')->onDelete('set null');
            $table->ulid('cost_center_id')->nullable();
            $table->foreign('cost_center_id')->references('id')->on('cost_centers')->onDelete('set null');
            
            // System User Link
            $table->ulid('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            
            // Audit
            $table->ulid('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['tenant_id', 'company_id', 'employee_number']);
            $table->index('tenant_id');
            $table->index('company_id');
            $table->index('position_id');
            $table->index('department_id');
            $table->index('employment_status');
        });
        
        // Add self-referencing foreign key for manager_id
        Schema::table('employees', function (Blueprint $table) {
            $table->foreign('manager_id')->references('id')->on('employees')->onDelete('set null');
        });
        
        // Add foreign key for departments.manager_id
        Schema::table('departments', function (Blueprint $table) {
            $table->foreign('manager_id')->references('id')->on('employees')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropForeign(['manager_id']);
        });
        
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['manager_id']);
        });
        
        Schema::dropIfExists('employees');
    }
};

