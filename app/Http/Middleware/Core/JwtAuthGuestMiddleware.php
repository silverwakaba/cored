<?php

namespace App\Http\Middleware\Core;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// Repository interface
use App\Contracts\Core\ApiRepositoryInterface;

// Helper
use App\Helpers\Core\CookiesHelper;

// Internal
use Illuminate\Support\Facades\Cookie;

// External
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

// This middleware is created to restrict access to frontend auth routes for ALREADY authorized user
class JwtAuthGuestMiddleware{
    // Property
    protected $apiRepository;

    // Constructor
    public function __construct(ApiRepositoryInterface $apiRepository){
        $this->apiRepository = $apiRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next) : Response{
        try{
            // Check the session
            JWTAuth::setToken(CookiesHelper::jwtToken())->authenticate();
    
            // If the user is authenticated, redirect to the index page
            return redirect()->route('fe.page.index');
        }
        catch(\Throwable $th){
            // For some unknown reason authenticated user can't be redirected
            // So we force them to logout and grant access to the request
            $http = $this->apiRepository->withToken()->post('be.core.auth.jwt.logout');

            // Delete JWT-related cookie
            Cookie::expire('jwt_token');

            Cookie::expire('jwt_ttl');

            if(CookiesHelper::jwtRemember() == true){
                Cookie::expire('jwt_remember');

                Cookie::expire('jwt_user_id');
            }

            return $next($request);
        }
    }
}






