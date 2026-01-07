<?php

namespace Database\Seeders\Project;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder{
    /**
     * Seed the application's database.
     */
    public function run() : void{
        $this->call([
            BaseModuleSeeder::class,
            BaseRequestSeeder::class,
            RoleSeeder::class,
        ]);
    }
}
