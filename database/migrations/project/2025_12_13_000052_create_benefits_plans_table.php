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
        Schema::create('benefits_plans', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->ulid('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            
            // Plan Info
            $table->string('code', 50);
            $table->string('name');
            $table->text('description')->nullable();
            
            // Type
            $table->enum('benefit_type', ['health', 'dental', 'life_insurance', 'pension', 'allowance']);
            
            // Coverage
            $table->decimal('coverage_amount', 15, 2)->nullable();
            
            // Premium
            $table->decimal('monthly_premium', 10, 2)->nullable();
            $table->decimal('company_contribution_percent', 5, 2)->nullable();
            
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
        Schema::dropIfExists('benefits_plans');
    }
};

