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
        if (!Schema::hasTable('attendance_adjustments')) {
            Schema::create('attendance_adjustments', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('company_id', 26);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->string('attendance_id', 26)->nullable();
            $table->foreign('attendance_id')->references('id')->on('attendances')->onDelete('set null');
            $table->unsignedBigInteger('adjustment_type_id')->nullable();
            $table->decimal('hours_adjusted', 5, 2)->nullable();
            $table->text('reason')->nullable();
            $table->string('approved_by', 26)->nullable();
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->boolean('is_approved')->default(false);
            $table->string('created_by', 26)->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->string('updated_by', 26)->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_adjustments');
    }
};
