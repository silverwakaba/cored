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
        Schema::create('ai_predictions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            
            $table->string('prediction_type', 100); // churn_risk, performance, etc.
            $table->ulid('target_entity_id');
            $table->string('target_entity_type', 50); // employee, department, etc.
            
            $table->decimal('prediction_value', 5, 2);
            $table->decimal('confidence_score', 5, 2)->nullable();
            
            $table->json('factors')->nullable();
            $table->string('model_version', 50)->nullable();
            
            $table->timestamps();
            
            $table->index(['tenant_id', 'prediction_type'], 'idx_ai_predictions_tenant_type');
            $table->index(['tenant_id', 'target_entity_type', 'target_entity_id'], 'idx_ai_predictions_target');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_predictions');
    }
};
