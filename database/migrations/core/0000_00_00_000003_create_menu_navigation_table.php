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
            $table->string('type')->comment('h = header | p = parent | c = child.');
            $table->integer('order')->default(1);
            $table->boolean('is_authenticate')->nullable();
            $table->boolean('is_guest_only')->nullable();
        });

        // Pivot table for menu-role relationship
        Schema::create('menu_roles', function (Blueprint $table){
            $table->foreignId('menu_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->primary(['menu_id', 'role_id']);
        });

        // Pivot table for menu-user includes (users who can see menu even without role)
        Schema::create('menu_user_includes', function (Blueprint $table){
            $table->foreignId('menu_id')->constrained()->onDelete('cascade');
            $table->ulid('user_id');
            $table->primary(['menu_id', 'user_id']);
        });

        // Pivot table for menu-user excludes (users who cannot see menu even with role)
        Schema::create('menu_user_excludes', function (Blueprint $table){
            $table->foreignId('menu_id')->constrained()->onDelete('cascade');
            $table->ulid('user_id');
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
