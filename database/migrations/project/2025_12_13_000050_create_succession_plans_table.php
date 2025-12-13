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
        Schema::create('succession_plans', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->ulid('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            
            // Position to Succession Plan
            $table->ulid('position_id');
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('cascade');
            
            // Primary Successor
            $table->ulid('primary_successor_id')->nullable();
            $table->foreign('primary_successor_id')->references('id')->on('employees')->onDelete('set null');
            $table->enum('primary_readiness_level', ['not_ready', 'developing', 'ready', 'immediate'])->default('developing');
            
            // Backup Successor
            $table->ulid('backup_successor_id')->nullable();
            $table->foreign('backup_successor_id')->references('id')->on('employees')->onDelete('set null');
            $table->enum('backup_readiness_level', ['not_ready', 'developing', 'ready', 'immediate'])->default('not_ready');
            
            // Dates
            $table->date('plan_date')->nullable();
            $table->date('target_succession_date')->nullable();
            
            // Audit
            $table->timestamps();
            
            $table->unique(['tenant_id', 'position_id']);
            $table->index('tenant_id');
            $table->index('company_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('succession_plans');
    }
};

