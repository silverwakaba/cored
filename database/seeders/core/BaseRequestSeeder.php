<?php

namespace Database\Seeders\Core;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Core\BaseRequest;
use App\Models\Core\BaseModule;

class BaseRequestSeeder extends Seeder{
    /**
     * Run the database seeds.
     */
    public function run() : void{
        // Get module IDs
        $authenticationModule = BaseModule::where('name', 'Authentication')->first();
        $accountManagementModule = BaseModule::where('name', 'Account Management')->first();

        BaseRequest::insert([
            [
                'base_modules_id' => $authenticationModule->id,
                'name' => "Email Verification"
            ],
            [
                'base_modules_id' => $accountManagementModule->id,
                'name' => "Email Change"
            ],
            [
                'base_modules_id' => $authenticationModule->id,
                'name' => "Password Reset"
            ],
        ]);
    }
}


