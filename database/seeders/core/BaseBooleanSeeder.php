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
        BaseBoolean::insert([
            [
                'text'  => 'No',
                'value' => false,
            ],
            [
                'text'  => 'Yes',
                'value' => true,
            ],
        ]);
    }
}
