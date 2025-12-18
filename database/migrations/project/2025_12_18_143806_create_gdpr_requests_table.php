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
        Schema::create('gdpr_requests', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('company_id', 26);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->string('employee_id', 26);
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->unsignedBigInteger('request_type_id')->nullable();
            $table->date('request_date');
            $table->unsignedBigInteger('status_id')->nullable();
            $table->text('reason')->nullable();
            $table->string('response_data_url', 500)->nullable();
            $table->date('completed_date')->nullable();
            $table->date('deletion_scheduled_for')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gdpr_requests');
    }
};
