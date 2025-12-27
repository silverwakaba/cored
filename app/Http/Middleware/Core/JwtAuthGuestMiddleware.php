<?php

namespace App\Http\Middleware\Core;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// Helper
use App\Helpers\Core\CookiesHelper;

// External
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;

// This middleware is created to restrict access to frontend auth routes for ALREADY authorized user
class JwtAuthGuestMiddleware{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next) : Response{
        try{
            // Get token from cookie
            $token = CookiesHelper::jwtToken();

            // If token exists, try to authenticate
            if($token){
                JWTAuth::setToken($token)->authenticate();
                
                // If authentication succeeds, user is already logged in
                // Redirect to index page to prevent access to auth pages
                return redirect()->route('fe.page.index');
            }
        }
        catch(JWTException $th){
            // Token is invalid, expired, or missing
            // This is expected for guest users, so allow access to auth pages
        }

        // Allow guest users to access auth pages
        return $next($request);
    }
}
