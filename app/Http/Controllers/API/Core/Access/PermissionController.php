<?php

namespace App\Http\Controllers\API\Core\Access;
use App\Http\Controllers\Controller;

// Helper
use App\Helpers\ErrorHelper;

// Model
use Spatie\Permission\Models\Permission;

// Request
use App\Http\Requests\PermissionCreateRequest;

// Internal
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

// External
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller{
    // List
    public function list(){
        try{
            // Get permission data
            $datas = Permission::with([
                'roles'
            ])->get();

            // Return response as datatable
            if(isset($request->type) && ($request->type == 'datatable')){
                return response()->json([
                    'success'   => true,
                    'data'      => DataTables::of($datas)->toJson(),
                ], 200);
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

            // Implementing db transaction
            return DB::transaction(function() use($request){
                // Create permission
                $create = Permission::create([
                    'name' => $request->name,
                ]);

                // Return response
                return response()->json([
                    'success'   => true,
                    'data'      => $create,
                    'message'   => "Permission created successfully.",
                ], 201);
            });
        }
        catch(\Throwable $th){
            return ErrorHelper::apiErrorResult();
        }
    }

    // Read
    public function read(Request $request){
        try{
            // Get permission data
            $datas = Permission::with([
                'roles'
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

    // Delete
    public function delete(Request $request){
        try{
            // Implementing db transaction
            return DB::transaction(function() use($request){
                // Find permission
                $datas = Permission::find($request->id);

                // Delete permission
                $datas->delete();

                // Return response
                return response()->json([
                    'success'   => true,
                    'message'   => "Permission successfully deleted.",
                ], 200);
            });
        }
        catch(\Throwable $th){
            return ErrorHelper::apiErrorResult();
        }
    }
}
