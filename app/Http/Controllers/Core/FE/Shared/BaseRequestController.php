<?php

namespace App\Http\Controllers\Core\FE\Shared;
use App\Http\Controllers\Controller;

// Repository interface
use App\Contracts\Core\ApiRepositoryInterface;

// Internal
use Illuminate\Http\Request;

class BaseRequestController extends Controller{
    // Property
    protected $apiRepository;

    // Constructor
    public function __construct(ApiRepositoryInterface $apiRepository){
        $this->apiRepository = $apiRepository;
    }

    // Index
    public function index(){
        return view('pages/app/base/request/index');
    }

    // List
    public function list(){
        // Make http call
        $http = $this->apiRepository->withToken()->get('be.core.base.request.index', array_merge(
            request()->all(), [
                'type'      => request()->type,
                'relation'  => ['baseModule:id,name'],
            ])
        );

        // Response
        return response()->json($http->json(), $http->status());
    }

    // Create
    public function create(Request $request){
        // Create permission
        $http = $this->apiRepository->withToken()->post('be.core.base.request.store', [
            'module'    => $request->module,
            'name'      => $request->name,
            'detail'    => $request->detail,
        ]);
        
        // Response for $create action
        return response()->json($http->json(), $http->status());
    }

    // Read
    public function read($id){
        // Make http call
        $http = $this->apiRepository->withToken()->get('be.core.base.request.show', [
            'id'        => $id,
            'relation'  => ['baseModule:id,name'],
        ]);

        // Response
        return response()->json($http->json(), $http->status());
    }

    // Update
    public function update($id, Request $request){
        // Update permission
        $http = $this->apiRepository->withToken()->put('be.core.base.request.update', [
            'id'        => $id,
            'module'    => $request->module,
            'name'      => $request->name,
            'detail'    => $request->detail,
        ]);
        
        // Response for $update action
        return response()->json($http->json(), $http->status());
    }

    // Delete
    public function delete($id, Request $request){
        // Delete permission
        $http = $this->apiRepository->withToken()->delete('be.core.base.request.destroy', [
            'id' => $id,
        ]);
        
        // Response for $delete action
        return response()->json($http->json(), $http->status());
    }
}
