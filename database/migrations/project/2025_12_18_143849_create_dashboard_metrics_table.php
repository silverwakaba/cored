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
        Schema::create('dashboard_metrics', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('company_id', 26);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->string('metric_name')->nullable();
            $table->decimal('metric_value', 15, 2)->nullable();
            $table->date('metric_date')->nullable();
            $table->unsignedBigInteger('time_period_id')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['company_id', 'metric_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dashboard_metrics');
    }
};
