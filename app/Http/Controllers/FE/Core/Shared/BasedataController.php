<?php

namespace App\Http\Controllers\FE\Core\Shared;
use App\Http\Controllers\Controller;

// Repository interface
use App\Contracts\ApiRepositoryInterface;

// Helper
use App\Helpers\ErrorHelper;

// Internal
use Illuminate\Http\Request;

class BasedataController extends Controller{
    // Property
    protected $apiRepository;

    // Constructor
    public function __construct(ApiRepositoryInterface $apiRepository){
        $this->apiRepository = $apiRepository;
    }

    // Menu
    public function menu(){
        try{
            // Make http call
            $http = $this->apiRepository->withToken()->get('be.core.menu.index');

            // Response
            return response()->json($http->json(), $http->status());
        }
        catch(\Throwable $th){
            return ErrorHelper::apiErrorResult();
        }
    }
}
