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
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            // Plan Info
            $table->string('code', 50)->unique();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->integer('display_order')->nullable();
            
            // Pricing
            $table->decimal('base_price_monthly', 12, 2)->nullable();
            $table->decimal('base_price_annual', 12, 2)->nullable();
            $table->decimal('price_per_employee_monthly', 10, 2)->nullable();
            
            // Employee Limits
            $table->integer('min_employees')->nullable();
            $table->integer('max_employees')->nullable();
            
            // Features (JSON)
            $table->json('features');
            
            // Status
            $table->boolean('is_active')->default(true);
            
            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};

