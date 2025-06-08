<?php

namespace App\Http\Controllers\API\Core\Access;
use App\Http\Controllers\Controller;

// Repository interface
use App\Contracts\UserRepositoryInterface;

// Helper
use App\Helpers\AuthHelper;
use App\Helpers\ErrorHelper;
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
            // Get data
            $datas = $this->repositoryInterface;

            // Load relation
            if(isset($request->relation)){
                $datas->withRelation($request->relation);
            }

            // Return response as datatable
            if(isset($request->type) && ($request->type == 'datatable')){
                return $datas->useDatatable()->all();
            }

            // Return response
            return $datas->all();
        }
        catch(\Throwable $th){
            return ErrorHelper::apiErrorResult();
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

            // Return created data
            return response()->json([
                'success'   => true,
                'data'      => $datas,
                'message'   => "User created successfully.",
            ], 201);
        }
        catch(\Throwable $th){
            return ErrorHelper::apiErrorResult();
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

            $newDatas = $datas->find($request->id);

            // Return 404
            if(!$newDatas){
                return ErrorHelper::apiError404Result();
            }

            // Return created data
            return response()->json([
                'success'   => true,
                'data'      => $newDatas,
            ], 200);
        }
        catch(\Throwable $th){
            return ErrorHelper::apiErrorResult();
        }
    }

    // Update
    public function update(Request $request){
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

            // Init id
            $id = null;
            
            // Edit specific user
            if(
                // User within role level 1 can edit specific user
                (AuthHelper::roleLevel('1') == true) && (in_array($request->id, AuthHelper::roleUser(['Root'])))

                xor

                // User within role level 3 can edit specific user, but they can't edit user with 'Root' role
                (AuthHelper::roleLevel('3') == true) && (!in_array($request->id, AuthHelper::roleUser(['Root'])))
            ){
                // specific user id
                $id = $request->id;
            } else{
                // User outside role level 1 and 3 can only edit their own
                $id = AuthHelper::authID();
            }

            // Update data
            $datas = $this->repositoryInterface->update($id, [
                'name'  => $request->name,
                'email' => $request->email,
            ]);

            // Return updated data
            return response()->json([
                'success'   => true,
                'data'      => $datas,
                'message'   => "User updated successfully.",
            ], 200);
        }
        catch(\Throwable $th){
            return ErrorHelper::apiErrorResult();
        }
    }

    // Activation
    public function activation(Request $request){
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

            // Init id
            $id = null;
            
            // De/Activating specific user
            if(
                // User within role level 1 can de/activating specific user
                (AuthHelper::roleLevel('1') == true) && (in_array($request->id, AuthHelper::roleUser(['Root'])))

                xor

                // User within role level 3 can de/activating specific user, but they can't de/activating user with 'Root' role
                (AuthHelper::roleLevel('3') == true) && (!in_array($request->id, AuthHelper::roleUser(['Root'])))
            ){
                // Specific user id
                $id = $request->id;
            } else{
                // User outside role level 1 and 3 can only de/activating their own
                $id = AuthHelper::authID();
            }

            // Init activation
            $activation = (bool) $request->activation;

            // Read user account
            $datas = $this->repositoryInterface->activation($id, $activation);

            // State message
            $state = ($activation == true) ? 'activated' : 'deactivated';

            // Return created data
            return response()->json([
                'success'   => true,
                'data'      => $datas,
                'message'   => "User $state successfully.",
            ], 200);
        }
        catch(\Throwable $th){
            return ErrorHelper::apiErrorResult();
        }
    }
}
