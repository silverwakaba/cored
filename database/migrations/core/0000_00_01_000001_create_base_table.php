<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    /**
     * Run the migrations.
     */
    public function up() : void{
        // Boolean
        Schema::create('base_boolean', function (Blueprint $table){
            $table->id();
            $table->string('text');
            $table->boolean('value')->unique();
        });
        
        // Module
        Schema::create('base_modules', function (Blueprint $table){
            $table->id();
            $table->string('name');
            $table->boolean('is_active')->default(true);
        });

        // Request
        Schema::create('base_requests', function (Blueprint $table){
            $table->id();
            $table->foreignId('base_modules_id')->references('id')->on('base_modules');
            $table->string('name');
            $table->json('detail')->nullable();
            $table->boolean('is_active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void{
        Schema::dropIfExists('base_boolean');
        Schema::dropIfExists('base_requests');
        Schema::dropIfExists('base_modules');
    }
};
