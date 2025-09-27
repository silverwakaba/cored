<?php

namespace App\Http\Controllers\API\Core\Access;
use App\Http\Controllers\Controller;

// Repository interface
use App\Contracts\PermissionRepositoryInterface;

// Helper
use App\Helpers\GeneralHelper;

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
            return GeneralHelper::jsonResponse([
                'status'    => 409,
                'message'   => null,
            ]);
        }
    }

    // Create
    public function create(Request $request){
        try{
            // Validate input
            $validator = Validator::make($request->all(), (new PermissionCreateRequest())->rules());

            // Check validation and stop if failed
            if($validator->fails()){
                return GeneralHelper::jsonResponse([
                    'status'    => 422,
                    'errors'    => $validator->errors(),
                ]);
            }

            // Create permission
            $datas = $this->repositoryInterface->create([
                'name' => $request->name,
            ]);

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 201,
                'data'      => $datas,
                'message'   => 'Permission created successfully.',
            ]);
        }
        catch(\Throwable $th){
            return GeneralHelper::jsonResponse([
                'status'    => 409,
                'message'   => null,
            ]);
        }
    }

    // Read
    public function read(Request $request){
        try{
            // Get permission data
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
            $datas = $datas->find($request->id);

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 200,
                'data'      => $datas,
            ]);
        }
        catch(\Throwable $th){
            return GeneralHelper::jsonResponse([
                'status'    => 409,
                'message'   => null,
            ]);
        }
    }

    // Update
    public function update(Request $request){
        try{
            // Validate input
            $validator = Validator::make($request->all(), (new PermissionCreateRequest())->rules());

            // Check validation and stop if failed
            if($validator->fails()){
                return GeneralHelper::jsonResponse([
                    'status'    => 422,
                    'errors'    => $validator->errors(),
                ]);
            }

            // Update permission data
            $datas = $this->repositoryInterface->update($request->id, [
                'name' => $request->name,
            ]);

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 201,
                'data'      => $datas,
                'message'   => 'Permission updated successfully.',
            ]);
        }
        catch(\Throwable $th){
            return GeneralHelper::jsonResponse([
                'status'    => 409,
                'message'   => null,
            ]);
        }
    }

    // Delete
    public function delete(Request $request){
        try{
            // Delete permission data
            $datas = $this->repositoryInterface->delete($request->id);

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 200,
                'message'   => 'Permission deleted successfully.',
            ]);
        }
        catch(\Throwable $th){
            return GeneralHelper::jsonResponse([
                'status'    => 409,
                'message'   => null,
            ]);
        }
    }
}
