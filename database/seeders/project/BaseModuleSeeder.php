<?php

namespace Database\Seeders\Project;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Core\BaseModule;

class BaseModuleSeeder extends Seeder{
    /**
     * Run the database seeds.
     */
    public function run() : void{
        BaseModule::insert([
            // 3
            ['name' => "Project"],
            
            // 4
            ['name' => "Business"],

            // 5
            ['name' => "Finance"],

            // 6
            ['name' => "Settings"],
        ]);
    }
}
