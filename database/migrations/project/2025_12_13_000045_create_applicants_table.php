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
        Schema::create('applicants', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->ulid('job_posting_id');
            $table->foreign('job_posting_id')->references('id')->on('job_postings')->onDelete('cascade');
            
            // Personal Info
            $table->string('first_name', 100);
            $table->string('last_name', 100)->nullable();
            $table->string('email');
            $table->string('phone', 20)->nullable();
            
            // Application Details
            $table->text('resume_url')->nullable();
            $table->text('cover_letter')->nullable();
            
            // Status
            $table->enum('status', ['new', 'screening', 'interview', 'offer', 'hired', 'rejected'])->default('new');
            
            // Dates
            $table->timestamp('applied_at')->useCurrent();
            $table->timestamp('reviewed_at')->nullable();
            
            // Score
            $table->decimal('overall_score', 5, 2)->nullable();
            
            // Audit
            $table->timestamps();
            
            $table->index('tenant_id');
            $table->index('job_posting_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};

