<?php

namespace App\Http\Controllers\FE;
use App\Http\Controllers\Controller;

// Repository interface
use App\Contracts\ApiRepositoryInterface;

// Internal
use Illuminate\Http\Request;

class PageController extends Controller{
    // Property
    protected $apiRepository;

    // Constructor
    public function __construct(ApiRepositoryInterface $apiRepository){
        $this->apiRepository = $apiRepository;
    }

    // Index
    public function index(){
        $hash = password_hash("majubersama", PASSWORD_DEFAULT);

        return $hash;

        return view('pages/blank');
    }

    // Debug
    public function debug(){
        // 
    }
}
