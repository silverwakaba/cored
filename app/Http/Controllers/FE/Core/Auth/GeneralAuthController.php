<?php

namespace App\Http\Controllers\FE\Core\Auth;
use App\Http\Controllers\Controller;

// Repository interface
use App\Contracts\ApiRepositoryInterface;

// Helper
use App\Helpers\ErrorHelper;

// Internal
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

// Delete
use App\Helpers\CookiesHelper;

class GeneralAuthController extends Controller{
    // Property
    protected $apiRepository;

    // Constructor
    public function __construct(ApiRepositoryInterface $apiRepository){
        $this->apiRepository = $apiRepository;
    }

    // Register
    public function register(){
        return view('pages/auth/register');
    }

    public function registerPost(Request $request){
        try{
            // Make http call
            $http = $this->apiRepository->post('be.core.auth.jwt.register', [
                'name'                  => $request->name,
                'email'                 => $request->email,
                'password'              => $request->password,
                'password_confirmation' => $request->password_confirmation,
                'agreement'             => $request->agreement,
            ]);

            // Response
            return response()->json($http->json(), $http->status());
        }
        catch(\Throwable $th){
            return ErrorHelper::apiErrorResult();
        }
    }

    // Login
    public function login(){
        return view('pages/auth/login');
    }

    public function loginPost(Request $request){
        try{
            // Make http call
            $http = $this->apiRepository->post('be.core.auth.jwt.login', [
                'email'     => $request->email,
                'password'  => $request->password,
                'remember'  => $request->remember,
            ]);

            // Save the response as cookies
            if($http->successful()){
                // Set cookie expiration time
                $expire = (60 * 24) * 30;

                // Set JWT-related cookie from api call as authentication method
                Cookie::queue('jwt_token', $http['token'], $expire);
                Cookie::queue('jwt_ttl', $http['token_ttl'], $expire);

                // Further process if the session is remembered
                if(((bool) $request->remember == true)){
                    Cookie::queue('jwt_remember', true, $expire);

                    Cookie::queue('jwt_user_id', $http['data']['id'], $expire);
                }
            }

            // Response
            return response()->json($http->json(), $http->status());
        }
        catch(\Throwable $th){
            return ErrorHelper::apiErrorResult();
        }
    }

    // Validate token
    public function validate(){
        try{
            // Make http call
            $http = $this->apiRepository->withToken()->get('be.core.auth.jwt.token.validate');

            // Response
            return response()->json($http->json(), $http->status());
        }
        catch(\Throwable $th){
            return ErrorHelper::apiErrorResult();
        }
    }
}
