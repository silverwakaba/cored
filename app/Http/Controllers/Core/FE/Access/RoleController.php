<?php

namespace App\Http\Controllers\Core\FE\Access;
use App\Http\Controllers\Controller;

// Repository interface
use App\Contracts\Core\ApiRepositoryInterface;

// Internal
use Illuminate\Http\Request;

class RoleController extends Controller{
    // Property
    protected $apiRepository;

    // Constructor
    public function __construct(ApiRepositoryInterface $apiRepository){
        $this->apiRepository = $apiRepository;
    }

    // Index
    public function index(){
        return view('pages/app/rbac/role/index');
    }

    // List
    public function list(){
        // Make http call
        $http = $this->apiRepository->withToken()->get('be.core.rbac.role.index', array_merge(
            request()->all(), [
                'type'      => request()->type,
                'relation'  => ['permissions:id,name'],
            ])
        );

        // Response
        return response()->json($http->json(), $http->status());
    }

    // Create
    public function create(Request $request){
        // Check if permission is provided - use atomic transaction endpoint
        if($request->permission){
            // Create role and sync permission in a single atomic transaction
            $response = $this->apiRepository->withToken()->post('be.core.rbac.role.store_with_permission', [
                'name'          => $request->name,
                'permission'    => $request->permission,
            ]);

            // Response
            return response()->json($response->json(), $response->status());
        }

        // Create role only (no permission sync)
        $create = $this->apiRepository->withToken()->post('be.core.rbac.role.store', [
            'name' => $request->name,
        ]);
        
        // Response for $create action
        return response()->json($create->json(), $create->status());
    }

    // Read
    public function read($id){
        // Make http call
        $http = $this->apiRepository->withToken()->get('be.core.rbac.role.show', [
            'id'        => $id,
            'relation'  => ['permissions:id,name'],
        ]);

        // Response
        return response()->json($http->json(), $http->status());
    }

    // Sync to permission
    public function syncToPermission($id, Request $request){
        // Make http call
        $http = $this->apiRepository->withToken()->post('be.core.rbac.role.sync_to_permission', [
            'id' => $id,
        ]);

        // Response
        return response()->json($http->json(), $http->status());
    }
}
