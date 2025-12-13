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
        Schema::create('custom_field_values', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->ulid('custom_field_id');
            $table->foreign('custom_field_id')->references('id')->on('custom_fields')->onDelete('cascade');
            
            $table->ulid('entity_id');
            $table->string('entity_type', 50); // should match custom_fields.entity_type
            
            $table->text('field_value')->nullable();
            
            $table->timestamps();
            
            $table->unique(['custom_field_id', 'entity_id']);
            $table->index(['tenant_id', 'entity_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_field_values');
    }
};
