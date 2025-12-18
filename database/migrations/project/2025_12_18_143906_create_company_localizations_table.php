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
        Schema::create('company_localizations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('company_id', 26)->unique();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->string('language_id', 26)->nullable();
            // Foreign key constraint will be added in a separate migration after languages table is created
            $table->string('currency_id', 26)->nullable();
            // Foreign key constraint will be added in a separate migration after currencies table is created
            $table->string('timezone_id', 26)->nullable();
            // Foreign key constraint will be added in a separate migration after timezones table is created
            $table->string('date_format', 50)->nullable();
            $table->string('time_format', 50)->nullable();
            $table->string('decimal_separator', 1)->nullable();
            $table->string('thousands_separator', 1)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_localizations');
    }
};
