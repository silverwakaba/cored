<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    /**
     * Run the migrations.
     */
    public function up() : void{
        Schema::create('purchase_order', function (Blueprint $table){
            $table->id();

            // PO that will be imported are only PO that have valid suppliers on the vendor portal (filter suppliers by code)
            // If a PO does not have a valid supplier, it will not be imported into the vendor portal
            $table->foreignId('supplier_id')->references('id')->on('supplier');
            $table->foreignId('base_status_id')->references('id')->on('base_requests');     // Base Module: Progress | Default: Draft > [In Progress, Not Approved] > Approval > Approved > [Finished / Canceled]
            $table->foreignId('base_currency_id')->references('id')->on('base_requests');   // Base Module: Currency
            
            $table->string('number')->unique(); // PO number
            $table->date('date'); // PO date
            
            $table->decimal('value', total: 20, places: 4); // 16 digit + 4 decimal
            $table->decimal('vat', total: 20, places: 4); // 16 digit + 4 decimal

            // Action by
            $table->ulid('created_by')->nullable(); $table->foreign('created_by')->references('id')->on('users');
            $table->ulid('updated_by')->nullable(); $table->foreign('updated_by')->references('id')->on('users');
            $table->ulid('deleted_by')->nullable(); $table->foreign('deleted_by')->references('id')->on('users');
            
            // Timestamp
            $table->timestamps();
            $table->softDeletes('deleted_at', precision: 0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void{
        Schema::dropIfExists('purchase_order');
    }
};
