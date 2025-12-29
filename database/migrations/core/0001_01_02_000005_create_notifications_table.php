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
            $table->id();
            $table->foreignId('base_requests_id')->references('id')->on('base_requests')->onDelete('cascade');
            $table->ulid('users_id');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            $table->json('data')->comment('JSON payload containing notification content (title, message, action_url, etc.)');
            $table->timestamp('read_at')->nullable()->comment('Timestamp when the notification was read by the user (null if unread)');
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
