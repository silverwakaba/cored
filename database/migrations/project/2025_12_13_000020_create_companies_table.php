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
        Schema::create('companies', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            // Tenant Reference (multi-tenant isolation key)
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            
            // Company Info
            $table->string('code', 50);
            $table->string('name');
            $table->text('description')->nullable();
            
            // Legal Details
            $table->string('legal_entity_name')->nullable();
            $table->string('country', 2)->default('ID');
            $table->string('tax_id', 50)->nullable();
            $table->string('registration_number', 100)->nullable();
            
            // Contact
            $table->string('headquarters')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            
            // Configuration
            $table->string('currency', 3)->default('IDR');
            $table->string('timezone', 50)->nullable();
            $table->integer('fiscal_year_start_month')->default(1);
            
            // Status
            $table->boolean('is_active')->default(true);
            
            // Audit
            $table->ulid('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['tenant_id', 'code']);
            $table->index('tenant_id');
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};

