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
        Schema::create('salary_grades', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            // Tenant Reference
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->ulid('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            
            // Grade Info
            $table->string('code', 50);
            $table->string('name');
            
            // Salary Range
            $table->decimal('min_salary', 15, 2)->nullable();
            $table->decimal('max_salary', 15, 2)->nullable();
            $table->decimal('base_salary', 15, 2)->nullable();
            
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
        Schema::dropIfExists('salary_grades');
    }
};

