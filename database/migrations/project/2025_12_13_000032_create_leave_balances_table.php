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
        Schema::create('leave_balances', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            // Tenant Reference
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->ulid('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->ulid('leave_type_id');
            $table->foreign('leave_type_id')->references('id')->on('leave_types')->onDelete('cascade');
            
            // Period
            $table->integer('year');
            
            // Balance Tracking
            $table->decimal('opening_balance', 5, 2)->nullable();
            $table->decimal('closing_balance', 5, 2)->nullable();
            $table->decimal('used', 5, 2)->default(0);
            $table->decimal('carried_forward', 5, 2)->default(0);
            
            // Audit
            $table->timestamps();
            
            $table->unique(['tenant_id', 'employee_id', 'leave_type_id', 'year']);
            $table->index('tenant_id');
            $table->index('employee_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_balances');
    }
};

