<?php

namespace App\Http\Controllers\Core\API\Shared;
use App\Http\Controllers\Controller;

// Repository interface
use App\Contracts\Core\BaseModuleRepositoryInterface;

// Helper
use App\Helpers\Core\GeneralHelper;

// Request
// use App\Http\Requests\Core\CTAMessageRequest;

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
}
