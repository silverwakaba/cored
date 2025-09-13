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
        // TBA
    }

    // List
    public function list(){
        // Make http call
        $http = $this->apiRepository->withToken()->get('be.core.rbac.uac.list', array_merge(
            request()->all(), [
                'type' => request()->type,
            ])
        );

        // Response
        return response()->json($http->json(), $http->status());
    }
}
