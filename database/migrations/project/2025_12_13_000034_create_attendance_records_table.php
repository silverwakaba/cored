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
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            // Tenant Reference
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->ulid('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->ulid('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            
            // Date & Time
            $table->date('date');
            $table->time('clock_in_time')->nullable();
            $table->time('clock_out_time')->nullable();
            $table->decimal('working_hours', 5, 2)->nullable();
            
            // Status
            $table->enum('status', ['present', 'absent', 'late', 'early_out', 'half_day', 'on_leave'])->default('present');
            
            // Location (optional)
            $table->string('clock_in_location')->nullable();
            $table->string('clock_out_location')->nullable();
            
            // Notes
            $table->text('notes')->nullable();
            
            // Audit
            $table->timestamps();
            
            $table->unique(['tenant_id', 'employee_id', 'date']);
            $table->index('tenant_id');
            $table->index('employee_id');
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};

