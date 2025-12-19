<?php

namespace App\Http\Controllers\Core\FE;
use App\Http\Controllers\Controller;

// Repository interface
use App\Contracts\Core\ApiRepositoryInterface;

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
        return view('pages/index');
    }

    // Index auth
    public function auth(){
        // Data option
        $datas = [
            'breadcrumb'    => 'auth',
            'title'         => 'Auth',
            'navigation'    => [
                // Register
                [
                    'icon'      => 'fas fa-user',
                    'title'     => 'Register',
                    'content'   => 'Register new account.',
                    'link'      => route('fe.auth.register'),
                ],

                // Login
                [
                    'icon'      => 'fas fa-user-secret',
                    'title'     => 'Login',
                    'content'   => 'Login with existing account.',
                    'link'      => route('fe.auth.login'),
                ],

                // Verify account
                [
                    'icon'      => 'fas fa-check',
                    'title'     => 'Verify Account',
                    'content'   => 'Request a new verification email.',
                    'link'      => route('fe.auth.verify-account'),
                ],

                // Verify account
                [
                    'icon'      => 'fas fa-lock',
                    'title'     => 'Reset Password',
                    'content'   => 'Reset the lost password.',
                    'link'      => route('fe.auth.reset-password'),
                ],
            ],
        ];

        // View
        return view('pages/app/index-standardized', [
            'datas' => $datas,
        ]);
    }

    // Index cta
    public function cta(){
        // Data option
        $datas = [
            'breadcrumb'    => 'cta',
            'title'         => 'Call To Action',
            'navigation'    => [
                // Message
                [
                    'icon'      => 'fas fa-message',
                    'title'     => 'Message',
                    'content'   => 'Send us a message.',
                    'link'      => route('fe.cta.message'),
                ],
            ],
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
            'navigation'    => [
                // Role
                [
                    'icon'      => 'fas fa-crown',
                    'title'     => 'Role',
                    'link'      => route('fe.apps.rbac.role.index'),
                ],

                // Permission
                [
                    'icon'      => 'fas fa-tags',
                    'title'     => 'Permission',
                    'link'      => route('fe.apps.rbac.permission.index'),
                ],

                // UAC
                [
                    'icon'      => 'fas fa-users',
                    'title'     => 'UAC',
                    'link'      => route('fe.apps.rbac.uac.index'),
                ],
            ],
        ];

        // View
        return view('pages/app/index-standardized', [
            'datas' => $datas,
        ]);
    }
}
