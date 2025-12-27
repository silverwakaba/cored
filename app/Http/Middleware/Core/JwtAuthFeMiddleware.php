<?php

namespace App\Http\Middleware\Core;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

use App\Helpers\Core\CookiesHelper;

use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;

// This middleware is created to restrict access to authoritative frontend routes
class JwtAuthFeMiddleware{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next) : Response{
        try{
            // Authenticate using token from cookies
            JWTAuth::setToken(CookiesHelper::jwtToken())->authenticate();

            return $next($request);
        }
        catch(JWTException $th){
            // If token is invalid or expired then delete old token from cookies
            Cookie::expire('jwt_token');

            Cookie::expire('jwt_ttl');

            if(CookiesHelper::jwtRemember() == true){
                Cookie::expire('jwt_remember');

                Cookie::expire('jwt_user_id');
            }

            // Save intended URL to session for redirect after login
            if(!$request->expectsJson()){
                session()->put('url.intended', $request->fullUrl());
            }

            // Redirect to login page
            return redirect()->route('fe.auth.login')->with('class', 'warning')->with('message', "Session expired and/or invalid. Please try to login again.");
        }
    }
}
