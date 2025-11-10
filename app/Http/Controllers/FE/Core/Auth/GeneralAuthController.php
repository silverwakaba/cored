<?php

namespace App\Http\Controllers\FE\Core\Auth;
use App\Http\Controllers\Controller;

// Repository interface
use App\Contracts\ApiRepositoryInterface;

// Helper
use App\Helpers\CookiesHelper;
use App\Helpers\ErrorHelper;
use App\Helpers\GeneralHelper;

// Internal
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

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
            return GeneralHelper::jsonResponse([
                'status'    => 409,
                'message'   => null,
            ]);
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
            return GeneralHelper::jsonResponse([
                'status'    => 409,
                'message'   => null,
            ]);
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
            return GeneralHelper::jsonResponse([
                'status'    => 409,
                'message'   => null,
            ]);
        }
    }

    // Logout
    public function logout(){
        try{
            // Make http call
            $http = $this->apiRepository->withToken()->post('be.core.auth.jwt.logout');

            // Delete JWT-related cookie
            Cookie::expire('jwt_token');

            Cookie::expire('jwt_ttl');

            if(CookiesHelper::jwtRemember() == true){
                Cookie::expire('jwt_remember');

                Cookie::expire('jwt_user_id');
            }

            // Redirect to login page
            return redirect()->route('fe.auth.login')->with('class', 'info')->with('message', "Session ended safely.");
        }
        catch(\Throwable $th){
            return GeneralHelper::jsonResponse([
                'status'    => 409,
                'message'   => null,
            ]);
        }
    }

    // Verify account
    public function verifyAccount(Request $request){
        return view('pages/auth/verify');
    }

    public function verifyAccountPost(Request $request){
        try{
            // Make http call
            $http = $this->apiRepository->post('be.core.auth.jwt.verify-account', [
                'email'     => $request->email,
                'agreement' => $request->agreement,
            ]);

            // Response
            return response()->json($http->json(), $http->status());
        }
        catch(\Throwable $th){
            return GeneralHelper::jsonResponse([
                'status'    => 409,
                'message'   => null,
            ]);
        }
    }
    
    // Verify account vie token
    public function verifyAccountTokenized($token){
        try{
            // Make http call
            $http = $this->apiRepository->post('be.core.auth.jwt.verify-account-tokenized', [
                'token' => $token,
            ]);

            // Redirect to login page
            return redirect()->route('fe.auth.login')->with('class', 'success')->with('message', $http['message']);
        }
        catch(\Throwable $th){
            return GeneralHelper::jsonResponse([
                'status'    => 409,
                'message'   => null,
            ]);
        }
    }

    // Reset password
    public function resetPassword(){
        return view('pages/auth/reset');
    }

    public function resetPasswordPost(Request $request){
        try{
            // Make http call
            $http = $this->apiRepository->post('be.core.auth.jwt.reset-password', [
                'email'     => $request->email,
                'agreement' => $request->agreement,
            ]);

            // Response
            return response()->json($http->json(), $http->status());
        }
        catch(\Throwable $th){
            return GeneralHelper::jsonResponse([
                'status'    => 409,
                'message'   => null,
            ]);
        }
    }

    // Reset password vie token
    public function resetPasswordTokenized($token){
        return view('pages/auth/reset-password', [
            'token' => $token,
        ]);
    }

    public function resetPasswordTokenizedPost($token, Request $request){
        try{
            // Make http call
            $http = $this->apiRepository->post('be.core.auth.jwt.reset-password-tokenized', [
                'token'                     => $token,
                'new_password'              => $request->new_password,
                'new_password_confirmation' => $request->new_password_confirmation,
                'agreement'                 => $request->agreement,
            ]);

            // Response
            return response()->json($http->json(), $http->status());
        }
        catch(\Throwable $th){
            return GeneralHelper::jsonResponse([
                'status'    => 409,
                'message'   => null,
            ]);
        }
    }
}
