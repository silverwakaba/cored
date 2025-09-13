<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Helpers\CookiesHelper;

use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

// This middleware is created ONLY to facilitate Blade's @auth and/or @guest authentication-related directives WITHOUT any restriction
// Can also facilitate backend public route that need to load user data
class JwtAuthGlobalMiddleware{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next) : Response{
        try{
            // Nested try so that it won't return error message
            try{
                // Parse JWT token (mainly used on API)
                JWTAuth::parseToken()->authenticate();
            }
            catch(\Throwable $th){
                // Set JWT token (mainly used on WEB)
                JWTAuth::setToken(CookiesHelper::jwtToken())->authenticate();
            }
        }
        catch(\Throwable $th){
            // no restriction or error message
        }

        return $next($request);
    }
}
