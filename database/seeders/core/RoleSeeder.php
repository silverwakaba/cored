<?php

namespace Database\Seeders\Core;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Core\Role;

class RoleSeeder extends Seeder{
    /**
     * Run the database seeds.
     */
    public function run() : void{
        // 1
        Role::create([
            'name' => 'Root',
        ])->givePermissionTo(['isRoot']);

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


