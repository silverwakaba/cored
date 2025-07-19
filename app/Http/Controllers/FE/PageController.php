<?php

namespace App\Http\Controllers\FE;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use App\Contracts\ApiRepositoryInterface;

class PageController extends Controller{
    protected $apiRepository;

    public function __construct(ApiRepositoryInterface $apiRepository){
        $this->apiRepository = $apiRepository;
    }

    // Index
    public function index(){
        return view('pages/blank');
    }

    // Debug
    public function debug(){
        $response = $this->apiRepository->post('be.core.auth.jwt.login', [
            // 
        ]);

        return $response;
    }
}
