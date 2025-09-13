<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    /**
     * Run the migrations.
     */
    public function up() : void{
        Schema::create('personal_note_shares', function (Blueprint $table){
            $table->foreignId('personal_notes_id')->references('id')->on('personal_notes');
            $table->foreignId('shared_to_users_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void{
        Schema::dropIfExists('personal_note_shares');
    }
};
