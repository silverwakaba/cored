<?php

namespace App\Http\Controllers\Core\FE\Shared;
use App\Http\Controllers\Controller;

// Repository interface
use App\Contracts\Core\ApiRepositoryInterface;

// Internal
use Illuminate\Http\Request;

class ItemController extends Controller{
    // Property
    protected $apiRepository;

    // Constructor
    public function __construct(ApiRepositoryInterface $apiRepository){
        $this->apiRepository = $apiRepository;
    }

    // Index
    public function index(){
        return view('pages/app/base/item/index');
    }

    // List
    public function list(){
        // Make http call
        $http = $this->apiRepository->withToken()->get('be.core.base.item.index', array_merge(
            request()->all(), [
                'type' => request()->type,
            ])
        );

        // Response
        return response()->json($http->json(), $http->status());
    }

    // Create
    public function create(Request $request){
        // Create item
        $http = $this->apiRepository->withToken()->post('be.core.base.item.store', [
            'name_master' => $request->name_master,
            'description_master' => $request->description_master,
            'details' => $request->details ?? [],
        ]);
        
        // Response for $create action
        return response()->json($http->json(), $http->status());
    }

    // Read
    public function read($id){
        // Make http call
        $http = $this->apiRepository->withToken()->get('be.core.base.item.show', [
            'id' => $id,
        ]);

        // Response
        return response()->json($http->json(), $http->status());
    }

    // Update
    public function update($id, Request $request){
        // Update item
        $http = $this->apiRepository->withToken()->put('be.core.base.item.update', [
            'id'    => $id,
            'name_master'  => $request->name_master,
            'description_master' => $request->description_master,
            'details' => $request->details ?? [],
        ]);
        
        // Response for $update action
        return response()->json($http->json(), $http->status());
    }

    // Delete
    public function delete($id, Request $request){
        // Delete item
        $http = $this->apiRepository->withToken()->delete('be.core.base.item.destroy', [
            'id' => $id,
        ]);
        
        // Response for $delete action
        return response()->json($http->json(), $http->status());
    }

    // Bulk Destroy
    public function bulkDestroy(Request $request){
        // Bulk delete item
        $http = $this->apiRepository->withToken()->post('be.core.base.item.bulk-destroy', [
            'ids' => $request->input('ids', []),
        ]);
        
        // Response for bulk delete action
        return response()->json($http->json(), $http->status());
    }
}

