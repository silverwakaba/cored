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
        Schema::create('integrations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            
            // Integration Details
            $table->string('name', 100);
            $table->string('integration_type', 100)->nullable(); // 'slack', 'teams', 'sso', 'erp', etc
            
            // Configuration
            $table->json('configuration');
            
            // API Keys (encrypted)
            $table->text('api_key_encrypted')->nullable();
            $table->text('webhook_url')->nullable();
            
            // Status
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_sync_at')->nullable();
            
            // Audit
            $table->ulid('connected_by')->nullable();
            $table->foreign('connected_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index('tenant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('integrations');
    }
};

