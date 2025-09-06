<?php

namespace App\Http\Controllers\API\Core\Access;
use App\Http\Controllers\Controller;

// Repository interface
use App\Contracts\PermissionRepositoryInterface;

// Helper
use App\Helpers\GeneralHelper;
use App\Helpers\RoleHelper;

// Model
use Spatie\Permission\Models\Permission;

// Request
use App\Http\Requests\PermissionCreateRequest;

// Internal
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller{
    // Property
    private $repositoryInterface;

    // Constructor
    public function __construct(PermissionRepositoryInterface $repositoryInterface){
        $this->repositoryInterface = $repositoryInterface;
    }

    // List
    public function list(Request $request){
        try{
            // Get data while sorting
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

            // Response
            if(($request->type == 'datatable')){
                // Return response as datatable
                $datas = $datas->useDatatable()->all();
            } else {
                // Return response as plain query
                $datas = $datas->all();
            }

            // Return response
            return $datas;
        }
        catch(\Throwable $th){
            return ErrorHelper::apiErrorResult();
        }
    }

    // Create
    public function create(Request $request){
        try{
            // Validate input
            $validator = Validator::make($request->all(), (new PermissionCreateRequest())->rules());

            // Check validation and stop if failed
            if($validator->fails()){
                return response()->json([
                    'success'   => false,
                    'errors'    => $validator->errors(),
                ], 422);
            }

            // Create permission
            $datas = $this->repositoryInterface->create([
                'name' => $request->name,
            ]);

            // Return response
            return response()->json([
                'success'   => true,
                'data'      => $datas,
                'message'   => "Permission created successfully.",
            ], 201);
        }
        catch(\Throwable $th){
            return ErrorHelper::apiErrorResult();
        }
    }

    // Read
    public function read(Request $request){
        try{
            // Get permission data
            $datas = $this->repositoryInterface->withRelation([
                'roles'
            ])->find($request->id);

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

    // Update
    public function update(Request $request){
        try{
            // Update permission data
            $datas = $this->repositoryInterface->update($request->id, [
                'name' => $request->name,
            ]);

            // Return response
            return response()->json([
                'success'   => true,
                'data'      => $datas,
            ], 200);
        }
        catch(\Throwable $th){
            return ErrorHelper::apiErrorResult($th);
        }
    }

    // Delete
    public function delete(Request $request){
        try{
            // Delete permission data
            $datas = $this->repositoryInterface->delete($request->id);

            // Return response
            return response()->json([
                'success'   => true,
                'data'      => $datas,
                'message'   => "Permission deleted successfully.",
            ], 201);
        }
        catch(\Throwable $th){
            return ErrorHelper::apiErrorResult();
        }
    }
}
