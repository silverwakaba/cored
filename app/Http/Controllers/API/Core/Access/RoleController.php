<?php

namespace App\Http\Controllers\API\Core\Access;
use App\Http\Controllers\Controller;

// Repository interface
use App\Contracts\RoleRepositoryInterface;

// Helper
use App\Helpers\GeneralHelper;

// Model
use App\Models\User;
use Spatie\Permission\Models\Role;

// Request
use App\Http\Requests\RoleCreateRequest;
use App\Http\Requests\RoleSyncToPermissionRequest;
use App\Http\Requests\RoleSyncToUserRequest;

// Internal
use Illuminate\Http\Request;

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

            // Return response
            return ($request->type === 'datatable') ? $datas->useDatatable()->all() : $datas->all();
        });
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
                'name' => $request['name'],
            ]);

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 201,
                'data'      => $datas,
                'message'   => 'Role created successfully.',
            ]);
        });
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
        });
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
            $datas = $this->repositoryInterface->permission($request['permission'])->syncToPermission($id);

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 200,
                'data'      => $datas,
                'message'   => 'Role successfully synchronized with permission.',
            ]);
        });
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
            $datas = $this->repositoryInterface->withRelation('roles')->role($request['role'])->syncToUser($id);

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 200,
                'data'      => $datas,
                'message'   => 'Role successfully synchronized to user.',
            ]);
        });
    }
}
