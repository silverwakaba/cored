<?php

namespace App\Http\Controllers\FE\Core\Access;
use App\Http\Controllers\Controller;

// Repository interface
use App\Contracts\ApiRepositoryInterface;

// Helper
use App\Helpers\ErrorHelper;

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
        $datas['roles'] = collect(auth()->user()->roles)->pluck('name')->all();

        return view('pages/app/role/index');
    }

    // List
    public function list(){
        // Make http call
        $http = $this->apiRepository->withToken()->get('be.core.rnp.role.list', array_merge(
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
        // Create role
        $create = $this->apiRepository->withToken()->post('be.core.rnp.role.create', [
            'name' => $request->name,
        ]);

        // Sync role to permission if create is success
        if(($create->status() == 201) && ($request->permission)){
            // Sync role
            $sync = $this->apiRepository->withToken()->post('be.core.rnp.role.stp', [
                'id'            => $create['data']['id'],
                'permission'    => $request->permission,
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
        $http = $this->apiRepository->withToken()->get('be.core.rnp.role.read', ['id' => $id]);

        // Response
        return response()->json($http->json(), $http->status());
    }

    // Sync to permission
    public function syncToPermission($id, Request $request){
        // Make http call
        $http = $this->apiRepository->withToken()->post('be.core.rnp.role.stp', [
            'id'            => $id,
            'permission'    => $request->permission,
        ]);

        // Response
        return response()->json($http->json(), $http->status());
    }
}
