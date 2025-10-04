<?php

namespace App\Http\Controllers\API\Core\Access;
use App\Http\Controllers\Controller;

// Model
use App\Models\Menu;

// Internal
use Illuminate\Http\Request;

class MenuController extends Controller{
    public function index(Request $request){
        // Get auth info
        $user = auth()->guard('api')->user();
        
        // Check if user is authenticated
        $isAuthenticated = !is_null($user);

        // Load menu base on auth info with hierarchical authentication filtering
        $menus = Menu::whereNull('parent_id')
            ->with(['children' => function($query) use($user, $isAuthenticated){
                // Apply both role-based and authentication filtering for children
                $this->applyQueryFilters($query, $user, $isAuthenticated);
                
                // Load grandchildren with the same filtering
                $query->with(['children' => function($subQuery) use($user, $isAuthenticated){
                    $this->applyQueryFilters($subQuery, $user, $isAuthenticated);
                }]);
            }])
            ->where(function($query) use($user, $isAuthenticated){
                $this->applyQueryFilters($query, $user, $isAuthenticated);
            })
        ->orderBy('order')->get();

        // Apply final hierarchical authentication filtering to handle any edge cases
        $filteredMenus = $this->filterByHierarchicalAuthentication($menus, $isAuthenticated);

        // Response
        return response()->json($filteredMenus);
    }

    /**
     * Apply both role-based and authentication filtering to query
     */
    private function applyQueryFilters($query, $user, $isAuthenticated){
        $query->where(function($q) use($user, $isAuthenticated){
            // Apply authentication filtering
            // If authenticated, no authentication restriction (all is_authenticate values allowed)
            if(!$isAuthenticated){
                $q->where(function($authQuery){
                    $authQuery->where('is_authenticate', 0)->orWhereNull('is_authenticate');
                });
            }

            // Apply role-based filtering
            if($user){
                // For authenticated users: show menus that have matching roles OR no roles
                $q->whereHas('roles', function($roleQuery) use($user){
                    $roleQuery->whereIn('name', $user->roles->pluck('name'));
                })->orWhereDoesntHave('roles');
            } else {
                // For unauthenticated users: show menus that have no roles OR roles with null menu_id
                $q->whereDoesntHave('roles')->orWhereHas('roles', function($roleQuery){
                    $roleQuery->whereNull('menu_id');
                });
            }
        });
    }

    /**
     * Filter menus hierarchically based on is_authenticate (final cleanup)
     */
    private function filterByHierarchicalAuthentication($menus, $isAuthenticated){
        return $menus->filter(function($menu) use($isAuthenticated){
            // Check if this menu should be visible based on authentication
            if($menu->is_authenticate && !$isAuthenticated){
                return false;
            }

            // Recursively filter children
            if($menu->children->isNotEmpty()){
                $menu->children = $this->filterByHierarchicalAuthentication($menu->children, $isAuthenticated);
                
                // If after filtering, this menu has no children and it's a header/parent type, hide it
                if($menu->children->isEmpty() && in_array($menu->type, ['h', 'p'])){
                    return false;
                }
            }

            return true;
        })->values();
    }
}
