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
        Schema::create('interviews', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('company_id', 26);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->string('application_id', 26);
            $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');
            $table->string('interviewer_id', 26)->nullable();
            $table->foreign('interviewer_id')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('interview_type_id')->nullable();
            $table->timestamp('scheduled_date')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->string('location_or_link')->nullable();
            $table->unsignedBigInteger('status_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('company_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};
