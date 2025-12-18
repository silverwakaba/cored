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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('company_id', 26);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->string('employee_id', 26);
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->string('course_id', 26);
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->date('enrollment_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->unsignedBigInteger('status_id')->nullable();
            $table->decimal('score', 5, 2)->nullable();
            $table->decimal('passing_required_score', 5, 2)->nullable();
            $table->boolean('certificate_issued')->default(false);
            $table->timestamps();

            $table->index(['company_id', 'employee_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
