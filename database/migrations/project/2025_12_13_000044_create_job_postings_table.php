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
        Schema::create('job_postings', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->ulid('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            
            // Job Details
            $table->string('title');
            $table->text('description')->nullable();
            $table->ulid('position_id')->nullable();
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('set null');
            $table->ulid('department_id')->nullable();
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            
            // Salary Info
            $table->decimal('salary_min', 15, 2)->nullable();
            $table->decimal('salary_max', 15, 2)->nullable();
            
            // Status & Dates
            $table->enum('status', ['draft', 'open', 'closed', 'on_hold'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            
            // Count
            $table->integer('positions_available')->default(1);
            $table->integer('positions_filled')->default(0);
            
            // Audit
            $table->ulid('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index('tenant_id');
            $table->index('company_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_postings');
    }
};

