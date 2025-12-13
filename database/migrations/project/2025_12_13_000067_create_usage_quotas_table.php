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
        Schema::create('usage_quotas', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            $table->ulid('subscription_plan_id');
            $table->foreign('subscription_plan_id')->references('id')->on('subscription_plans')->onDelete('cascade');
            
            $table->string('event_type', 100);
            $table->decimal('quota_limit', 20, 4);
            $table->string('unit_of_measure', 20);
            $table->string('period_type', 20)->default('monthly'); // daily, monthly, yearly, lifetime
            
            $table->decimal('overage_rate', 12, 6)->nullable();
            $table->boolean('is_hard_limit')->default(false);
            $table->decimal('warning_threshold_percent', 5, 2)->default(80.00);
            
            $table->timestamps();
            
            $table->index(['subscription_plan_id', 'event_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usage_quotas');
    }
};
