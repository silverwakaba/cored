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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            // Tenant Reference
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            
            // Payment Details
            $table->enum('type', ['credit_card', 'bank_transfer', 'e_wallet']);
            
            // For Credit Cards
            $table->string('card_last_four', 4)->nullable();
            $table->string('card_brand', 50)->nullable();
            $table->date('card_expiry')->nullable();
            
            // For Bank Transfer
            $table->string('bank_code', 10)->nullable();
            $table->string('account_number', 50)->nullable();
            $table->string('account_holder')->nullable();
            
            // For E-Wallet
            $table->string('wallet_provider', 100)->nullable();
            $table->string('wallet_account')->nullable();
            
            // Status
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            
            // Audit
            $table->timestamps();
            
            $table->index('tenant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};

