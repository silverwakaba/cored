<?php

namespace App\Helpers;

use App\Models\Navigation;

use Illuminate\Support\Facades\Auth;

class NavigationHelper{
    // Build menu
    public function buildMenu($parentID = null){
        return Navigation::where('parent_id', $parentID)->where('is_active', true)->orderBy('order', 'ASC')->get()->filter(function($item){
            return $item->isAuthorized();
        })->map(function($item){
            $item->children = $this->buildMenu($item->id);

            $item->hasActiveChild = $item->children->contains(function($child){
                return $child->isActive() || $child->hasActiveChild;
            });
            
            return $item;
        });
    }

    // Get Menu
    public function getMenuWithHeaders(){
        // Build menu
        $items = $this->buildMenu();
        
        // Group items by headers
        $grouped = [];
        $currentHeader = null;
        
        // Load menu as item
        foreach($items as $item){
            if($item->is_header){
                $currentHeader = $item;
                $grouped[] = $item;
            }
            else{
                if($currentHeader){
                    if(!isset($currentHeader->children)){
                        $currentHeader->children = collect();
                    }

                    $currentHeader->children->push($item);
                }
                else {
                    $grouped[] = $item;
                }
            }
        }
        
        return collect($grouped);
    }
}
