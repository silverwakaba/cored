<?php

namespace App\Http\Controllers\API\Core\Access;
use App\Http\Controllers\Controller;

use App\Models\Menu;

use Illuminate\Http\Request;

class MenuController extends Controller{
    public function index(Request $request){
        // Get auth info
        $user = auth()->guard('api')->user();

        // Load menu base on auth info
        $menus = Menu::whereNull('parent_id')
            ->with(['children' => function($query) use($user){
                if($user){
                    $query->whereHas('roles', function ($query) use ($user) {
                        $query->whereIn('name', $user->roles->pluck('name'))
                            ->orWhereNull('menu_id');
                    })->orWhereDoesntHave('roles');
                }
                else{
                    $query->whereDoesntHave('roles')->orWhereHas('roles', function($query){
                        $query->whereNull('menu_id');
                    });
                }
                $query->with('children');
            }])
            ->where(function($query) use($user){
                if($user){
                    $query->whereHas('roles', function($query) use($user){
                        $query->whereIn('name', $user->roles->pluck('name'));
                    })->orWhereDoesntHave('roles');
                }
                else{
                    $query->whereDoesntHave('roles')->orWhereHas('roles', function($query){
                        $query->whereNull('menu_id');
                    });
                }
            })
        ->orderBy('order')->get();

        return response()->json($menus);
    }
}
