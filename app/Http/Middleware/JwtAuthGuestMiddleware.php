<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Helpers\CookiesHelper;

use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

// This middleware is created to restrict access to frontend auth routes for ALREADY authorized user
class JwtAuthGuestMiddleware{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next) : Response{
        try{
            // Check the session
            JWTAuth::setToken(CookiesHelper::jwtToken())->authenticate();

            // Redirect to previous page if history is available
            if($request->session()->has('_previous.url')){
                return back();
            }
    
            // Default redirect when no history is available
            return redirect()->route('fe.page.index');
        }
        catch(\Throwable $th){
            // no restriction or error message to avoid error
        }

        // Don't have session
        return $next($request);
    }
}
