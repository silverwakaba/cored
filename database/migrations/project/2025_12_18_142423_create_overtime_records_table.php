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
        Schema::create('overtime_records', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('company_id', 26);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->string('employee_id', 26);
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->date('work_date');
            $table->decimal('overtime_hours', 5, 2)->nullable();
            $table->unsignedBigInteger('overtime_type_id')->nullable();
            $table->decimal('rate_multiplier', 3, 2)->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('approved_by', 26)->nullable();
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->boolean('is_approved')->default(false);
            $table->timestamps();

            $table->index(['company_id', 'employee_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('overtime_records');
    }
};
