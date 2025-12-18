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
        Schema::create('entitlements', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('subscription_id', 26);
            $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('cascade');
            $table->string('feature_name')->nullable();
            $table->integer('limit_value')->nullable();
            $table->integer('current_usage')->default(0);
            $table->unsignedBigInteger('reset_frequency_id')->nullable();
            $table->timestamp('last_reset_at')->nullable();
            $table->timestamps();

            $table->index('subscription_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entitlements');
    }
};
