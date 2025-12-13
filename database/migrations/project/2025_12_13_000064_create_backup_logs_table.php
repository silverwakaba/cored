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
        Schema::create('backup_logs', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            
            // Backup Details
            $table->enum('backup_type', ['full', 'incremental', 'differential']);
            
            // Status
            $table->enum('status', ['in_progress', 'completed', 'failed']);
            
            // Size
            $table->bigInteger('backup_size')->nullable();
            
            // Location
            $table->text('backup_location')->nullable();
            
            // Restoration
            $table->boolean('can_restore')->default(true);
            
            // Timestamps
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            // Audit
            $table->timestamp('created_at')->useCurrent();
            
            $table->index('tenant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_logs');
    }
};

