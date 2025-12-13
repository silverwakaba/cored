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
        Schema::create('employee_wellness_tracking', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->ulid('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->ulid('wellness_program_id');
            $table->foreign('wellness_program_id')->references('id')->on('wellness_programs')->onDelete('cascade');
            
            $table->string('metric_name', 100);
            $table->decimal('metric_value', 10, 2)->nullable();
            
            $table->decimal('wellness_score', 5, 2)->nullable();
            $table->string('risk_level', 10)->default('low'); // low, medium, high
            
            $table->timestamp('recorded_at')->useCurrent();
            $table->timestamps();
            
            $table->index(['tenant_id', 'employee_id']);
            $table->index(['tenant_id', 'wellness_program_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_wellness_tracking');
    }
};
