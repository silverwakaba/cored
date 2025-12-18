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
            $table->string('company_id', 26);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->string('job_title')->nullable();
            $table->string('job_code', 50)->nullable();
            $table->string('department_id', 26)->nullable();
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            $table->text('job_description')->nullable();
            $table->text('responsibilities')->nullable();
            $table->text('requirements')->nullable();
            $table->decimal('salary_range_min', 12, 2)->nullable();
            $table->decimal('salary_range_max', 12, 2)->nullable();
            $table->unsignedBigInteger('status_id')->nullable();
            $table->date('posted_date')->nullable();
            $table->date('closing_date')->nullable();
            $table->json('locations')->nullable();
            $table->unsignedBigInteger('job_type_id')->nullable();
            $table->integer('experience_required_years')->nullable();
            $table->unsignedBigInteger('education_required_id')->nullable();
            $table->string('created_by', 26)->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->string('updated_by', 26)->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();

            $table->index('company_id');
            $table->index(['company_id', 'status_id']);
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
