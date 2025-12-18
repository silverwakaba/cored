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
        Schema::create('leave_balances', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('company_id', 26);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->string('employee_id', 26);
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->string('leave_type_id', 26);
            $table->foreign('leave_type_id')->references('id')->on('leave_types')->onDelete('cascade');
            $table->string('fiscal_year', 4)->nullable();
            $table->decimal('opening_balance', 8, 2)->nullable();
            $table->decimal('earned_balance', 8, 2)->nullable();
            $table->decimal('used_balance', 8, 2)->nullable();
            $table->decimal('carryover_balance', 8, 2)->nullable();
            $table->decimal('closing_balance', 8, 2)->nullable();
            $table->timestamp('last_updated_at')->nullable();
            $table->timestamps();

            $table->unique(['company_id', 'employee_id', 'leave_type_id', 'fiscal_year'], 'leave_balances_unique');
            $table->index(['company_id', 'employee_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_balances');
    }
};
