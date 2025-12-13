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
        Schema::create('holidays', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->ulid('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            
            // Holiday Details
            $table->string('name');
            $table->date('date');
            $table->boolean('is_recurring')->default(false);
            $table->integer('recurring_month')->nullable();
            $table->integer('recurring_day')->nullable();
            
            // Configuration
            $table->boolean('is_paid')->default(true);
            $table->boolean('is_applicable_all_employees')->default(true);
            
            // Status
            $table->boolean('is_active')->default(true);
            
            // Audit
            $table->timestamp('created_at')->useCurrent();
            
            $table->unique(['tenant_id', 'company_id', 'date']);
            $table->index('tenant_id');
            $table->index('company_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('holidays');
    }
};

