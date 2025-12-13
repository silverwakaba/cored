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
        Schema::create('compliance_checklists', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->ulid('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            
            // Checklist
            $table->enum('checklist_type', ['onboarding', 'offboarding', 'probation', 'annual']);
            
            // Items (JSON array)
            $table->json('items');
            
            // Progress
            $table->integer('completion_percent')->default(0);
            
            // Dates
            $table->date('due_date')->nullable();
            $table->date('completed_date')->nullable();
            
            // Audit
            $table->timestamps();
            
            $table->index('tenant_id');
            $table->index('employee_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compliance_checklists');
    }
};

