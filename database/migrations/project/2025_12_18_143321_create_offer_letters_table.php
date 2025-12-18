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
        Schema::create('offer_letters', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('company_id', 26);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->string('application_id', 26);
            $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');
            $table->string('candidate_id', 26);
            $table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('cascade');
            $table->string('offer_title')->nullable();
            $table->decimal('offered_salary', 12, 2)->nullable();
            $table->string('offered_salary_currency', 3)->nullable();
            $table->date('start_date')->nullable();
            $table->date('offer_validity_date')->nullable();
            $table->unsignedBigInteger('status_id')->nullable();
            $table->string('offer_document_url', 500)->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->string('created_by', 26)->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offer_letters');
    }
};
