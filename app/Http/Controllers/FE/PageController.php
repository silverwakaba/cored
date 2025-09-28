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
        return view('pages/blank');
    }

    // Index auth
    public function auth(){
        // Data option
        $datas = [
            'breadcrumb'    => 'auth',
            'title'         => 'Auth',
        ];

        // View
        return view('pages/app/index-standardized', [
            'datas' => $datas,
        ]);
    }

    // Index app
    public function app(){
        return view('pages/app/index');
    }

    // Index app/rbac
    public function appRBAC(){
        // Data option
        $datas = [
            'breadcrumb'    => 'apps.rbac',
            'title'         => 'RBAC',
        ];

        // View
        return view('pages/app/index-standardized', [
            'datas' => $datas,
        ]);
    }
}
