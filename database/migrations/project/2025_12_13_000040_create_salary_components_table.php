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
        Schema::create('salary_components', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            // Tenant Reference
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->ulid('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            
            // Component Info
            $table->string('code', 50);
            $table->string('name');
            $table->text('description')->nullable();
            
            // Type
            $table->enum('component_type', ['earning', 'deduction', 'tax']);
            
            // Calculation
            $table->enum('calculation_method', ['fixed', 'percentage', 'formula'])->default('fixed');
            $table->text('formula')->nullable();
            
            // Tax Treatment
            $table->boolean('is_taxable')->default(true);
            $table->boolean('is_social_security')->default(false);
            
            // Status
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->nullable();
            
            // Audit
            $table->timestamps();
            
            $table->unique(['tenant_id', 'company_id', 'code']);
            $table->index('tenant_id');
            $table->index('company_id');
            $table->index('component_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_components');
    }
};

