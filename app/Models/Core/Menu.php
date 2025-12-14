<?php

// app/Models/Menu.php
namespace App\Models\Core;

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

    protected $casts = [
        'is_authenticate'   => 'boolean',
        'order'             => 'integer',
    ];

    // Belong to parent
    public function parent(){
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    // Has many children
    public function children(){
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order')->with('children');
    }

    // Belong to role
    public function roles(){
        return $this->belongsToMany(Role::class, 'menu_roles');
    }

    // User has access
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

    // Visible children for user
    public function getVisibleChildren($user = null){
        return $this->children->filter(function($child) use($user){
            return $child->hasAccess($user);
        });
    }

    //  Menu scope: Header
    public function scopeHeaders($query){
        return $query->where('type', 'h')->whereNull('parent_id');
    }

    //  Menu scope: Parents
    public function scopeParents($query){
        return $query->where('type', 'p');
    }

    //  Menu scope: Children
    public function scopeChildren($query){
        return $query->where('type', 'c');
    }

    //  Menu scope: By parent
    public function scopeByParent($query, $parentId){
        return $query->where('parent_id', $parentId);
    }

    //  Menu scope: By type
    public function scopeByType($query, $type){
        return $query->where('type', $type);
    }
}
