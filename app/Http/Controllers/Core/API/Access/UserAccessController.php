<?php

namespace App\Http\Controllers\Core\API\Access;
use App\Http\Controllers\Controller;

// Repository interface
use App\Contracts\Core\UserRepositoryInterface;

// Helper
use App\Helpers\Core\FileHelper;
use App\Helpers\Core\GeneralHelper;

// Request
use App\Http\Requests\Core\UserActivationRequest;
use App\Http\Requests\Core\UserCreateRequest;
use App\Http\Requests\Core\UserUpdateRequest;

// Internal
use Illuminate\Http\Request;

class UserAccessController extends Controller{
    // Property
    private $repositoryInterface;

    // Constructor
    public function __construct(UserRepositoryInterface $repositoryInterface){
        $this->repositoryInterface = $repositoryInterface;
    }

    // List
    public function list(Request $request){
        return GeneralHelper::safe(function() use($request){
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

            // Return response
            return ($request->type === 'datatable') ? $datas->useDatatable()->all() : $datas->all();
        });
    }

    // Create
    public function create(Request $request){
        return GeneralHelper::safe(function() use($request){
            // Validate input
            $validated = GeneralHelper::validate($request->all(), (new UserCreateRequest())->rules());

            // Stop if validation failed
            if(!is_array($validated)){
                return $validated;
            }

            // Create registered user
            $datas = $this->repositoryInterface->prepare([
                'name'      => $request['name'],
                'email'     => $request['email'],
                'password'  => bcrypt(GeneralHelper::randomPassword()),
            ])->role($request['role'])->register();

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 201,
                'data'      => $datas,
                'message'   => 'User created successfully.',
            ]);
        });
    }

    // Read
    public function read($id, Request $request){
        return GeneralHelper::safe(function() use($id, $request){
            // Read user account
            $datas = $this->repositoryInterface;

            // Load relation
            if(isset($request->relation)){
                $datas->withRelation($request->relation);
            }

            // Continue variable
            $datas = $datas->find($id);

            // Return created data
            return response()->json([
                'success'   => true,
                'data'      => $datas,
            ], 200);
        });
    }

    // Update
    public function update($id, Request $request){
        return GeneralHelper::safe(function() use($id, $request){
            // Validate input
            $validated = GeneralHelper::validate($request->all(), (new UserUpdateRequest())->rules());

            // Stop if validation failed
            if(!is_array($validated)){
                return $validated;
            }

            // Update registered user
            $datas = $this->repositoryInterface->modify($id, [
                'name'  => $request['name'],
                'email' => $request['email'],
            ]);

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 200,
                'data'      => $datas,
                'message'   => 'User updated successfully.',
            ]);
        });
    }

    // Activation
    public function activation($id, Request $request){
        return GeneralHelper::safe(function() use($id, $request){
            // Validate input
            $validated = GeneralHelper::validate($request->all(), (new UserActivationRequest())->rules());

            // Stop if validation failed
            if(!is_array($validated)){
                return $validated;
            }

            // Init activation
            $activation = (bool) $request['activation'];

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
        });
    }
}
