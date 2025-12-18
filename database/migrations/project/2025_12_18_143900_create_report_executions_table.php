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
        Schema::create('report_executions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('company_id', 26);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->string('custom_report_id', 26)->nullable();
            $table->foreign('custom_report_id')->references('id')->on('custom_reports')->onDelete('set null');
            $table->string('executed_by', 26)->nullable();
            $table->foreign('executed_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamp('execution_date')->nullable();
            $table->integer('row_count')->nullable();
            $table->string('file_url', 500)->nullable();
            $table->unsignedBigInteger('file_type_id')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_executions');
    }
};
