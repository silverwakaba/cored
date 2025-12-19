<?php

namespace Database\Seeders\Core;

use App\Models\Core\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder{
    public function run() : void{
        /**
         * General
        */

        // Create header
        $headerGeneral = Menu::create([
            'name'  => 'Main Menu',
            'type'  => 'h',
            'order' => 1,
        ]);

        // Create parent
        $parentGeneral = Menu::create([
            'name'      => 'Main Menu',
            'icon'      => 'fas fa-gauge',
            'type'      => 'p',
            'parent_id' => $headerGeneral->id,
            'order'     => 1,
        ]);

        // Create child
        $childGeneral = Menu::create([
            'name'      => 'Home',
            'route'     => 'fe.page.index',
            'type'      => 'c',
            'parent_id' => $parentGeneral->id,
            'order'     => 1,
        ]);

        /**
         * Apps
        */

        // Create header
        $headerApps = Menu::create([
            'name'              => 'App',
            'type'              => 'h',
            'order'             => $headerGeneral->order + 1,
            'is_authenticate'   => true,
        ]);

        // Create parent - General Apps
        $childGeneral = Menu::create([
            'name'      => 'Apps',
            'icon'      => 'fas fa-home',
            'route'     => 'fe.apps.index',
            'type'      => 'p',
            'parent_id' => $headerApps->id,
            'order'     => 1,
        ]);

        // Create parent - RBAC
        $parentAppsRBAC = Menu::create([
            'name'      => 'RBAC',
            'icon'      => 'fas fa-sitemap',
            'type'      => 'p',
            'parent_id' => $headerApps->id,
            'order'     => 999999,
        ]);

        // Create child - RBAC - Role
        $childAppsRBACRole = Menu::create([
            'name'      => 'Role',
            'route'     => 'fe.apps.rbac.role.index',
            'type'      => 'c',
            'parent_id' => $parentAppsRBAC->id,
            'order'     => 1,
        ]);

        // Create child - RBAC - Permission
        $childAppsRBACPermission = Menu::create([
            'name'      => 'Permission',
            'route'     => 'fe.apps.rbac.permission.index',
            'type'      => 'c',
            'parent_id' => $parentAppsRBAC->id,
            'order'     => $childAppsRBACRole->order + 1,
        ]);

        // Create child - RBAC - UAC
        $childAppsRBACUAC = Menu::create([
            'name'      => 'UAC',
            'route'     => 'fe.apps.rbac.uac.index',
            'type'      => 'c',
            'parent_id' => $parentAppsRBAC->id,
            'order'     => $childAppsRBACPermission->order + 1,
        ]);

        /**
         * Assign roles to menu items
        */

        // App - RBAC
        $parentAppsRBAC->roles()->attach([1, 2, 3]);
    }
}
