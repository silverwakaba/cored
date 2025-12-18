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
        Schema::create('payslips', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('company_id', 26);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->string('payroll_entry_id', 26);
            $table->foreign('payroll_entry_id')->references('id')->on('payroll_entries')->onDelete('restrict');
            $table->string('employee_id', 26);
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->string('payslip_number', 50)->unique()->nullable();
            $table->string('payslip_pdf_url', 500)->nullable();
            $table->boolean('is_sent')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->boolean('is_viewed')->default(false);
            $table->timestamp('viewed_at')->nullable();
            $table->timestamps();

            $table->index('company_id');
            $table->index(['company_id', 'employee_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payslips');
    }
};
