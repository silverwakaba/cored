<?php

namespace Database\Seeders\Core;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Core\BaseModule;

class BaseModuleSeeder extends Seeder{
    /**
     * Run the database seeds.
     * 
     * This seed data is generated synthetically by AI, adapting to the needs of the document's state.
     * Some data may not be used, but I recommend leaving it untouched. It may be needed at some point.
     */
    public function run() : void{
        BaseModule::insert([
            ['name' => "Authentication"],
            ['name' => "Account Management"],
            ['name' => "Progress"],
            ['name' => "Approval"],
            ['name' => "Payment"],
        ]);
    }
}
