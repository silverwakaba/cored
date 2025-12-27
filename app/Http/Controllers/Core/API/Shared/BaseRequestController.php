<?php

namespace App\Http\Controllers\Core\API\Shared;
use App\Http\Controllers\Controller;

// Repository interface
use App\Contracts\Core\BaseRequestRepositoryInterface;

// Event
use App\Events\Core\GeneralEventHandler;

// Helper
use App\Helpers\Core\GeneralHelper;

// Request
use App\Http\Requests\Core\BaseRequestRequest;

// Internal
use Illuminate\Http\Request;

class BaseRequestController extends Controller{
    // Property
    private $repositoryInterface;

    // Constructor
    public function __construct(BaseRequestRepositoryInterface $repositoryInterface){
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

            // Apply filters if provided
            $filters = $request->only(array_filter(array_keys($request->all()), function($key){
                return strpos($key, 'filter') === 0;
            }));

            // Run filter as sub-query
            if(!empty($filters)){
                $datas->query->where(function($query) use($filters){
                    foreach($filters as $filterKey => $filterValue){
                        // Status active filters
                        if(in_array($filterKey, ['filter-active'])){
                            // Skip if value is empty string or null, but allow "0" and false
                            if(in_array($filterValue, [null, ''])){
                                continue;
                            }
                            
                            // Convert to boolean if needed
                            if(is_string($filterValue)){
                                // Convert string to boolean
                                $filterValue = filter_var($filterValue, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                            }
                            
                            // Only apply filter if value is valid boolean, not null after conversion
                            if($filterValue !== null){
                                $query->where('is_active', $filterValue);
                            }
                        }

                        // Module filters
                        if(in_array($filterKey, ['filter-module'])){
                            $query->whereHas('baseModule', function($q) use($filterValue){
                                $q->where('id', $filterValue);
                            });
                        }
                    }
                });
            }

            // Return response
            return ($request->type === 'datatable') ? $datas->useDatatable()->all() : $datas->all();
        }, ['status' => 409, 'message' => true]);
    }

    // Create
    public function create(Request $request){
        return GeneralHelper::safe(function() use($request){
            // Validate input
            $validated = GeneralHelper::validate($request->all(), (new BaseRequestRequest())->rules());

            // Stop if validation failed
            if(!is_array($validated)){
                return $validated;
            }

            // Create base request
            $datas = $this->repositoryInterface->broadcaster(GeneralEventHandler::class, 'create')->create([
                'base_modules_id'   => $request['module'],
                'name'              => $request['name'],
                'detail'            => $request['detail'],
            ]);

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 201,
                'data'      => $datas,
                'message'   => 'Base request created successfully.',
            ]);
        }, ['status' => 409, 'message' => true]);
    }

    // Read
    public function read($id, Request $request){
        return GeneralHelper::safe(function() use($id, $request){
            // Get base request data
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

    // Update
    public function update($id, Request $request){
        return GeneralHelper::safe(function() use($id, $request){
            // Validate input
            $validated = GeneralHelper::validate($request->all(), (new BaseRequestRequest())->rules());

            // Stop if validation failed
            if(!is_array($validated)){
                return $validated;
            }

            // Update base request data
            $datas = $this->repositoryInterface->broadcaster(GeneralEventHandler::class, 'update')->update($id, [
                'base_modules_id'   => $request['module'],
                'name'              => $request['name'],
                'detail'            => $request['detail'],
            ]);

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 200,
                'data'      => $datas,
                'message'   => 'Base request updated successfully.',
            ]);
        }, ['status' => 409, 'message' => true]);
    }

    // Delete
    public function delete($id, Request $request){
        return GeneralHelper::safe(function() use($id, $request){
            // Delete base request data (actually toggles activation status)
            $result = $this->repositoryInterface->broadcaster(GeneralEventHandler::class, 'delete')->activation($id);

            // Get action and data from result
            $action = $result['action'];

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 200,
                'message'   => "Base request {$action} successfully.",
            ]);
        }, ['status' => 409, 'message' => true]);
    }
}
