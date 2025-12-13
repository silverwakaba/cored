<?php

namespace App\Http\Middleware\Core;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;

// This middleware is created to restrict access to authoritative backend routes
// For spatie exception see: \bootstrap\app.php
class JwtAuthBeMiddleware{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next) : Response{
        try{
            // Authenticate using token
            JWTAuth::parseToken()->authenticate();

            return $next($request);
        }
        catch(JWTException $th){
            // Throw exception error if token is invalid
            return response()->json([
                'success'   => false,
                'message'   => 'Unauthorized access.',
            ], 401);
        }
    }
}






