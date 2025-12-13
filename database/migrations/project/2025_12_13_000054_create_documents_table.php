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
        Schema::create('documents', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->ulid('employee_id')->nullable();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            
            // Document Details
            $table->enum('document_type', ['contract', 'offer', 'promotion', 'training', 'performance_review', 'disciplinary', 'medical']);
            $table->string('title');
            
            // Storage
            $table->text('file_path');
            $table->integer('file_size')->nullable();
            $table->string('file_type', 50)->nullable();
            
            // Dates
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            
            // Status
            $table->boolean('is_active')->default(true);
            
            // Audit
            $table->ulid('uploaded_by')->nullable();
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamp('created_at')->useCurrent();
            $table->softDeletes();
            
            $table->index('tenant_id');
            $table->index('employee_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};

