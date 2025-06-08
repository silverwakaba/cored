<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class Navigation extends Model{
    protected $table = 'navigations';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'parent_id',
        'is_active',
        'is_header',
        'order',
        'title',
        'route',
        'icon',
        'roles',
    ];

    public function children(){
        return $this->hasMany(NavigationItem::class, 'parent_id')->orderBy('order')->where('is_active', true);
    }

    public function parent(){
        return $this->belongsTo(NavigationItem::class, 'parent_id');
    }

    public function isActive(){
        return request()->routeIs($this->route);
    }

    public function getResolvedUrl(){
        return route($this->route);
    }

    public function isAuthorized(){
        if(empty($this->roles)){
            return true;
        }

        try{
            $requiredRoles = explode(',', $this->roles);
        
            return Auth::user()->hasAnyRole($requiredRoles);
        }
        catch(\Throwable $th){
            return false;
        }
    }

    public function authorizedChildren(){
        return $this->children->filter(function ($child) {
            return $child->isAuthorized();
        });
    }

    public static function roots(){
        return self::whereNull('parent_id')->where('is_active', true)->orderBy('order')->get()->filter(function($item){
            return $item->isAuthorized();
        });
    }
}
