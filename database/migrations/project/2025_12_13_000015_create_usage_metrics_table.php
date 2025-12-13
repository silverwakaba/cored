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
        Schema::create('usage_metrics', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->string('metric_type', 100)->nullable(); // 'api_calls', 'active_users', 'data_stored_gb'
            $table->decimal('metric_value', 15, 2)->nullable();
            $table->date('period_date')->nullable();
            $table->decimal('allocated_limit', 15, 2)->nullable();
            $table->boolean('is_over_limit')->default(false);
            $table->timestamp('created_at')->useCurrent();
            
            $table->index('tenant_id');
            $table->index(['metric_type', 'period_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usage_metrics');
    }
};

