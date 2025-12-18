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
        Schema::create('employees', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('company_id', 26);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->string('user_id', 26)->nullable(); // ULID from Core users table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->string('employee_code', 50)->nullable();
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);
            $table->string('email')->nullable();
            $table->string('personal_email')->nullable();
            $table->string('phone_primary', 20)->nullable();
            $table->string('phone_secondary', 20)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender', 50)->nullable();
            $table->string('nationality', 100)->nullable();
            $table->string('ssn_encrypted', 500)->nullable();
            $table->string('passport_encrypted', 500)->nullable();
            $table->string('marital_status', 50)->nullable();
            // Employment details
            $table->unsignedBigInteger('employment_type_id')->nullable();
            $table->unsignedBigInteger('status_id')->nullable();
            $table->string('job_title')->nullable();
            $table->string('department_id', 26)->nullable();
            // Foreign key constraint will be added in a separate migration after departments table is created
            $table->string('manager_id', 26)->nullable();
            // Foreign key constraint (self-reference) will be added in a separate migration after employees table is created
            $table->date('date_of_joining');
            $table->date('date_of_exit')->nullable();
            $table->string('location', 100)->nullable();
            $table->string('work_phone', 20)->nullable();
            $table->string('office_email')->nullable();
            // Compensation
            $table->string('salary_encrypted', 500)->nullable();
            $table->string('salary_currency', 3)->nullable();
            $table->unsignedBigInteger('pay_frequency_id')->nullable();
            $table->string('bank_account_encrypted', 500)->nullable();
            // Address
            $table->string('residential_address_line_1')->nullable();
            $table->string('residential_address_line_2')->nullable();
            $table->string('residential_city', 100)->nullable();
            $table->string('residential_state', 100)->nullable();
            $table->string('residential_postal_code', 20)->nullable();
            $table->string('residential_country', 100)->nullable();
            // Metadata
            $table->boolean('is_active')->default(true);
            $table->string('created_by', 26)->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->string('updated_by', 26)->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['company_id', 'employee_code']);
            $table->index('company_id');
            $table->index(['company_id', 'email', 'deleted_at']);
            $table->index(['company_id', 'department_id']);
            $table->index(['company_id', 'manager_id']);
            $table->index(['company_id', 'status_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
