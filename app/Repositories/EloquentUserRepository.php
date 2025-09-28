<?php

namespace App\Repositories;

// Helper
use App\Helpers\GeneralHelper;
use App\Helpers\RBACHelper;

// Model
use App\Models\User;
use Spatie\Permission\Models\Role;

// Interface
use App\Contracts\UserRepositoryInterface;

// Internal
use Illuminate\Support\Facades\DB;

class EloquentUserRepository extends BaseRepository implements UserRepositoryInterface{
    // Property
    protected $pendingUser;
    protected $roleToAssign;

    // Constructor
    public function __construct(User $model){
        $this->model = parent::__construct($model);
        $this->query = $model->query();
    }

    // Prepare execute
    public function prepare(array $data){
        // Set user data
        $this->pendingUser = $data;
        
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
        $this->roleToAssign = array_diff(collect(Role::select('name')->whereIn('name', $roles)->get())->pluck('name')->toArray(), RBACHelper::roleLevel(auth()->user()->roles, 'excluded'));
        
        // Chainable
        return $this;
    }

    // Register
    public function register(){
        // Implementing db transaction
        return DB::transaction(function(){
            // Create
            $datas = $this->query->create($this->pendingUser);

            // Assign role
            if(isset($this->roleToAssign)){
                $datas->syncRoles($this->roleToAssign);
            }

            // Return response
            return $datas;
        });
    }

    // Modify (Equal to update but bundled with RBAC)
    public function modify($id, array $data){
        // Check role level
        $rbacCheck = RBACHelper::roleLevelCompare(parent::withRelation(['roles'])->find($id)->roles, auth()->user()->roles);

        // Do something if true
        if($rbacCheck == true){
            // Update the data from the parents function
            return parent::update($id, $data);
        }

        // Otherwise return null response
        return null;
    }

    // Activate (Equal to activation but bundled with RBAC)
    public function activate($id, $data){
        // Check role level
        $rbacCheck = RBACHelper::roleLevelCompare(parent::withRelation(['roles'])->find($id)->roles, auth()->user()->roles);

        // Do something if true
        if($rbacCheck == true){
            // Update the data from the parents function
            return parent::activation($id, $data);
        }

        // Otherwise return null response
        return null;
    }
}
