<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up() : void{
        // Table for menu
        Schema::create('menus', function (Blueprint $table){
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('menus')->onDelete('cascade');
            $table->string('name');
            $table->string('icon')->nullable();
            $table->string('route')->nullable();
            $table->string('type')->comment('h = header | p = parent | c = Child.');
            $table->integer('order')->default(1);
            $table->boolean('is_authenticate')->nullable();
        });

        // Pivot table for menu-role relationship
        Schema::create('menu_roles', function (Blueprint $table){
            $table->foreignId('menu_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->primary(['menu_id', 'role_id']);
        });
    }

    public function down() : void{
        Schema::dropIfExists('menus');
        Schema::dropIfExists('menu_roles');
    }
};
