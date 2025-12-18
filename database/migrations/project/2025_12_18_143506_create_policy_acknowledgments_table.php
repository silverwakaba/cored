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
        Schema::create('policy_acknowledgments', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('company_id', 26);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->string('employee_id', 26);
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->unsignedBigInteger('policy_id')->nullable();
            $table->string('policy_name')->nullable();
            $table->timestamp('acknowledged_date')->nullable();
            $table->string('acknowledged_by', 26)->nullable();
            $table->foreign('acknowledged_by')->references('id')->on('users')->onDelete('set null');
            $table->integer('version_number')->nullable();
            $table->timestamp('reminder_sent_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['company_id', 'employee_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('policy_acknowledgments');
    }
};
