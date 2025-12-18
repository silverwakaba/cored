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
            $table->uuid('uuid')->unique();
            $table->string('owner_id', 26); // ULID from Core users table
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('restrict');
            $table->string('name');
            $table->string('industry', 100)->nullable();
            $table->string('company_code', 50)->unique()->nullable();
            $table->string('legal_entity_name')->nullable();
            $table->string('registration_number', 100)->nullable();
            $table->string('tax_id', 100)->nullable();
            $table->string('website')->nullable();
            $table->string('phone', 20)->nullable();
            $table->integer('employee_count')->default(0);
            $table->string('country_id', 26)->nullable();
            // Foreign key constraint will be added in a separate migration after countries table is created
            $table->string('state_province', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('timezone', 50)->default('UTC');
            $table->string('default_currency', 3)->default('USD');
            $table->string('default_language', 10)->default('en');
            $table->string('logo_url', 500)->nullable();
            $table->integer('max_users')->default(100);
            $table->bigInteger('max_storage_gb')->default(50);
            $table->json('features_enabled')->nullable();
            $table->string('created_by', 26)->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->string('updated_by', 26)->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            $table->index('owner_id');
            $table->index(['is_active', 'deleted_at']);
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
