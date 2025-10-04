<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder{
    public function run() : void{
        // Create header | Fixed
        $headerGeneral = Menu::create([
            'name'  => 'Main Menu',
            'type'  => 'h',
            'order' => 1,
        ]);

        // Create parent | For general
        $parentGeneral = Menu::create([
            'name'      => 'Home',
            'icon'      => 'fas fa-tachometer-alt',
            'type'      => 'p',
            'parent_id' => $headerGeneral->id,
            'order'     => 1,
        ]);

        // Create child | For general
        $childGeneral = Menu::create([
            'name'      => 'Go Home',
            'icon'      => 'far fa-circle',
            'route'     => 'xxx',
            'type'      => 'c',
            'parent_id' => $parentGeneral->id,
            'order'     => 1,
        ]);

        // Assign roles to menu items
        // $headerGeneral->roles()->attach(1);
        // $parent->roles()->attach(1);
        // $child->roles()->attach(1);
    }
}
