<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up() : void{
        Schema::create('item_masters', function (Blueprint $table){
            $table->id();
            $table->string('name');
            $table->text('description');
        });

        Schema::create('item_details', function (Blueprint $table){
            $table->id();
            $table->foreignId('item_masters_id')->references('id')->on('item_masters');
            $table->string('name');
            $table->text('description');
        });
    }

    public function down() : void{
        Schema::dropIfExists('item_masters');
        Schema::dropIfExists('item_details');
    }
};
