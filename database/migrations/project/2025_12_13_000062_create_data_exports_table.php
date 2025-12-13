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
        Schema::create('data_exports', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            
            // Export Details
            $table->string('export_type', 100)->nullable(); // 'employees', 'payroll', 'attendance', etc
            $table->string('export_format', 50)->nullable(); // 'csv', 'excel', 'pdf', 'json'
            
            // Filters (JSON)
            $table->json('filters')->nullable();
            
            // File
            $table->text('file_path')->nullable();
            $table->integer('file_size')->nullable();
            
            // Status
            $table->enum('status', ['processing', 'completed', 'failed', 'expired'])->default('processing');
            
            // Security
            $table->ulid('download_token')->unique()->nullable();
            $table->timestamp('download_expires_at')->nullable();
            $table->timestamp('downloaded_at')->nullable();
            
            // Audit
            $table->ulid('requested_by');
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();
            $table->softDeletes();
            
            $table->index('tenant_id');
            $table->index('requested_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_exports');
    }
};

