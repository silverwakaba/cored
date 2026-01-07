<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    /**
     * Run the migrations.
     */
    public function up() : void{
        Schema::create('supplier', function (Blueprint $table){
            $table->id();
            
            // Mostly nullable because suppliers can be created/imported from PMO without having a vendor portal user
            // There should be an "Add Supplier" and "Bind Supplier" menu because of this
            $table->ulid('users_id')->nullable(); $table->foreign('users_id')->references('id')->on('users');
            $table->foreignId('base_qualification_id')->nullable()->references('id')->on('base_requests');      // Base Module: Qualification
            $table->foreignId('base_business_entity_id')->nullable()->references('id')->on('base_requests');    // Base Module: Business Entity
            $table->foreignId('base_bank_id')->nullable()->references('id')->on('base_requests');               // Base Module: Bank
            
            $table->string('code')->nullable()->unique();
            $table->string('name');
            $table->smallInteger('credit_day');
            $table->longText('address_1')->nullable();
            $table->longText('address_2')->nullable();

            $table->string('telp')->nullable();
            $table->string('fax')->nullable();

            $table->string('npwp')->nullable();
            $table->longText('npwp_address')->nullable();

            $table->string('bank_account_name')->nullable(); // On behalf of
            $table->string('bank_account_number')->nullable();

            $table->string('pkp')->nullable(); // PKP name
            $table->string('nib')->nullable(); // NIB number
            $table->string('notes')->nullable();
            $table->string('statement_file_path')->nullable();

            // Status
            $table->boolean('is_active')->default(false);

            // Action by
            $table->ulid('created_by')->nullable(); $table->foreign('created_by')->references('id')->on('users');
            $table->ulid('updated_by')->nullable(); $table->foreign('updated_by')->references('id')->on('users');
            
            // Timestamp
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void{
        Schema::dropIfExists('supplier');
    }
};
