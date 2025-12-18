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
        Schema::create('owners', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('company_id', 26)->unique();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->string('user_id', 26); // ULID from Core users table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('role_title', 100)->nullable();
            $table->timestamps();

            $table->index('company_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owners');
    }
};
