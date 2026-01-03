<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up() : void{
        // Add foreign key constraint for menu_user_includes
        Schema::table('menu_user_includes', function (Blueprint $table){
            $table->foreign('user_id')->references('id')->on('users');
        });

        // Add foreign key constraint for menu_user_excludes
        Schema::table('menu_user_excludes', function (Blueprint $table){
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down() : void{
        Schema::table('menu_user_excludes', function (Blueprint $table){
            $table->dropForeign(['user_id']);
        });

        Schema::table('menu_user_includes', function (Blueprint $table){
            $table->dropForeign(['user_id']);
        });
    }
};
