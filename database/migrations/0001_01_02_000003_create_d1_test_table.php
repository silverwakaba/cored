<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    // // Hardcode the connection to d1
    // protected $connection = 'd1';

    // /**
    //  * Run the migrations.
    //  */
    // public function up() : void{
    //     Schema::connection('d1')->create('d1_test', function (Blueprint $table){
    //         $table->id();
    //         $table->string('column')->nullable();
    //     });
    // }

    // /**
    //  * Reverse the migrations.
    //  */
    // public function down() : void{
    //     Schema::connection('d1')->dropIfExists('d1_test');
    // }
};
