<?php

namespace App\Helpers;

// Helper
use App\Helpers\GeneralHelper;

// Model
use App\Models\User;

// Internal
use Illuminate\Support\Str;

class RBACHelper{
    // User prop
    public static function userProp($id, $prop = null){
        $data = User::with([
            'roles', 'permissions'
        ])->find($id);

        // Switch prop
        switch($prop){
            // ID
            case 'id': $datas = $data->id; break;

            // Role
            case 'role': $datas = $data->roles; break;

            // Permission
            case 'permission': $datas = $data->permissions; break;
            
            // Default
            default: $datas = $data; break;
        }

        // Return prop
        return $datas;
    }

    // Profile prop
    public static function profileProp($prop = null){
        // Switch prop
        switch($prop){
            // ID
            case 'id': $datas = auth()->user()->id; break;

            // Role
            case 'role': $datas = auth()->user()->roles; break;

            // Permission
            case 'permission': $datas = auth()->user()->permissions; break;
            
            // Default
            default: $datas = auth()->user(); break;
        }

        // Return prop
        return $datas;
    }

    // Role level
    public static function roleLevel($data, $type = null){
        // Pluck role name (Main source could be from user auth or role data list)
        $roles = collect($data)->pluck('name')->all();

        // Set role level
        $levels = [
            'Root'      => 1,
            'Admin'     => 2,
            'Moderator' => 3,
        ];

        // Highest role available (Root is higher compared to Admin, and so on)
        $highestRole = array_reduce($roles, function($carry, $role) use($levels){
            return ($carry === null || $levels[$role] < $levels[$carry]) ? $role : $carry;
        });

        // Get role level
        $levelRole = $levels[$highestRole];

        // Process type
        if($type == 'excluded'){
            // Get previous role name as exclusion
            // See: app\Repositories\EloquentUserRepository.php - role()
            $previousRoles = array_filter($levels, function($level) use($levelRole){
                return $level < $levelRole;
            });
    
            return array_keys($previousRoles);
        }

        // Return highest level
        return $levelRole;
    }

    // Compare role level
    public static function roleLevelCompare($data, $role){
        try{
            // Get data type and its data
            $datas = GeneralHelper::getType($data);

            // If level of loaded role is less than level of user role = false
            if(self::roleLevel($datas) < self::roleLevel($role)){
                return (bool) false;
            }

            // Otherwise = true
            return (bool) true;
        }
        catch(\Throwable $th){
            // Otherwise not declared
            return (bool) true;
        }
    }
}
