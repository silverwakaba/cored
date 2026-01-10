<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    /**
     * Run the migrations.
     */
    public function up() : void{
        Schema::create('notifications', function (Blueprint $table){
            $table->ulid('id')->primary();
            $table->ulid('base_requests_id')->comment('Request module id');
            $table->foreign('base_requests_id')->references('id')->on('base_requests');
            $table->ulid('base_statuses_id')->comment('Request status id');
            $table->foreign('base_statuses_id')->references('id')->on('base_requests');
            $table->ulid('users_id');
            $table->foreign('users_id')->references('id')->on('users');
            $table->json('data')->nullable()->comment('JSON payload containing the content');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void{
        Schema::dropIfExists('notifications');
    }
};
