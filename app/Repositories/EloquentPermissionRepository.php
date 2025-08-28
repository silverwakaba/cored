<?php

namespace App\Repositories;

// Helper
use App\Helpers\GeneralHelper;

// Model
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

// Interface
use App\Contracts\PermissionRepositoryInterface;

// Internal
use Illuminate\Support\Facades\DB;

class EloquentPermissionRepository extends BaseRepository implements PermissionRepositoryInterface{
    // Property
    protected $permissionToSync;
    protected $roleToSync;

    // Constructor
    public function __construct(Permission $model){
        $this->model = parent::__construct($model);
        $this->query = $model->query();
    }

    // // Permission
    // public function permission(mixed $permission){
    //     // Get data type and its data
    //     $permissions = GeneralHelper::getType($permission);

    //     // Set permission data
    //     $this->permissionToSync = collect(Permission::select('name')->whereIn('name', $permissions)->get())->pluck('name');
        
    //     // Chainable
    //     return $this;
    // }

    // // Role
    // public function role(mixed $role){
    //     // Get data type and its data
    //     $roles = GeneralHelper::getType($role);

    //     // Set role data
    //     $this->roleToSync = collect(Role::select('name')->whereIn('name', $roles)->get())->pluck('name');
        
    //     // Chainable
    //     return $this;
    // }

    // // Sync role with permission
    // public function syncToPermission($id){
    //     // Implementing db transaction
    //     return DB::transaction(function() use($id){
    //         // Find role
    //         $datas = parent::find($id);

    //         // Sync role to permission
    //         $datas->syncPermissions($this->permissionToSync);

    //         // Return response
    //         return $datas;
    //     });
    // }

    // // Sync role to user
    // public function syncToUser($id){
    //     // Implementing db transaction
    //     return DB::transaction(function() use($id){
    //         // Find user
    //         $datas = User::find($id);

    //         // Sync role to user
    //         $datas->syncRoles($this->roleToSync);

    //         // Return response
    //         return $datas;
    //     });
    // }
}
