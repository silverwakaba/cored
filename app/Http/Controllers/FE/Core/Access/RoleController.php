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
        // return response()->json($http->json(), $http->status());
        return response()->json($http->json(), $http->status());
    }

    // create

    // read
    public function read($id){
        // Make http call
        $http = $this->apiRepository->withToken()->get('be.core.rnp.role.read', ['id' => $id]);

        // Response
        return response()->json($http->json(), $http->status());
    }

    // Sync to permission
    public function syncToPermission($id){
        // 
    }

    // delete
}
