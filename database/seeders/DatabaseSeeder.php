<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder{
    /**
     * Seed the application's database.
     */
    public function run() : void{
        // Run core seeders first
        $this->call(\Database\Seeders\Core\DatabaseSeeder::class);
        
        // Run project-specific seeders afterwards
        $this->call(\Database\Seeders\Project\DatabaseSeeder::class);
    }
}
