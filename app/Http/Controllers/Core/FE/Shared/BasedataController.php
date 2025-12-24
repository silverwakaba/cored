<?php

namespace App\Http\Controllers\Core\FE\Shared;
use App\Http\Controllers\Controller;

// Repository interface
use App\Contracts\Core\ApiRepositoryInterface;

// Helper
use App\Helpers\Core\ErrorHelper;

// Internal
use Illuminate\Http\Request;

class BasedataController extends Controller{
    // Property
    protected $apiRepository;

    // Constructor
    public function __construct(ApiRepositoryInterface $apiRepository){
        $this->apiRepository = $apiRepository;
    }

    // Boolean
    public function boolean(){
        try{
            // Make http call
            $http = $this->apiRepository->withToken()->get('be.core.base.general.boolean');

            // Response
            return response()->json($http->json(), $http->status());
        }
        catch(\Throwable $th){
            return ErrorHelper::apiErrorResult();
        }
    }
}
