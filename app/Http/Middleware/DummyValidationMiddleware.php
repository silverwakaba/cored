<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// Stupid validation method but it works
class DummyValidationMiddleware{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next) : Response{
        try{
            if(password_verify('majubersama', $request->key)){
                return $next($request);
            }
            else{
                // Emulate exception error that token is invalid
                return response()->json([
                    'success'   => false,
                    'message'   => 'Unauthorized access.',
                ], 401);
            }
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
