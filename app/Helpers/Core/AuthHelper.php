<?php

namespace App\Helpers\Core;

// Helper
use App\Helpers\Core\GeneralHelper;

// Model
use App\Models\Core\User;

// Internal
use Illuminate\Support\Str;

class AuthHelper{
    // Auth id
    public static function authID(){
        // Return user id
        return auth()->user()->id;
    }

    // Role level
    public static function roleLevel($level, $type = null){
        // Define role level
        $roles = [
            '1' => ['Root'],
            '2' => ['Root', 'Admin'],
            '3' => ['Root', 'Admin', 'Moderator'],
        ];
        
        // Return null if level is invalid
        if(!isset($roles[$level])){
            return null;
        }

        // Implode role level
        $roleString = implode('|', $roles[$level]);

        // Return role name
        if($type === 'name'){
            return $roleString;
        }

        // Return role check status
        return auth()->user()->hasRole([$roleString]);
    }

    // Role user
    public static function roleUser($roles){
        // Get data type and its data
        $role = GeneralHelper::getType($roles);

        // Get user data
        $users = User::select('id')->whereHas('roles', function($query) use($role){
            $query->whereIn('name', $role);
        })->get();

        // Return data
        return collect($users)->pluck('id')->toArray();
    }
}
