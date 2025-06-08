<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Helpers\CookiesHelper;

use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

// This middleware is created to restrict access to frontend register/login routes for ALREADY authorized user
class JwtAuthGuestMiddleware{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next) : Response{
        try{
            JWTAuth::setToken(CookiesHelper::jwtToken())->authenticate();

            return back()->with('class', 'warning')->with('message', __('auth.guest_only'));
        }
        catch(\Throwable $th){
            // no restriction or error message
        }

        return $next($request);
    }
}
