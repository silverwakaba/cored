<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration updates column types for encrypted fields.
     * Encrypted values are stored as strings in the database,
     * so decimal and date columns need to be changed to text/string.
     */
    public function up(): void
    {
        // Update employees table - date_of_birth
        Schema::table('employees', function (Blueprint $table) {
            $table->text('date_of_birth')->nullable()->change();
        });

        // Update payroll_entries table - salary fields
        Schema::table('payroll_entries', function (Blueprint $table) {
            $table->text('base_salary')->nullable()->change();
            $table->text('gross_salary')->nullable()->change();
            $table->text('net_salary')->nullable()->change();
        });

        // Update offer_letters table - offered_salary
        Schema::table('offer_letters', function (Blueprint $table) {
            $table->text('offered_salary')->nullable()->change();
        });

        // Update pay_adjustments table - amount
        Schema::table('pay_adjustments', function (Blueprint $table) {
            $table->text('amount')->nullable()->change();
        });

        // Update earnings table - amount
        Schema::table('earnings', function (Blueprint $table) {
            $table->text('amount')->nullable()->change();
        });

        // Update deductions table - amount
        Schema::table('deductions', function (Blueprint $table) {
            $table->text('amount')->nullable()->change();
        });

        // Update taxes table - amount
        Schema::table('taxes', function (Blueprint $table) {
            $table->text('amount')->nullable()->change();
        });

        // Update reimbursements table - amount
        Schema::table('reimbursements', function (Blueprint $table) {
            $table->text('amount')->nullable()->change();
        });

        // Update benefit_enrollments table - coverage_amount and premium_amount
        Schema::table('benefit_enrollments', function (Blueprint $table) {
            $table->text('coverage_amount')->nullable()->change();
            $table->text('premium_amount')->nullable()->change();
        });

        // Update overtime_records table - amount
        Schema::table('overtime_records', function (Blueprint $table) {
            $table->text('amount')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert employees table - date_of_birth
        Schema::table('employees', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable()->change();
        });

        // Revert payroll_entries table - salary fields
        Schema::table('payroll_entries', function (Blueprint $table) {
            $table->decimal('base_salary', 12, 2)->nullable()->change();
            $table->decimal('gross_salary', 12, 2)->nullable()->change();
            $table->decimal('net_salary', 12, 2)->nullable()->change();
        });

        // Revert offer_letters table - offered_salary
        Schema::table('offer_letters', function (Blueprint $table) {
            $table->decimal('offered_salary', 12, 2)->nullable()->change();
        });

        // Revert pay_adjustments table - amount
        Schema::table('pay_adjustments', function (Blueprint $table) {
            $table->decimal('amount', 12, 2)->nullable()->change();
        });

        // Revert earnings table - amount
        Schema::table('earnings', function (Blueprint $table) {
            $table->decimal('amount', 12, 2)->nullable()->change();
        });

        // Revert deductions table - amount
        Schema::table('deductions', function (Blueprint $table) {
            $table->decimal('amount', 12, 2)->nullable()->change();
        });

        // Revert taxes table - amount
        Schema::table('taxes', function (Blueprint $table) {
            $table->decimal('amount', 12, 2)->nullable()->change();
        });

        // Revert reimbursements table - amount
        Schema::table('reimbursements', function (Blueprint $table) {
            $table->decimal('amount', 12, 2)->nullable()->change();
        });

        // Revert benefit_enrollments table - coverage_amount and premium_amount
        Schema::table('benefit_enrollments', function (Blueprint $table) {
            $table->decimal('coverage_amount', 12, 2)->nullable()->change();
            $table->decimal('premium_amount', 12, 2)->nullable()->change();
        });

        // Revert overtime_records table - amount
        Schema::table('overtime_records', function (Blueprint $table) {
            $table->decimal('amount', 10, 2)->nullable()->change();
        });
    }
};

