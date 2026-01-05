<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    /**
     * Run the migrations.
     */
    public function up() : void{
        Schema::create('ttbp_master', function (Blueprint $table){
            $table->id();
            
            $table->foreignId('supplier_id')->references('id')->on('supplier');
            $table->foreignId('base_currency_id')->references('id')->on('base_requests');
            $table->foreignId('base_status_ttbp_id')->references('id')->on('base_requests');    // Base Module: Progress | Default: Draft > [In Progress, Not Approved] > Approval > Approved > [Finished / Canceled]
            $table->foreignId('base_status_payment_id')->references('id')->on('base_requests'); // Base Module: Payment  | Default: On Hold

            $table->string('number');
            $table->date('date');
            $table->date('due_date');
            $table->smallInteger('credit_day');
            $table->decimal('total', total: 20, places: 4)->nullable(); // 16 digit + 4 decimal

            $table->string('bpb_file_path')->nullable();
            $table->string('bpj_file_path')->nullable();
            $table->text('note')->nullable();

            // Action by
            $table->ulid('created_by')->nullable(); $table->foreign('created_by')->references('id')->on('users');
            $table->ulid('updated_by')->nullable(); $table->foreign('updated_by')->references('id')->on('users');
            $table->ulid('deleted_by')->nullable(); $table->foreign('deleted_by')->references('id')->on('users');
            $table->ulid('verified_by')->nullable(); $table->foreign('verified_by')->references('id')->on('users');
            $table->ulid('canceled_by')->nullable(); $table->foreign('canceled_by')->references('id')->on('users');
            
            // Timestamp
            $table->timestamps();
            $table->softDeletes('deleted_at', precision: 0);
            $table->timestamp('verified_at', precision: 0)->nullable();
            $table->timestamp('canceled_at', precision: 0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void{
        Schema::dropIfExists('ttbp_master');
    }
};
