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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('company_id', 26)->unique();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->string('plan_id', 26);
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('restrict');
            $table->unsignedBigInteger('status_id')->nullable();
            $table->date('subscription_start_date');
            $table->date('subscription_end_date')->nullable();
            $table->date('trial_end_date')->nullable();
            $table->boolean('auto_renew')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index(['company_id', 'status_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
