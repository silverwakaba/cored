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
        Schema::create('workflows', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            
            // Workflow Definition
            $table->string('code', 50);
            $table->string('name');
            $table->text('description')->nullable();
            
            // Trigger & Type
            $table->string('trigger_event', 100)->nullable();
            $table->enum('workflow_type', ['approval', 'notification', 'data_sync'])->default('approval');
            
            // Configuration (JSON)
            $table->json('configuration');
            
            // Status
            $table->boolean('is_active')->default(true);
            
            // Audit
            $table->ulid('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
            
            $table->unique(['tenant_id', 'code']);
            $table->index('tenant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflows');
    }
};

