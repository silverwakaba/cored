<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up() : void{
        // Table for menu
        Schema::create('menus', function (Blueprint $table){
            $table->ulid('id')->primary();
            $table->ulid('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('menus')->onDelete('cascade');
            $table->string('name');
            $table->string('icon')->nullable();
            $table->string('route')->nullable();
            $table->string('type')->comment('h = Header | p = Parent | c = Child.');
            $table->integer('order')->default(1);
            $table->boolean('is_authenticate')->default(false)->nullable();
            $table->boolean('is_guest_only')->default(false)->nullable();
        });

        // Pivot table for menu-role relationship
        Schema::create('menu_roles', function (Blueprint $table){
            $table->ulid('menu_id');
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
            $table->ulid('role_id');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->primary(['menu_id', 'role_id']);
        });

        // Pivot table for menu-user includes (users who can see menu even without role)
        Schema::create('menu_user_includes', function (Blueprint $table){
            $table->ulid('menu_id');
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
            $table->ulid('user_id');
            // Foreign key to users will be added in a later migration (after users table is created)
            $table->primary(['menu_id', 'user_id']);
        });

        // Pivot table for menu-user excludes (users who cannot see menu even with role)
        Schema::create('menu_user_excludes', function (Blueprint $table){
            $table->ulid('menu_id');
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
            $table->ulid('user_id');
            // Foreign key to users will be added in a later migration (after users table is created)
            $table->primary(['menu_id', 'user_id']);
        });
    }

    public function down() : void{
        Schema::dropIfExists('menu_user_excludes');
        Schema::dropIfExists('menu_user_includes');
        Schema::dropIfExists('menu_roles');
        Schema::dropIfExists('menus');
    }
};
