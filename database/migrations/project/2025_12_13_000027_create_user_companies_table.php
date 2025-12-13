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
        Schema::create('user_companies', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            // References
            $table->ulid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            
            // Role Assignment (can have multiple roles per company)
            // Default roles: admin, hr_manager, manager, employee, finance
            $table->string('primary_role', 50)->default('employee');
            
            // Employee Link (if user is also an employee in the system)
            $table->ulid('employee_id')->nullable();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
            
            // Access Status
            $table->boolean('is_active')->default(true);
            $table->timestamp('access_starts_at')->useCurrent();
            $table->timestamp('access_ends_at')->nullable();
            
            // Audit
            $table->timestamps();
            
            $table->unique(['user_id', 'tenant_id']);
            $table->index('user_id');
            $table->index('tenant_id');
            $table->index('employee_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_companies');
    }
};

