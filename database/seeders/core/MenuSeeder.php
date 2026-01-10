<?php

namespace Database\Seeders\Core;

use App\Models\Core\Menu;
use App\Models\Core\User;
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
            'icon'      => 'fas fa-house-user',
            'type'      => 'p',
            'parent_id' => $headerGeneral->id,
            'order'     => 1,
        ]);

        // Create child - Home Page
        $childGeneral = Menu::create([
            'name'      => 'Home Page',
            'icon'      => 'fas fa-door-open',
            'route'     => 'fe.page.index',
            'type'      => 'c',
            'parent_id' => $parentGeneral->id,
            'order'     => 1,
        ]);

        // Create child - Auth Index
        $childAuth = Menu::create([
            'name'          => 'Auth',
            'icon'          => 'fas fa-key',
            'route'         => 'fe.page.auth',
            'type'          => 'c',
            'parent_id'     => $parentGeneral->id,
            'order'         => $childGeneral->order + 1,
            'is_guest_only' => true,
        ]);

        // Create child - CTA Index
        $childCTA = Menu::create([
            'name'      => 'CTA',
            'icon'      => 'fas fa-newspaper',
            'route'     => 'fe.page.cta',
            'type'      => 'c',
            'parent_id' => $parentGeneral->id,
            'order'     => $childAuth->order + 1,
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
        $childApps = Menu::create([
            'name'      => 'Apps',
            'icon'      => 'fas fa-home',
            'route'     => 'fe.apps.index',
            'type'      => 'p',
            'parent_id' => $headerApps->id,
            'order'     => 1,
        ]);

        // Create parent - Menu
        $childMenu = Menu::create([
            'name'      => 'Menu',
            'icon'      => 'fas fa-bars',
            'route'     => 'fe.apps.menu.index',
            'type'      => 'p',
            'parent_id' => $headerApps->id,
            'order'     => $childApps->order + 1,
        ]);

        /**
         * Apps - Base
        */

        // Create parent - Base
        $parentAppsBase = Menu::create([
            'name'      => 'Base',
            'icon'      => 'fas fa-database',
            'type'      => 'p',
            'parent_id' => $headerApps->id,
            'order'     => 999998,
        ]);

        // Create child - Base - Module
        $childAppsBaseModule = Menu::create([
            'name'      => 'Module',
            'route'     => 'fe.apps.base.module.index',
            'type'      => 'c',
            'parent_id' => $parentAppsBase->id,
            'order'     => 1,
        ]);

        // Create child - Base - Request
        $childAppsBaseRequest = Menu::create([
            'name'      => 'Request',
            'route'     => 'fe.apps.base.request.index',
            'type'      => 'c',
            'parent_id' => $parentAppsBase->id,
            'order'     => $childAppsBaseModule->order + 1,
        ]);

        /**
         * Apps - RBAC
        */

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

        // App - Get roles by name since we're using ULID now
        $roles = \App\Models\Core\Role::whereIn('name', ['Root', 'Admin', 'Moderator'])->get();
        $roleIds = $roles->pluck('id')->toArray();
        
        $childMenu->roles()->attach($roleIds);
        $parentAppsBase->roles()->attach($roleIds);
        $parentAppsRBAC->roles()->attach($roleIds);

        /**
         * Exclude user from menu items regarding roles (inclusion have the similar application)
        */

        // $excludeUser = User::where('email', 'a@a.a')->first();
        // $parentAppsBase->excludedUsers()->attach($excludeUser);
    }
}
