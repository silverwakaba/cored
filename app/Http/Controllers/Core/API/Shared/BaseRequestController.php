<?php

namespace App\Http\Controllers\Core\API\Shared;
use App\Http\Controllers\Controller;

// Repository interface
use App\Contracts\Core\BaseRequestRepositoryInterface;

// Helper
use App\Helpers\Core\GeneralHelper;

// Request
// use App\Http\Requests\Core\CTAMessageRequest;

// Internal
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

            // // Apply all filters if provided
            // $filters = $request->only(array_filter(array_keys($request->all()), function($key){
            //     return strpos($key, 'filter') === 0;
            // }));

            // // Run filter sub-query
            // if(!empty($filters)){
            //     $datas->query->where(function($query) use($filters){
            //         foreach($filters as $filterKey => $filterValue){
            //             if(!empty(trim($filterValue))){
            //                 $filterValue = trim($filterValue);
                            
            //                 // Filter by permission name
            //                 if(in_array($filterKey, ['filter-name'])){
            //                     $query->where('name', 'LIKE', '%' . $filterValue . '%');
            //                 }
                            
            //                 // Filter by role name
            //                 elseif(in_array($filterKey, ['filter-role'])){
            //                     $query->whereHas('roles', function($q) use($filterValue){
            //                         $q->where('name', 'LIKE', '%' . $filterValue . '%');
            //                     });
            //                 }
            //             }
            //         }
            //     });
            // }

            // Return response
            return ($request->type === 'datatable') ? $datas->useDatatable()->all() : $datas->all();
        }, ['status' => 409, 'message' => true]);
    }
}
