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
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

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

    // Search account
    public function search($data){
        // Start query
        $datas = User::query();

        // If $data variable is provided
        if($data){
            // Filter the id
            if(isset($data['id'])){
                $datas->where('id', '=', $data['id']);
            }

            // Filter the email
            if(isset($data['email'])){
                $datas->where('email', '=', $data['email']);
            }

            // Filter null $data
            if(collect($data)->every(fn($value) => is_null($value))){
                // If $data variable is provided but null
                $datas->where('id', '=', 0);
            }

        } else {
            // If no $data variable is provided
            $datas->where('id', '=', 0);
        }
        
        // End query
        $datas = $datas->first();

        // Return response
        return $datas;
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
                ['token', '=', $id],
            ])->whereNotNull('token')->first();

            // Run if there's result
            if($datas){
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
            }

            // Return response
            return $datas;
        });
    }

    // Verify check eligibility
    public function verifyEligibility($id){
        // Search user
        $user = User::select(['*'])->where([
            ['id', '=', $id],
            ['is_active', '=', true],
        ])->whereNull('email_verified_at')->first();

        // Determine wether user is exist or not
        if($user){
            // Search request
            $request = $user->hasOneUserRequest->where([
                ['base_requests_id', '=', 1],
                ['users_id', '=', $id],
                ['updated_at', '<=', Carbon::now()->subHour()],
            ])->latest()->first();

            // Return response
            if(isset($user) && isset($request)){
                // Update the timestamp
                $user->hasOneUserRequest()->touch();

                // User is eligible for verify
                return true;
            } else {
                // User is not eligible for verify
                return false;
            }
        } else {
            return false;
        }
    }
}
