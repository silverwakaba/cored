<?php

namespace App\Http\Middleware\Core;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Helpers\Core\CookiesHelper;

use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

// This middleware is created ONLY to facilitate Blade's @auth and/or @guest authentication-related directives WITHOUT any restriction
class JwtAuthGlobalMiddleware{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next) : Response{
        try{
            // Set JWT token
            JWTAuth::setToken(CookiesHelper::jwtToken())->authenticate();
        }
        catch(\Throwable $th){
            // no restriction or error message
        }

        return $next($request);
    }
}






