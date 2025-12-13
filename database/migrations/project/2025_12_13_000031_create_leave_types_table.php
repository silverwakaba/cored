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
        Schema::create('leave_types', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            // Tenant Reference
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->ulid('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            
            // Leave Type Info
            $table->string('code', 50);
            $table->string('name');
            $table->text('description')->nullable();
            
            // Configuration
            $table->integer('annual_entitlement')->nullable();
            $table->boolean('is_paid')->default(true);
            $table->boolean('requires_approval')->default(true);
            $table->boolean('can_carryover')->default(true);
            $table->integer('carryover_max_days')->nullable();
            
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
        Schema::dropIfExists('leave_types');
    }
};

