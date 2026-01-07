<?php

namespace Database\Seeders\Project;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder{
    /**
     * Run the database seeds.
     */
    public function run() : void{
        Role::create([
            'name' => 'Supplier',
            'guard_name' => 'web', // Match with User model guard
        ]);
    }
}
