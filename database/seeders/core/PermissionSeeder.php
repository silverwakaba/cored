<?php

namespace Database\Seeders\Core;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder{
    /**
     * Run the database seeds.
     */
    public function run() : void{
        Permission::create([
            'name' => 'isRoot',
        ]);

        Permission::create([
            'name' => 'isAdmin',
        ]);

        Permission::create([
            'name' => 'isModerator',
        ]);

        Permission::create([
            'name' => 'isUser',
        ]);
    }
}


