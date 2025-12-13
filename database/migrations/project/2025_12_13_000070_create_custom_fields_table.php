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
        Schema::create('custom_fields', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            
            $table->string('entity_type', 50); // employee, company, position, etc.
            $table->string('field_name', 255); // internal key
            $table->string('field_label', 255); // display label
            $table->string('field_type', 50); // text, number, date, dropdown, checkbox
            
            $table->json('field_options')->nullable(); // for dropdown, etc.
            
            $table->boolean('is_required')->default(false);
            $table->boolean('is_visible')->default(true);
            
            $table->integer('display_order')->nullable();
            
            $table->timestamps();
            
            $table->unique(['tenant_id', 'entity_type', 'field_name']);
            $table->index(['tenant_id', 'entity_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_fields');
    }
};
