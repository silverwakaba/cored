<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    /**
     * Run the migrations.
     */
    public function up() : void{
        Schema::create('ttbp_detail', function (Blueprint $table){
            $table->id();
            $table->foreignId('invoice_id')->references('id')->on('invoice');
            $table->foreignId('ttbp_master_id')->references('id')->on('ttbp_master');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void{
        Schema::dropIfExists('ttbp_detail');
    }
};
