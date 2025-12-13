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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            // Tenant Reference
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            
            // Subscription Plan
            $table->ulid('plan_id')->nullable();
            $table->foreign('plan_id')->references('id')->on('subscription_plans')->onDelete('set null');
            $table->string('plan_name', 100)->nullable();
            
            // Pricing
            $table->decimal('monthly_price', 12, 2)->nullable();
            $table->decimal('annual_price', 12, 2)->nullable();
            $table->enum('billing_cycle', ['monthly', 'annual'])->default('monthly');
            
            // Employee Count Tier
            $table->integer('employee_count_tier')->nullable();
            $table->integer('max_employees')->nullable();
            
            // Status & Dates
            $table->enum('status', ['active', 'trial', 'cancelled', 'past_due'])->default('trial');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('renews_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            
            // Features Enabled (JSON array of feature codes)
            $table->json('enabled_features')->nullable();
            
            // Payment
            $table->ulid('payment_method_id')->nullable();
            $table->boolean('auto_renew')->default(true);
            
            // Audit
            $table->timestamps();
            
            $table->index('tenant_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};

