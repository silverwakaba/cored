<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    /**
     * Run the migrations.
     */
    public function up() : void{
        Schema::create('invoice', function (Blueprint $table){
            $table->id();
            
            $table->foreignId('supplier_id')->references('id')->on('supplier');
            $table->foreignId('purchase_order_id')->references('id')->on('purchase_order');
            $table->foreignId('base_status_id')->references('id')->on('base_requests');     // Base Module: Progress | Default: Draft > [In Progress, Not Approved] > Approval > Approved > [Finished / Canceled]
            $table->foreignId('base_work_type_id')->references('id')->on('base_requests');  // Base Module: Work Type
            $table->foreignId('base_tax_type_id')->references('id')->on('base_requests');   // Base Module: Tax Type

            // PO
            $table->string('po_file_path');

            // Invoice
            $table->string('invoice_number');
            $table->date('invoice_date');
            $table->decimal('invoice_value', total: 20, places: 4)->nullable(); // 16 digit + 4 decimal
            $table->string('invoice_file_path');
            $table->text('invoice_note')->nullable();

            // Surat jalan & Berita Acara
            $table->string('sjba_number');
            $table->string('sjba_file_path');
            $table->text('sjba_note')->nullable();
            
            // Faktur pajak
            $table->string('tax_invoice_number');
            $table->string('tax_invoice_file_path');
            $table->text('tax_invoice_note')->nullable();

            // Other info
            $table->string('other_name')->nullable();
            $table->string('other_number')->nullable();
            $table->string('other_file_path')->nullable();
            $table->text('other_note')->nullable();

            // Action by
            $table->ulid('created_by')->nullable(); $table->foreign('created_by')->references('id')->on('users');
            $table->ulid('updated_by')->nullable(); $table->foreign('updated_by')->references('id')->on('users');
            $table->ulid('deleted_by')->nullable(); $table->foreign('deleted_by')->references('id')->on('users');
            $table->ulid('verified_by')->nullable(); $table->foreign('verified_by')->references('id')->on('users');
            $table->ulid('unverified_by')->nullable(); $table->foreign('unverified_by')->references('id')->on('users');
            
            // Timestamp
            $table->timestamps();
            $table->softDeletes('deleted_at', precision: 0);
            $table->timestamp('verified_at', precision: 0)->nullable();
            $table->timestamp('unverified_at', precision: 0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void{
        Schema::dropIfExists('invoice');
    }
};
