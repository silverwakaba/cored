<?php

// app/Models/Menu.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class Menu extends Model{
    protected $table = 'menus';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'parent_id',
        'name',
        'icon',
        'route',
        'type',
        'order',
        'is_authenticate',
    ];

    public function parent(){
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children(){
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order')->with('children');
    }

    public function roles(){
        return $this->belongsToMany(Role::class, 'menu_role');
    }

    public function hasAccess($user = null){
        // If no user is provided (guest) and no roles are assigned, the menu is visible
        if(!$user && $this->roles->isEmpty()){
            return true;
        }

        // If there is a parent, check its access first
        if($this->parent && !$this->parent->hasAccess($user)){
            return false;
        }

        // If no roles assigned, menu is visible to all (including guests)
        if($this->roles->isEmpty()){
            return true;
        }

        // For authenticated users, check if they have any of the assigned roles
        return $user && $user->hasAnyRole($this->roles->pluck('name'));
    }

    public function getVisibleChildren($user = null){
        return $this->children->filter(function($child) use($user){
            return $child->hasAccess($user);
        });
    }
}