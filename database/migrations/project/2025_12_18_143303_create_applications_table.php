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
        Schema::create('applications', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('company_id', 26);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->string('candidate_id', 26);
            $table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('cascade');
            $table->string('job_posting_id', 26);
            $table->foreign('job_posting_id')->references('id')->on('job_postings')->onDelete('cascade');
            $table->unsignedBigInteger('status_id')->nullable();
            $table->date('applied_date')->nullable();
            $table->string('cover_letter_url', 500)->nullable();
            $table->decimal('rating', 3, 2)->nullable();
            $table->unsignedBigInteger('screening_stage_id')->nullable();
            $table->timestamps();

            $table->index('company_id');
            $table->index(['company_id', 'job_posting_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
