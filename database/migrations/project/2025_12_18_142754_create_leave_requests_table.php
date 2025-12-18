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
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('company_id', 26);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->string('employee_id', 26);
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->string('leave_type_id', 26);
            $table->foreign('leave_type_id')->references('id')->on('leave_types')->onDelete('restrict');
            $table->date('request_date');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('number_of_days', 8, 2)->nullable();
            $table->text('reason')->nullable();
            $table->unsignedBigInteger('status_id')->nullable();
            $table->text('approval_comments')->nullable();
            $table->string('approved_by', 26)->nullable();
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->string('rejected_by', 26)->nullable();
            $table->foreign('rejected_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'employee_id']);
            $table->index(['company_id', 'status_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
