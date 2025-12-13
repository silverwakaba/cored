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
        Schema::create('performance_reviews', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->ulid('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            
            // Review Details
            $table->string('review_period', 50)->nullable();
            $table->enum('review_type', ['annual', 'mid_year', 'probation', 'project'])->default('annual');
            
            // Ratings
            $table->decimal('overall_rating', 3, 1)->nullable();
            $table->decimal('performance_rating', 3, 1)->nullable();
            $table->decimal('behavioral_rating', 3, 1)->nullable();
            
            // Content
            $table->text('strengths')->nullable();
            $table->text('development_areas')->nullable();
            $table->text('comments')->nullable();
            
            // Approval
            $table->ulid('reviewed_by')->nullable();
            $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            
            // Dates
            $table->date('review_period_start')->nullable();
            $table->date('review_period_end')->nullable();
            
            // Audit
            $table->timestamps();
            
            $table->index('tenant_id');
            $table->index('employee_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_reviews');
    }
};

