<?php

namespace App\Http\Controllers\Core\API\Shared;
use App\Http\Controllers\Controller;

// Repository interface
use App\Contracts\Core\BaseModuleRepositoryInterface;

// Event
use App\Events\Core\GeneralEventHandler;

// Helper
use App\Helpers\Core\GeneralHelper;

// Request
use App\Http\Requests\Core\BaseModuleRequest;

// Internal
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BaseModuleController extends Controller{
    // Property
    private $repositoryInterface;

    // Constructor
    public function __construct(BaseModuleRepositoryInterface $repositoryInterface){
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

            // Apply filters if provided
            $filters = $request->only(array_filter(array_keys($request->all()), function($key){
                return strpos($key, 'filter') === 0;
            }));

            // Run filter as sub-query
            if(!empty($filters)){
                $datas->query->where(function($query) use($filters){
                    foreach($filters as $filterKey => $filterValue){
                        // Handle boolean filters (filter-active, etc.)
                        if(in_array($filterKey, ['filter-active'])){
                            // Skip if value is empty string or null (but allow "0" and false)
                            if(in_array($filterValue, [null, ''])){
                                continue;
                            }
                            
                            // Convert to boolean if needed
                            // Handles: string "0"/"1", string "true"/"false", boolean true/false
                            if(is_string($filterValue)){
                                // Convert string to boolean
                                // "0", "false", "off", "no" -> false
                                // "1", "true", "on", "yes" -> true
                                // invalid string -> null
                                $filterValue = filter_var($filterValue, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                            }
                            
                            // Only apply filter if value is valid boolean (not null after conversion)
                            // This allows both true and false values
                            if($filterValue !== null){
                                $query->where('is_active', $filterValue);
                            }
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
            $validated = GeneralHelper::validate($request->all(), (new BaseModuleRequest())->rules());

            // Stop if validation failed
            if(!is_array($validated)){
                return $validated;
            }

            // Create base module
            $datas = $this->repositoryInterface->broadcaster(GeneralEventHandler::class, 'create')->create([
                'name' => $request['name'],
            ]);

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 201,
                'data'      => $datas,
                'message'   => 'Base module created successfully.',
            ]);
        }, ['status' => 409, 'message' => false]);
    }

    // Read
    public function read($id, Request $request){
        return GeneralHelper::safe(function() use($id, $request){
            // Get base module data
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
            $validated = GeneralHelper::validate($request->all(), (new BaseModuleRequest())->rules());

            // Stop if validation failed
            if(!is_array($validated)){
                return $validated;
            }

            // Update base module data
            $datas = $this->repositoryInterface->broadcaster(GeneralEventHandler::class, 'update')->update($id, [
                'name' => $request['name'],
            ]);

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 200,
                'data'      => $datas,
                'message'   => 'Base module successfully.',
            ]);
        }, ['status' => 409, 'message' => true]);
    }

    // Delete
    public function delete($id, Request $request){
        return GeneralHelper::safe(function() use($id, $request){
            // Delete base module data
            $datas = $this->repositoryInterface->broadcaster(GeneralEventHandler::class, 'delete')->activation($id);

            // Return response
            return GeneralHelper::jsonResponse([
                'status'    => 200,
                'message'   => 'Base module deleted successfully.',
            ]);
        }, ['status' => 409, 'message' => true]);
    }
}
