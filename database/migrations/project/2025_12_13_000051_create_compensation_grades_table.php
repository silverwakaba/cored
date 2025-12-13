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
        Schema::create('compensation_grades', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->ulid('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            
            // Grade Info
            $table->string('code', 50);
            $table->integer('level');
            
            // Salary Range
            $table->decimal('minimum', 15, 2)->nullable();
            $table->decimal('midpoint', 15, 2)->nullable();
            $table->decimal('maximum', 15, 2)->nullable();
            
            // Market Reference
            $table->decimal('market_benchmarked_rate', 15, 2)->nullable();
            
            // Status
            $table->boolean('is_active')->default(true);
            $table->date('effective_date')->nullable();
            
            // Audit
            $table->timestamp('created_at')->useCurrent();
            
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
        Schema::dropIfExists('compensation_grades');
    }
};

