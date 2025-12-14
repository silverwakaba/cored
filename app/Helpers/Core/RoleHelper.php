<?php

namespace App\Helpers\Core;

class RoleHelper{
    // Get role level
    public static function level($data){
        // Pluck role name
        $roles = collect($data)->pluck('name')->all();

        // Set role level
        $levels = [
            'Root'      => 1,
            'Admin'     => 2,
            'Moderator' => 3,
        ];

        // Search role
        $lowestRole = array_reduce($roles, function($carry, $role) use ($levels){
            return ($carry === null || $levels[$role] < $levels[$carry]) ? $role : $carry;
        });

        // Return response
        return $levels[$lowestRole];
    }

    // Compare level
    public static function compareLevel($data, $role){
        try{
            // If level of loaded role is less than level of user role = false
            if(self::level([$data]) < self::level($role)){
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
