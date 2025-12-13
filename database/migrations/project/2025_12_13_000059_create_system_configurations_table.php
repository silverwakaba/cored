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
        Schema::create('system_configurations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            
            // Config Key
            $table->string('config_key', 100);
            $table->json('config_value');
            
            // Type
            $table->string('config_type', 50)->nullable(); // 'business_rule', 'notification', 'security', etc
            
            // Metadata
            $table->text('description')->nullable();
            $table->boolean('requires_restart')->default(false);
            
            // Audit
            $table->ulid('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
            
            $table->unique(['tenant_id', 'config_key']);
            $table->index('tenant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_configurations');
    }
};

