<?php

namespace App\Repositories\Core;

// Helper
use App\Helpers\Core\GeneralHelper;
use App\Helpers\Core\RBACHelper;

// Model
use App\Models\Core\User;
use App\Models\Core\UserRequest;
use Spatie\Permission\Models\Role;

// Interface
use App\Contracts\Core\UserRepositoryInterface;

// Internal
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

// External
use Carbon\Carbon;

class EloquentUserRepository extends BaseRepository implements UserRepositoryInterface{
    // Property
    protected $pendingUser;
    protected $searchUser;
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
            // Search the token
            $datas = UserRequest::with([
                'user',
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
                $user = $datas->user;

                // Update verification date
                $user->update([
                    'email_verified_at' => Carbon::now()->toDateTimeString(),
                ]);
            }

            // Return response
            return $datas;
        });
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
                // Use whereRaw with always false condition (ULID is string, can't use 0)
                $datas->whereRaw('1 = 0');
            }

        } else {
            // If no $data variable is provided
            // Use whereRaw with always false condition (ULID is string, can't use 0)
            $datas->whereRaw('1 = 0');
        }

        // End query
        $this->searchUser = $datas;

        // Return response
        return $this;
    }

    // Check eligibility for specific request
    public function requestEligibility($request){
        // Complete the search query
        // Only active user is eligible for every eligibility check
        $datas = $this->searchUser->where([
            ['is_active', '=', true],
        ]);

        // Do eligibility check based on $request type
        if($request == 1){
            // $request 1 = Email Verification
            // Column "email_verified_at" must be null
            $datas = $datas->whereNull('email_verified_at')->first();
        } else {
            // Only get the user
            $datas = $datas->first();
        }

        // If the user exist
        if($datas){
            // Include the request to the logic
            $requests = $datas->userRequests()->where([
                ['base_requests_id', '=', $request],
                ['users_id', '=', $datas['id']],
            ])->whereNotNull('token');
            
            // Get the latest request
            $getRequests = $requests->latest()->first();

            // If the request doesn't exist then create new
            if(!$getRequests){
                $newRequests = $requests->create([
                    'base_requests_id'  => $request,
                    'users_id'          => $datas['id'],
                    'token'             => GeneralHelper::randomToken(),
                ]);
            }

            // Default state
            $getRequestsCondition = false;
            $newRequestsCondition = false;

            // Check $getRequests if it exists
            if(isset($getRequests)){
                $getRequestsCondition = (Carbon::parse($getRequests->updated_at) <= Carbon::now()->subHours()) || ($getRequests->created_at == $getRequests->updated_at);
            }

            // Check $newRequests if it exists  
            if(isset($newRequests)){
                $newRequestsCondition = (Carbon::parse($newRequests->updated_at) <= Carbon::now()->subHours()) || ($newRequests->created_at == $newRequests->updated_at);
            }

            // Check the condition
            if($getRequestsCondition || $newRequestsCondition){
                // Update timestamp
                $requests->touch();

                // User is eligible
                return GeneralHelper::jsonResponse([
                    'status'    => 200,
                    'data'      => (isset($getRequests) ? $getRequests : $newRequests),
                    'message'   => 'Account found and eligible for this action. Please check your email for more information.',
                ]);
            }
        }

        // User is not eligible
        return GeneralHelper::jsonResponse([
            'status'    => 401,
            'message'   => 'This account is not eligible for this action. Please try again later.',
        ]);
    }

    // Reset password
    public function resetPassword($data){
        // Implementing db transaction
        return DB::transaction(function() use($data){
            // Search the token
            $datas = UserRequest::with([
                'user',
            ])->select(['id', 'users_id', 'token'])->where([
                ['base_requests_id', '=', 3],
                ['token', '=', $data['token']],
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
                $user = $datas->user;

                // Update password date
                $user->update([
                    'password' => bcrypt($data['new_password']),
                ]);
            }

            // Return response
            return $datas;
        });
    }
}
