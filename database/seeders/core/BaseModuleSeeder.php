<?php

namespace Database\Seeders\Core;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Core\BaseModule;

class BaseModuleSeeder extends Seeder{
    /**
     * Run the database seeds.
     */
    public function run() : void{
        BaseModule::insert([
            ['name' => "Authentication"],
            ['name' => "Account Management"],
        ]);
    }
}
