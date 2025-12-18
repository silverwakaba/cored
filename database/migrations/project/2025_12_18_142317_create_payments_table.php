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
        Schema::create('payments', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('invoice_id', 26);
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('restrict');
            $table->string('company_id', 26);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->decimal('amount_paid', 10, 2)->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->string('transaction_id', 100)->nullable();
            $table->unsignedBigInteger('status_id')->nullable();
            $table->timestamps();

            $table->index('invoice_id');
            $table->index('company_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
