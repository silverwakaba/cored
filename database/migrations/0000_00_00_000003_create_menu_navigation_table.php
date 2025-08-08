<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up() : void{
        Schema::create('menus', function (Blueprint $table){
            $table->id();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->string('route')->nullable();
            $table->string('type'); // header, parent, child
            $table->foreignId('parent_id')->nullable()->constrained('menus')->onDelete('cascade');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // Pivot table for menu-role relationship
        Schema::create('menu_role', function (Blueprint $table){
            $table->foreignId('menu_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->primary(['menu_id', 'role_id']);
        });
    }

    public function down() : void{
        Schema::dropIfExists('menu_role');
        Schema::dropIfExists('menus');
    }
};
