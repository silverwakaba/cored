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
        Schema::create('analytics_snapshots', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            
            // Metric Name
            $table->string('metric_name', 100);
            
            // Value
            $table->decimal('metric_value', 15, 2)->nullable();
            
            // Dimension (for slicing)
            $table->string('dimension_1', 100)->nullable(); // e.g., department
            $table->string('dimension_2', 100)->nullable(); // e.g., location
            $table->string('dimension_3', 100)->nullable(); // e.g., employment_type
            
            // Period
            $table->date('snapshot_date')->nullable();
            
            // Audit
            $table->timestamp('created_at')->useCurrent();
            
            $table->index(['tenant_id', 'metric_name', 'snapshot_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics_snapshots');
    }
};

