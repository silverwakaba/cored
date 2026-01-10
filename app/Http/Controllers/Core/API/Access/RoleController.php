<?php

namespace App\Http\Controllers\Core\API\Access;
use App\Http\Controllers\Controller;

// Repository interface
use App\Contracts\Core\RoleRepositoryInterface;

// Event
use App\Events\Core\GeneralEventHandler;

// Helper
use App\Helpers\Core\GeneralHelper;

// Request
use App\Http\Requests\Core\RoleCreateRequest;
use App\Http\Requests\Core\RoleCreateWithPermissionRequest;
use App\Http\Requests\Core\RoleSyncToPermissionRequest;
use App\Http\Requests\Core\RoleSyncToUserRequest;

// Internal
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller{
    // Property
    private $repositoryInterface;

    // Constructor
    public function __construct(RoleRepositoryInterface $repositoryInterface){
        $this->repositoryInterface = $repositoryInterface;
    }

    // List
    public function list(Request $request){
        return GeneralHelper::safe(function() use($request){
            // Get data
            $datas = $this->repositoryInterface;

            // Sort data
            $datas->sort([
                'name' => 'ASC',
            ]);

            // Load column selection
            if(isset($request->select)){
                $datas->onlySelect($request->select);
            }

            // Load relation
            if(isset($request->relation)){
                $datas->withRelation($request->relation);
            }

            // Apply filters if provided
            $filters = $request->only(array_filter(array_keys($request->all()), function($key){
                return strpos($key, 'filter') === 0;
            }));

            // Run filter as sub-query
            if(!empty($filters)){
                $datas->query->where(function($query) use($filters){
                    foreach($filters as $filterKey => $filterValue){
                        // Permission filters
                        if(in_array($filterKey, ['filter-permission'])){
                            $query->whereHas('permissions', function($q) use($filterValue){
                                $q->whereIn('name', $filterValue);
                            });
                        }
                    }
                });
            }

            // Return response
            return ($request->type === 'datatable') ? $datas->useDatatable()->all() : $datas->all();
        }, ['status' => 409, 'message' => false]);
    }

    // Create
    public function create(Request $request){
        return GeneralHelper::safe(function() use($request){
            // Validate input
            $validated = GeneralHelper::validate($request->all(), (new RoleCreateRequest())->rules());

            // Stop if validation failed
            if(!is_array($validated)){
                return $validated;
            }

            // Create role
            $datas = $this->repositoryInterface->create([
                'name' => $request->name,
            ]);

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 201,
                'data'      => $datas,
                'message'   => 'Role created successfully.',
            ]);
        }, ['status' => 409, 'message' => false]);
    }

    // Create with Permission (atomic transaction)
    public function createWithPermission(Request $request){
        return GeneralHelper::safe(function() use($request){
            // Validate input
            $validated = GeneralHelper::validate($request->all(), (new RoleCreateWithPermissionRequest())->rules());

            // Stop if validation failed
            if(!is_array($validated)){
                return $validated;
            }

            // Execute create and sync in a single database transaction
            // Note: Using create() method with broadcaster trailing for 'create' event
            // Laravel handles nested transactions safely using savepoints
            $datas = DB::transaction(function() use($request){
                // Create role with broadcaster trailing for 'create' event
                $role = $this->repositoryInterface->create([
                    'name' => $request->name,
                ]);

                // Sync role to permission if permission is provided
                if($request->permission){
                    // Prepare permissions
                    $permissions = collect(\App\Models\Core\Permission::select('name')
                        ->whereIn('name', \App\Helpers\Core\GeneralHelper::getType($request->permission))
                    ->get())->pluck('name');

                    // Check role level
                    $rbacCheck = \App\Helpers\Core\RBACHelper::roleLevelCompare([$role], auth()->user()->roles);
                    
                    // Sync role to permission
                    if($rbacCheck == true){
                        $role->syncPermissions($permissions);
                    }
                }

                return $role;
            });

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 201,
                'data'      => $datas,
                'message'   => 'Role created and synchronized with permission successfully.',
            ]);
        }, ['status' => 409, 'message' => false]);
    }

    // Read
    public function read($id, Request $request){
        return GeneralHelper::safe(function() use($id, $request){
            // Get role data
            $datas = $this->repositoryInterface;

            // Load column selection
            if(isset($request->select)){
                $datas->onlySelect($request->select);
            }

            // Load relation
            if(isset($request->relation)){
                $datas->withRelation($request->relation);
            }
            
            // Continue variable
            $datas = $datas->find($id);

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 200,
                'data'      => $datas,
            ]);
        }, ['status' => 409, 'message' => false]);
    }

    // Sync role to Permission
    public function syncToPermission($id, Request $request){
        return GeneralHelper::safe(function() use($id, $request){
            // Validate input
            $validated = GeneralHelper::validate($request->all(), (new RoleSyncToPermissionRequest())->rules());

            // Check validation and stop if failed
            if(!is_array($validated)){
                return $validated;
            }

            // Sync permission to role (id from role)
            $datas = $this->repositoryInterface->permission($request->permission)->syncToPermission($id);

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 200,
                'data'      => $datas,
                'message'   => 'Role successfully synchronized with permission.',
            ]);
        }, ['status' => 409, 'message' => false]);
    }

    // Sync role to user
    public function syncToUser($id, Request $request){
        return GeneralHelper::safe(function() use($id, $request){
            // Validate input
            $validated = GeneralHelper::validate($request->all(), (new RoleSyncToUserRequest())->rules());

            // Check validation and stop if failed
            if(!is_array($validated)){
                return $validated;
            }

            // Sync role to user (id from user)
            $datas = $this->repositoryInterface->withRelation('roles')->role($request->role)->syncToUser($id);

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 200,
                'data'      => $datas,
                'message'   => 'Role successfully synchronized to user.',
            ]);
        }, ['status' => 409, 'message' => false]);
    }
}
