<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    /**
     * Run the migrations.
     */
    public function up() : void{
        Schema::create('navigations', function (Blueprint $table){
            $table->id();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_header')->default(false);
            $table->integer('order')->default(0);
            $table->string('title');
            $table->string('route')->nullable();
            $table->string('icon')->nullable();
            $table->string('roles')->nullable();
        });

        Schema::table('navigations', function (Blueprint $table){
            $table->after('id', function (Blueprint $table){
                $table->foreignId('parent_id')->nullable()->constrained()->references('id')->on('navigations')->onDelete('cascade');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void{
        Schema::dropIfExists('navigations');
    }
};
