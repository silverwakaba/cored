<?php

namespace Database\Seeders\Project;

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
        $projectModule = BaseModule::where('name', 'Project')->first();
        $businessModule = BaseModule::where('name', 'Business')->first();
        $financeModule = BaseModule::where('name', 'Finance')->first();
        $settingsModule = BaseModule::where('name', 'Settings')->first();

        BaseRequest::insert([
            // Project
            [
                'base_modules_id'   => $projectModule->id,
                'name'              => "Work Type",
            ],

            // Business
            [
                'base_modules_id'   => $businessModule->id,
                'name'              => "Business Entity",
            ],
            [
                'base_modules_id'   => $businessModule->id,
                'name'              => "Qualification",
            ],

            // Finance
            [
                'base_modules_id'   => $financeModule->id,
                'name'              => "Bank",
            ],
            [
                'base_modules_id'   => $financeModule->id,
                'name'              => "Currency",
            ],
            [
                'base_modules_id'   => $financeModule->id,
                'name'              => "Tax Type",
            ],

            // Settings
            [
                'base_modules_id'   => $settingsModule->id,
                'name'              => "Status",
            ],
        ]);
    }
}
