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
        Schema::create('employee_trainings', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->ulid('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->ulid('training_id');
            $table->foreign('training_id')->references('id')->on('training_programs')->onDelete('cascade');
            
            // Assignment
            $table->date('assigned_date')->nullable();
            $table->enum('completion_status', ['pending', 'in_progress', 'completed', 'failed', 'cancelled'])->default('pending');
            
            // Results
            $table->decimal('score', 5, 2)->nullable();
            $table->date('certificate_issued_date')->nullable();
            
            // Audit
            $table->timestamps();
            
            $table->index('tenant_id');
            $table->index('employee_id');
            $table->index('training_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_trainings');
    }
};

