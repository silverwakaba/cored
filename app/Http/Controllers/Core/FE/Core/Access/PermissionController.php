<?php

namespace App\Http\Controllers\Core\FE\Core\Access;
use App\Http\Controllers\Controller;

// Repository interface
use App\Contracts\ApiRepositoryInterface;

// Helper
use App\Helpers\ErrorHelper;

// Internal
use Illuminate\Http\Request;

class PermissionController extends Controller{
    // Property
    protected $apiRepository;

    // Constructor
    public function __construct(ApiRepositoryInterface $apiRepository){
        $this->apiRepository = $apiRepository;
    }

    // Index
    public function index(){
        return view('pages/app/rbac/permission/index');
    }

    // List
    public function list(){
        // Make http call
        $http = $this->apiRepository->withToken()->get('be.core.rbac.permission.index', array_merge(
            request()->all(), [
                'type'      => request()->type,
                'relation'  => ['roles:id,name'],
            ])
        );

        // Response
        return response()->json($http->json(), $http->status());
    }

    // Create
    public function create(Request $request){
        // Create permission
        $http = $this->apiRepository->withToken()->post('be.core.rbac.permission.store', [
            'name' => $request->name,
        ]);
        
        // Response for $create action
        return response()->json($http->json(), $http->status());
    }

    // Read
    public function read($id){
        // Make http call
        $http = $this->apiRepository->withToken()->get('be.core.rbac.permission.show', [
            'id'        => $id,
            'relation'  => ['roles:id,name'],
        ]);

        // Response
        return response()->json($http->json(), $http->status());
    }

    // Update
    public function update($id, Request $request){
        // Update permission
        $http = $this->apiRepository->withToken()->put('be.core.rbac.permission.update', [
            'id'    => $id,
            'name'  => $request->name,
        ]);
        
        // Response for $update action
        return response()->json($http->json(), $http->status());
    }

    // Delete
    public function delete($id, Request $request){
        // Delete permission
        $http = $this->apiRepository->withToken()->delete('be.core.rbac.permission.destroy', [
            'id'    => $id,
            'name'  => $request->name,
        ]);
        
        // Response for $delete action
        return response()->json($http->json(), $http->status());
    }
}
