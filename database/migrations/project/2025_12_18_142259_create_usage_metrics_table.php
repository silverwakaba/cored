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
        if (!Schema::hasTable('usage_metrics')) {
            Schema::create('usage_metrics', function (Blueprint $table) {
                $table->ulid('id')->primary();
                $table->string('company_id', 26);
                $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
                $table->string('metric_type', 100);
                $table->decimal('metric_value', 15, 2)->default(0);
                $table->date('period_date');
                $table->string('period_type', 20)->nullable();
                $table->string('month_year', 7)->nullable();
                $table->decimal('quantity_used', 15, 2)->default(0);
                $table->decimal('limit_allowed', 15, 2)->nullable();
                $table->timestamps();

                $table->unique(['company_id', 'metric_type', 'period_date', 'period_type'], 'usage_metrics_unique');
                $table->index(['company_id', 'metric_type']);
                $table->index(['company_id', 'period_date', 'period_type']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usage_metrics');
    }
};
