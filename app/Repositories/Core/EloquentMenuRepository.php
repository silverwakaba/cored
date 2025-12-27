<?php

namespace App\Repositories\Core;

// Helper
use App\Helpers\Core\GeneralHelper;

// Model
use App\Models\Core\Menu;

// Interface
use App\Contracts\Core\MenuRepositoryInterface;

// Internal
use Illuminate\Support\Facades\DB;

/*
 * Notes
 * 
 * This repo is a stub: It is ready to use but there's still a lot things to optimize.
*/

class EloquentMenuRepository extends BaseRepository implements MenuRepositoryInterface{
    // Constructor
    public function __construct(Menu $model){
        $this->model = parent::__construct($model);
        $this->query = $model->query();
    }

    // Index
    public function index(){
        // Get auth info
        $user = auth()->guard('api')->user();
        
        // Check if user is authenticated
        $isAuthenticated = !is_null($user);

        // Load menu base on auth info with hierarchical authentication filtering
        $menus = Menu::whereNull('parent_id')
            ->with(['children' => function($query) use($user, $isAuthenticated){
                // Apply both role-based and authentication filtering for children
                $this->applyQueryFilters($query, $user, $isAuthenticated);
                
                // Load grandchildren with the same filtering and relationships
                $query->with(['children' => function($subQuery) use($user, $isAuthenticated){
                    $this->applyQueryFilters($subQuery, $user, $isAuthenticated);
                }, 'includedUsers:id', 'excludedUsers:id']);
            }, 'includedUsers:id', 'excludedUsers:id'])
            ->where(function($query) use($user, $isAuthenticated){
                $this->applyQueryFilters($query, $user, $isAuthenticated);
            })
        ->orderBy('order')->get();

        // Apply final hierarchical authentication filtering to handle any edge cases
        $filteredMenus = $this->filterByHierarchicalAuthentication($menus, $isAuthenticated);

        // Response
        return response()->json($filteredMenus);
    }

    // Apply both role-based and authentication filtering to query
    private function applyQueryFilters($query, $user, $isAuthenticated){
        $query->where(function($q) use($user, $isAuthenticated){
            // Apply guest-only filtering
            // If authenticated, hide menus that are guest-only
            if($isAuthenticated){
                $q->where(function($guestQuery){
                    $guestQuery->where('is_guest_only', 0)->orWhereNull('is_guest_only');
                });
            }
            // If not authenticated, no guest-only restriction needed (guests can see all menus, is_authenticate will filter further)

            // Apply authentication filtering
            // If authenticated, no authentication restriction (regardless, all is_authenticate values allowed)
            if(!$isAuthenticated){
                $q->where(function($authQuery){
                    $authQuery->where('is_authenticate', 0)->orWhereNull('is_authenticate');
                });
            }

            // Apply role-based filtering with include/exclude logic
            if($user){
                // Exclude menus where user is in excludes
                $q->whereDoesntHave('excludedUsers', function($excludeQuery) use($user){
                    $excludeQuery->where('user_id', $user->id);
                });

                // For authenticated users: show menus that have matching roles OR no roles OR user is included
                $q->where(function($roleQuery) use($user){
                    $roleQuery->whereHas('roles', function($rQuery) use($user){
                        $rQuery->whereIn('name', $user->roles->pluck('name'));
                    })
                    ->orWhereDoesntHave('roles')
                    ->orWhereHas('includedUsers', function($includeQuery) use($user){
                        $includeQuery->where('user_id', $user->id);
                    });
                });
            } else {
                // For unauthenticated users: show menus that have no roles assigned
                $q->whereDoesntHave('roles');
            }
        });
    }

    // Filter menus hierarchically based on is_authenticate and is_guest_only (final cleanup)
    private function filterByHierarchicalAuthentication($menus, $isAuthenticated){
        $user = auth()->guard('api')->user();
        
        return $menus->filter(function($menu) use($isAuthenticated, $user){
            // Check if this menu should be visible based on authentication
            if($menu->is_authenticate && !$isAuthenticated){
                return false;
            }

            // Check if this menu should be visible based on guest-only
            if($menu->is_guest_only && $isAuthenticated){
                return false;
            }

            // Check exclude logic: if user is in excludes, hide menu even if they have role
            if($user && $menu->excludedUsers->pluck('id')->contains($user->id)){
                return false;
            }

            // Recursively filter children
            if($menu->children->isNotEmpty()){
                $menu->children = $this->filterByHierarchicalAuthentication($menu->children, $isAuthenticated);
                
                // If after filtering, this menu has no children and it's a header/parent type, then hide it
                if($menu->children->isEmpty() && in_array($menu->type, ['h', 'p'])){
                    return false;
                }
            }

            return true;
        })->values();
    }

    // Create menu
    // The position can be either before or after
    public function createMenu($data, $position = 'after', $referenceId = null){
        return DB::transaction(function() use($data, $position, $referenceId){
            $referenceMenu = $referenceId ? Menu::findOrFail($referenceId) : null;
            
            // Determine target order
            $targetOrder = $this->calculateTargetOrder($data, $position, $referenceMenu);
            
            // Shift existing menus to make space
            $this->shiftMenusForInsertion($data, $targetOrder);
            
            // Create the new menu
            return Menu::create(array_merge($data, ['order' => $targetOrder]));
        });
    }

    // Update menu position
    // The position can be either before or after
    public function updateMenuPosition($menuId, $position, $referenceId){
        return DB::transaction(function() use($menuId, $position, $referenceId){
            $menu = Menu::findOrFail($menuId);
            $referenceMenu = Menu::findOrFail($referenceId);
            
            // Ensure both menus have same parent and type context
            if($menu->parent_id !== $referenceMenu->parent_id || $menu->type !== $referenceMenu->type){
                throw new \InvalidArgumentException('Cannot move menu to different parent or type context');
            }
            
            $currentOrder = $menu->order;
            $targetOrder = $this->calculateTargetOrder($menu->toArray(), $position, $referenceMenu);
            
            if($currentOrder === $targetOrder){
                return $menu;
            }
            
            // Shift menus and update
            $this->shiftMenusForMovement($menu, $currentOrder, $targetOrder);
            $menu->update(['order' => $targetOrder]);
            
            return $menu->fresh();
        });
    }

    // Delete menu and reorder siblings
    public function deleteMenu($menuId){
        DB::transaction(function() use($menuId){
            $menu = Menu::findOrFail($menuId);
            $parentId = $menu->parent_id;
            $type = $menu->type;
            $deletedOrder = $menu->order;
            
            // Delete the menu (cascade will handle children)
            $menu->delete();
            
            // Reorder siblings
            $this->reorderSiblings($parentId, $type, $deletedOrder);
        });
    }

    // Calculate target order based on position and reference menu
    private function calculateTargetOrder($data, $position, $referenceMenu){
        $parentId = $data['parent_id'] ?? null;
        $type = $data['type'];
        
        if(!$referenceMenu){
            // If no reference, add to the end
            return $this->getMaxOrder($parentId, $type) + 1;
        }
        
        $referenceOrder = $referenceMenu->order;
        
        return match($position){
            'before'    => $referenceOrder,
            'after'     => $referenceOrder + 1,
            default     => throw new \InvalidArgumentException('Position must be "before" or "after"')
        };
    }

    // Shift menus to make space for new insertion
    private function shiftMenusForInsertion($data, $targetOrder){
        $parentId = $data['parent_id'] ?? null;
        $type = $data['type'];
        
        Menu::byParent($parentId)->byType($type)->where('order', '>=', $targetOrder)->increment('order');
    }

    // Shift menus for movement within same group
    private function shiftMenusForMovement($menu, $currentOrder, $targetOrder){
        $parentId = $menu->parent_id;
        $type = $menu->type;
        
        if($currentOrder < $targetOrder){
            // Moving down - decrement orders between current and target
            Menu::byParent($parentId)->byType($type)
                ->where('order', '>', $currentOrder)
                ->where('order', '<=', $targetOrder)
            ->decrement('order');
        } else {
            // Moving up - increment orders between target and current
            Menu::byParent($parentId)->byType($type)
                ->where('order', '>=', $targetOrder)
                ->where('order', '<', $currentOrder)
            ->increment('order');
        }
    }

    // Reorder siblings after deletion
    private function reorderSiblings($parentId, $type, $deletedOrder){
        Menu::byParent($parentId)
            ->byType($type)
            ->where('order', '>', $deletedOrder)
            ->decrement('order');
    }

    // Get maximum order for given parent and type
    private function getMaxOrder($parentId, $type){
        return (int) Menu::byParent($parentId)->byType($type)->max('order') ?? 0;
    }

    // Get hierarchical menu structure
    public function getHierarchicalMenu(){
        return Menu::with([
            'children.children'
        ])->headers()->orderBy('order')->get()->toArray();
    }

    // Get menus by parent and type for reordering
    public function getMenusByContext($parentId, $type){
        return Menu::byParent($parentId)->byType($type)->orderBy('order')->get();
    }
}
