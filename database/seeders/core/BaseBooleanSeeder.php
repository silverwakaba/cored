<?php

namespace Database\Seeders\Core;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Core\BaseBoolean;

class BaseBooleanSeeder extends Seeder{
    /**
     * Run the database seeds.
     */
    public function run() : void{
        BaseBoolean::create([
            'text'  => 'No',
            'value' => false,
        ]);
        
        BaseBoolean::create([
            'text'  => 'Yes',
            'value' => true,
        ]);
    }
}
