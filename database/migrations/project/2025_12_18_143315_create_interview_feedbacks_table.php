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
        Schema::create('interview_feedbacks', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('company_id', 26);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->string('interview_id', 26);
            $table->foreign('interview_id')->references('id')->on('interviews')->onDelete('cascade');
            $table->string('interviewer_id', 26)->nullable();
            $table->foreign('interviewer_id')->references('id')->on('users')->onDelete('set null');
            $table->decimal('rating', 3, 2)->nullable();
            $table->decimal('technical_skills_rating', 3, 2)->nullable();
            $table->decimal('communication_rating', 3, 2)->nullable();
            $table->decimal('cultural_fit_rating', 3, 2)->nullable();
            $table->text('feedback_comments')->nullable();
            $table->unsignedBigInteger('recommendation_id')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interview_feedbacks');
    }
};
