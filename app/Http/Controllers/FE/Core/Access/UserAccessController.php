<?php

namespace App\Http\Controllers\FE\Core\Access;
use App\Http\Controllers\Controller;

// Repository interface
use App\Contracts\ApiRepositoryInterface;

// Helper
use App\Helpers\ErrorHelper;

// Internal
use Illuminate\Http\Request;

class UserAccessController extends Controller{
    // Property
    protected $apiRepository;

    // Constructor
    public function __construct(ApiRepositoryInterface $apiRepository){
        $this->apiRepository = $apiRepository;
    }

    // Index
    public function index(){
        return view('pages/app/rbac/uac/index');
    }

    // List
    public function list(){
        // Make http call
        $http = $this->apiRepository->withToken()->get('be.core.rbac.uac.list', array_merge(
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
        // Create user
        $create = $this->apiRepository->withToken()->post('be.core.rbac.uac.create', [
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        // Sync role to user if create is success
        if(($create->status() == 201) && ($request->role)){
            // Sync role
            $sync = $this->apiRepository->withToken()->post('be.core.rbac.role.stu', [
                'id'    => $create['data']['id'],
                'role'  => $request->role,
            ]);

            // Response for $sync action
            return response()->json($sync->json(), $sync->status());
        }
        
        // Response for $create action
        return response()->json($create->json(), $create->status());
    }

    // Read
    public function read($id){
        // Make http call
        $http = $this->apiRepository->withToken()->get('be.core.rbac.uac.read', [
            'id'        => $id,
            'relation'  => ['roles:id,name'],
        ]);

        // Response
        return response()->json($http->json(), $http->status());
    }

    // Update
    public function update($id, Request $request){
        // Update user
        $update = $this->apiRepository->withToken()->post('be.core.rbac.uac.update', [
            'id'    => $id,
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        // Sync role to user if update is success
        if(($update->status() == 200) && ($request->role)){
            // Sync role
            $sync = $this->apiRepository->withToken()->post('be.core.rbac.role.stu', [
                'id'    => $update['data']['id'],
                'role'  => $request->role,
            ]);

            // Response for $sync action
            return response()->json($sync->json(), $sync->status());
        }
        
        // Response for $update action
        return response()->json($update->json(), $update->status());
    }
}
