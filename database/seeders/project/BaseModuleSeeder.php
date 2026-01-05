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
            // // 3
            // ['name' => "Payment Status"],
            
            // 4
            ['name' => "Business Entity"],
            
            // 5
            ['name' => "Bank"],

            // 6
            ['name' => "Currency"],

            // 7
            ['name' => "Qualification"],
            
            // 8
            ['name' => "Work Type"],
            
            // 9
            ['name' => "Tax Type"],
        ]);
    }
}
