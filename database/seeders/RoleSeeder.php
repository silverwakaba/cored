<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder{
    /**
     * Run the database seeds.
     */
    public function run() : void{
        // 1
        Role::create([
            'name' => 'Root',
        ]);

        // 2
        Role::create([
            'name' => 'Admin',
        ]);

        // 3
        Role::create([
            'name' => 'Moderator',
        ]);

        // 4
        Role::create([
            'name' => 'User',
        ]);
    }
}
