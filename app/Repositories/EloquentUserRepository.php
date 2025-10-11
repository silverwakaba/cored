<?php

namespace App\Repositories;

// Helper
use App\Helpers\GeneralHelper;
use App\Helpers\RBACHelper;

// Model
use App\Models\User;
use App\Models\UserRequest;
use Spatie\Permission\Models\Role;

// Interface
use App\Contracts\UserRepositoryInterface;

// Internal
use Illuminate\Support\Facades\DB;

// External
use Carbon\Carbon;

class EloquentUserRepository extends BaseRepository implements UserRepositoryInterface{
    // Property
    protected $pendingUser;
    protected $roleToAssign;
    protected $rolePublic;

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
        // eg. User with role "Moderator" can't assign role higher than "Moderator" - TLDR; Moderator can't add their own version of Root, or it seems.
        $this->roleToAssign = array_diff(collect(Role::select('name')->whereIn('name', $roles)->get())->pluck('name')->toArray(), RBACHelper::roleLevel(auth()->user()->roles, 'excluded'));
        
        // Chainable
        return $this;
    }

    // Role for public
    public function rolePublic(){
        // Static public role
        $this->rolePublic = collect(Role::select('name')->whereIn('name', ['User'])->get())->pluck('name')->toArray();

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

            // Assign role for public
            if(isset($this->rolePublic)){
                $datas->syncRoles($this->rolePublic);
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

    // Verify account
    public function verifyAccount($id){
        // Implementing db transaction
        return DB::transaction(function() use($id){
            // Datas
            $datas = UserRequest::with([
                'belongsToUser',
            ])->select(['id', 'users_id', 'token'])->where([
                ['base_requests_id', '=', 1],
                'token' => $id,
            ])->first();

            // Request data
            $request = $datas;

            // Invalidate token
            $request->update([
                'token' => null,
            ]);

            // User data
            $user = $datas->belongsToUser;

            // Update verification date
            $user->update([
                'email_verified_at' => Carbon::now()->toDateTimeString(),
            ]);

            // Return response
            return $datas;
        });
    }
}
