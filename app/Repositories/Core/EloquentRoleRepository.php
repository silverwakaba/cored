<?php

namespace App\Repositories\Core;

// Helper
use App\Helpers\Core\GeneralHelper;
use App\Helpers\Core\RBACHelper;

// Model
use App\Models\Core\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

// Interface
use App\Contracts\Core\RoleRepositoryInterface;

// Internal
use Illuminate\Support\Facades\DB;

class EloquentRoleRepository extends BaseRepository implements RoleRepositoryInterface{
    // Property
    protected $permissionToSync;
    protected $roleToSync;

    // Constructor
    public function __construct(Role $model){
        $this->model = parent::__construct($model);
        $this->query = $model->query();
    }

    // Permission
    public function permission(mixed $permission){
        // Get data type and its data
        $permissions = GeneralHelper::getType($permission);

        // Set permission data
        $this->permissionToSync = collect(Permission::select('name')->whereIn('name', $permissions)->get())->pluck('name');
        
        // Chainable
        return $this;
    }

    // Role
    public function role(mixed $role){
        // Get data type and its data
        $roles = GeneralHelper::getType($role);

        // This one is crucial so this is implemented inside the repo
        // Can only assign role up to user highest role to prevent privilege escalation
        // eg. User with role "Moderator" can't assign role higher than "Moderator" - TLDR; Moderator can't add their own version of Root
        $this->roleToSync = array_diff(collect(Role::select('name')->whereIn('name', $roles)->get())->pluck('name')->toArray(), RBACHelper::roleLevel(auth()->user()->roles, 'excluded'));
        
        // Chainable
        return $this;
    }

    // Sync role with permission | To be RBAC-ed
    public function syncToPermission($id){
        return DB::transaction(function() use($id){
            // Find role
            $datas = parent::find($id);

            // Check role level
            $rbacCheck = RBACHelper::roleLevelCompare([$datas], auth()->user()->roles);

            // Sync role to permission
            if($rbacCheck == true){
                $datas->syncPermissions($this->permissionToSync);
            }

            // Call broadcaster if set
            if($this->broadcastClass){
                $this->broadcasterExecute($datas);
            }

            // Return response
            return $datas;
        });
    }

    // Sync role to user
    public function syncToUser($id){
        // Implementing db transaction
        return DB::transaction(function() use($id){
            // Find user
            $datas = User::find($id);

            // Check role level
            $rbacCheck = RBACHelper::roleLevelCompare($datas->roles, auth()->user()->roles);

            // Assign role
            if(isset($this->roleToSync) && ($rbacCheck == true)){
                $datas->syncRoles($this->roleToSync);
            }

            // Call broadcaster if set
            if($this->broadcastClass){
                $this->broadcasterExecute($datas);
            }

            // Return response
            return $datas;
        });
    }
}
