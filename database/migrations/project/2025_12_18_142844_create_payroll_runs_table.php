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
        Schema::create('payroll_runs', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('company_id', 26);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedBigInteger('payroll_cycle_id')->nullable();
            $table->date('period_start_date');
            $table->date('period_end_date');
            $table->date('payment_date')->nullable();
            $table->unsignedBigInteger('status_id')->nullable();
            $table->decimal('total_gross', 15, 2)->nullable();
            $table->decimal('total_deductions', 15, 2)->nullable();
            $table->decimal('total_taxes', 15, 2)->nullable();
            $table->decimal('total_net', 15, 2)->nullable();
            $table->integer('total_employees')->nullable();
            $table->integer('processed_count')->default(0);
            $table->integer('error_count')->default(0);
            $table->timestamp('locked_at')->nullable();
            $table->string('locked_by', 26)->nullable();
            $table->foreign('locked_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamp('finalized_at')->nullable();
            $table->string('finalized_by', 26)->nullable();
            $table->foreign('finalized_by')->references('id')->on('users')->onDelete('set null');
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
        Schema::dropIfExists('payroll_runs');
    }
};
