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
        Schema::create('leave_carryovers', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('company_id', 26);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->string('employee_id', 26);
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->string('leave_type_id', 26);
            $table->foreign('leave_type_id')->references('id')->on('leave_types')->onDelete('cascade');
            $table->string('from_year', 4)->nullable();
            $table->string('to_year', 4)->nullable();
            $table->decimal('carryover_days', 8, 2)->nullable();
            $table->text('notes')->nullable();
            $table->string('created_by', 26)->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_carryovers');
    }
};
