<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    /**
     * Run the migrations.
     */
    public function up() : void{
        Schema::create('users_requests', function (Blueprint $table){
            $table->id();
            $table->foreignId('base_requests_id')->references('id')->on('base_requests');
            $table->foreignId('users_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void{
        Schema::dropIfExists('users_requests');
    }
};
