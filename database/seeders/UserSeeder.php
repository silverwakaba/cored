<?php

namespace Database\Seeders;

use App\Models\User;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder{
    /**
     * Run the database seeds.
     */
    public function run() : void{
        // Root
        User::create([
            'name'      => 'Root',
            'email'     => 'a@a.a',
            'password'  => bcrypt('123456789'),
        ])->assignRole('Root');

        // Azhar
        User::create([
            'name'      => 'Azhar',
            'email'     => 'b@a.a',
            'password'  => bcrypt('123456789'),
        ])->assignRole('Admin');
    }
}
