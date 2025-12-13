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
        Schema::create('performance_goals', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->ulid('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            
            // Goal Details
            $table->string('title');
            $table->text('description')->nullable();
            
            // SMART Framework
            $table->decimal('target_value', 10, 2)->nullable();
            $table->string('unit', 50)->nullable();
            
            // Period
            $table->integer('goal_year')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            
            // Status & Progress
            $table->enum('status', ['draft', 'active', 'completed', 'cancelled'])->default('draft');
            $table->decimal('current_progress', 10, 2)->nullable();
            
            // Audit
            $table->ulid('set_by')->nullable();
            $table->foreign('set_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index('tenant_id');
            $table->index('employee_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_goals');
    }
};

