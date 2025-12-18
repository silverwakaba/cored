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
        Schema::create('employee_reviews', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('company_id', 26);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->string('performance_review_id', 26);
            $table->foreign('performance_review_id')->references('id')->on('performance_reviews')->onDelete('cascade');
            $table->string('review_question_id', 26);
            $table->foreign('review_question_id')->references('id')->on('review_questions')->onDelete('cascade');
            $table->string('reviewer_id', 26)->nullable();
            $table->foreign('reviewer_id')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('review_answer_id')->nullable();
            $table->decimal('rating', 3, 2)->nullable();
            $table->text('comment')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_reviews');
    }
};
