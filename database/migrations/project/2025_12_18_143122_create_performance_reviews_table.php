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
            $table->string('company_id', 26);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->string('employee_id', 26);
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->string('review_period', 50)->nullable();
            $table->unsignedBigInteger('review_type_id')->nullable();
            $table->decimal('overall_rating', 3, 2)->nullable();
            $table->unsignedBigInteger('status_id')->nullable();
            $table->text('review_comments')->nullable();
            $table->string('reviewer_id', 26)->nullable();
            $table->foreign('reviewer_id')->references('id')->on('users')->onDelete('set null');
            $table->date('review_date')->nullable();
            $table->decimal('self_rating', 3, 2)->nullable();
            $table->timestamps();

            $table->index(['company_id', 'employee_id']);
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
