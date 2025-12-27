<?php

namespace App\Http\Controllers\Core\API\Access;
use App\Http\Controllers\Controller;

// Repository interface
use App\Contracts\Core\UserRepositoryInterface;

// Event
use App\Events\Core\GeneralEventHandler;

// Helper
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

            // Apply all filters if provided
            $filters = $request->only(array_filter(array_keys($request->all()), function($key){
                return strpos($key, 'filter') === 0;
            }));

            // Run filter sub-query
            if(!empty($filters)){
                $datas->query->where(function($query) use($filters){
                    foreach($filters as $filterKey => $filterValue){
                        // Role filters
                        if(in_array($filterKey, ['filter-role'])){
                            $query->whereHas('roles', function($q) use($filterValue){
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
            $validated = GeneralHelper::validate($request->all(), (new UserCreateRequest())->rules());

            // Stop if validation failed
            if(!is_array($validated)){
                return $validated;
            }

            // Create registered user
            $datas = $this->repositoryInterface->broadcaster(GeneralEventHandler::class, 'create')->prepare([
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
        }, ['status' => 409, 'message' => false]);
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
        }, ['status' => 409, 'message' => false]);
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
            $datas = $this->repositoryInterface->broadcaster(GeneralEventHandler::class, 'update')->modify($id, [
                'name'  => $request->name,
                'email' => $request->email,
            ]);

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 200,
                'data'      => $datas,
                'message'   => 'User updated successfully.',
            ]);
        }, ['status' => 409, 'message' => false]);
    }

    // Delete
    public function delete($id, Request $request){
        return GeneralHelper::safe(function() use($id, $request){
            // Delete base module data (actually toggles activation status)
            $result = $this->repositoryInterface->broadcaster(GeneralEventHandler::class, 'delete')->activation($id);

            // Get action and data from result
            $action = $result['action'];

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 200,
                'message'   => "User {$action} successfully.",
            ]);
        }, ['status' => 409, 'message' => true]);
    }
}
