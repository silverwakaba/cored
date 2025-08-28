<?php

namespace App\Http\Controllers\API\Core\Access;
use App\Http\Controllers\Controller;

// Repository interface
use App\Contracts\RoleRepositoryInterface;

// Helper
use App\Helpers\ErrorHelper;

// Model
use App\Models\User;
use Spatie\Permission\Models\Role;

// Request
use App\Http\Requests\RoleCreateRequest;
use App\Http\Requests\RoleSyncToPermissionRequest;
use App\Http\Requests\RoleSyncToUserRequest;

// Internal
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

// External
use Carbon\Carbon;

class RoleController extends Controller{
    // Property
    private $repositoryInterface;

    // Constructor
    public function __construct(RoleRepositoryInterface $repositoryInterface){
        $this->repositoryInterface = $repositoryInterface;
    }

    // List
    public function list(Request $request){
        try{
            // Get data
            $datas = $this->repositoryInterface;

            // Load relation
            if(isset($request->relation)){
                $datas->withRelation($request->relation);
            }

            // Response
            if(isset($request->type) && ($request->type != 'datatable')){
                // Return response as plain query
                $newDatas = $datas->all();
            }
            else{
                // Return response as datatable
                $newDatas = $datas->useDatatable()->all();
            }

            // Return response
            return $newDatas;
        }
        catch(\Throwable $th){
            return ErrorHelper::apiErrorResult();
        }
    }

    // Create
    public function create(Request $request){
        try{
            // Validate input
            $validator = Validator::make($request->all(), (new RoleCreateRequest())->rules());

            // Check validation and stop if failed
            if($validator->fails()){
                return response()->json([
                    'success'   => false,
                    'errors'    => $validator->errors(),
                ], 422);
            }

            // Create role
            $datas = $this->repositoryInterface->create([
                'name' => $request->name,
            ]);

            // Return response
            return response()->json([
                'success'   => true,
                'data'      => $datas,
                'message'   => "Role created successfully.",
            ], 201);
        }
        catch(\Throwable $th){
            return ErrorHelper::apiErrorResult();
        }
    }

    // Read
    public function read(Request $request){
        try{
            // Get role data
            $datas = $this->repositoryInterface->withRelation([
                'permissions'
            ])->find($request->id);

            // Return 404
            if(!$datas){
                return ErrorHelper::apiError404Result();
            }

            // Return response
            return response()->json([
                'success'   => true,
                'data'      => $datas,
            ], 200);
        }
        catch(\Throwable $th){
            return ErrorHelper::apiErrorResult();
        }
    }

    // Sync role to Permission
    public function syncToPermission(Request $request){
        try{
            // Validate input
            $validator = Validator::make($request->all(), (new RoleSyncToPermissionRequest())->rules());

            // Check validation and stop if failed
            if($validator->fails()){
                return response()->json([
                    'success'   => false,
                    'errors'    => $validator->errors(),
                ], 422);
            }

            // Sync permission to role (id from role)
            $datas = $this->repositoryInterface->permission($request->permission)->syncToPermission($request->id);

            // Return response
            return response()->json([
                'success'   => true,
                'data'      => $datas,
                'message'   => "Role successfully synchronized with permission.",
            ], 200);
        }
        catch(\Throwable $th){
            return ErrorHelper::apiErrorResult();
        }
    }

    // Sync role to user
    public function syncToUser(Request $request){
        try{
            // Validate input
            $validator = Validator::make($request->all(), (new RoleSyncToUserRequest())->rules());

            // Check validation and stop if failed
            if($validator->fails()){
                return response()->json([
                    'success'   => false,
                    'errors'    => $validator->errors(),
                ], 422);
            }

            // Sync role to user (id from user)
            $datas = $this->repositoryInterface->withRelation('roles')->role($request->role)->syncToUser($request->id);

            // Return response
            return response()->json([
                'success'   => true,
                'data'      => $datas,
                'message'   => "Role successfully synchronized to user.",
            ], 200);
        }
        catch(\Throwable $th){
            return ErrorHelper::apiErrorResult();
        }
    }
}
