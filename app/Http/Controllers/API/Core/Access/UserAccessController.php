<?php

namespace App\Http\Controllers\API\Core\Access;
use App\Http\Controllers\Controller;

// Repository interface
use App\Contracts\UserRepositoryInterface;

// Helper
use App\Helpers\GeneralHelper;

// Request
use App\Http\Requests\UserActivationRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;

// Internal
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserAccessController extends Controller{
    // Property
    private $repositoryInterface;

    // Constructor
    public function __construct(UserRepositoryInterface $repositoryInterface){
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
            $validator = Validator::make($request->all(), (new UserCreateRequest())->rules());

            // Check validation and stop if failed
            if($validator->fails()){
                return response()->json([
                    'success'   => false,
                    'errors'    => $validator->errors(),
                ], 422);
            }

            // Create registered user
            $datas = $this->repositoryInterface->prepare([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => bcrypt(GeneralHelper::randomPassword()),
            ])->role($request->role)->register();

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 201,
                'data'      => $datas,
                'message'   => 'User created successfully.',
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
            // Read user account
            $datas = $this->repositoryInterface;

            // Load relation
            if(isset($request->relation)){
                $datas->withRelation($request->relation);
            }

            // Continue variable
            $datas = $datas->find($request->id);

            // Return created data
            return response()->json([
                'success'   => true,
                'data'      => $datas,
            ], 200);
        }
        catch(\Throwable $th){
            return GeneralHelper::jsonResponse([
                'status'    => 409,
                'message'   => null,
            ]);
        }
    }

    // Update
    public function update($id, Request $request){
        try{
            // Validate input
            $validator = Validator::make($request->all(), (new UserUpdateRequest())->rules());

            // Check validation and stop if failed
            if($validator->fails()){
                return response()->json([
                    'success'   => false,
                    'errors'    => $validator->errors(),
                ], 422);
            }

            // Update registered user
            $datas = $this->repositoryInterface->modify($id, [
                'name'  => $request->name,
                'email' => $request->email,
            ]);

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 200,
                'data'      => $datas,
                'message'   => 'User updated successfully.',
            ]);
        }
        catch(\Throwable $th){
            return GeneralHelper::jsonResponse([
                'status'    => 409,
                'message'   => null,
            ]);
        }
    }

    // Activation
    public function activation($id, Request $request){
        try{
            // Validate input
            $validator = Validator::make($request->all(), (new UserActivationRequest())->rules());

            // Check validation and stop if failed
            if($validator->fails()){
                return response()->json([
                    'success'   => false,
                    'errors'    => $validator->errors(),
                ], 422);
            }

            // Init activation
            $activation = (bool) $request->activation;

            // Read user account
            $datas = $this->repositoryInterface->activate($id, $activation);

            // State message
            $state = ($activation == true) ? 'activated' : 'deactivated';

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 200,
                'data'      => $datas,
                'message'   => "User $state successfully.",
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
