<?php

namespace App\Repositories;

// Helper
use App\Helpers\AuthHelper;
use App\Helpers\GeneralHelper;

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

        // Set role data
        $this->roleToAssign = collect(Role::select('name')->whereIn('name', $roles)->get())->pluck('name');
        
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
}
