<?php

namespace App\Http\Middleware;

// Helper
use App\Helpers\ErrorHelper;
use App\Helpers\RateLimitHelper;

// Internal
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

// External
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

// Custom middleware for api rate limiter
class RateLimitMiddleware{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next) : Response{
        try{
            // Bypass this middleware for internal api request
            if(
                // Get header ip
                (request()->header(RateLimitHelper::internalHeaderIP()) == '127.0.0.1')

                &&

                // Get and verify header token
                (password_verify('apiInternalToken', request()->header(RateLimitHelper::internalHeaderToken())))

                &&

                // Get header type
                (request()->header(RateLimitHelper::internalHeaderType()) == 'apiInternalRequest')
            ){
                // Return the result
                return $next($request);
            }

            // Define default variable
            $action = null; $limit = null; $getIdentifier = null;

            // Define rate limit for each PUBLIC action
            // If route is NOT DEFINED then public access will always return the "rate limited" result
            switch(request()){
                // Register
                // case request()->routeIs('be.auth.register'): $action = 'register'; $limit = 3;

                // Login
                // case request()->routeIs('be.auth.login'): $action = 'login'; $limit = 3;
            }

            // Set identifier based on authentication
            try{
                JWTAuth::parseToken()->authenticate();

                $getIdentifier = auth()->user()->id;
            }
            catch(\Throwable $th){ // Otherwise set identifier based on user ip
                $getIdentifier = hash('crc32', 'guest:' . request()->ip());
            }

            // Rate limit handler
            if(
                // Check if action is being defined
                ($action != null)
                
                &&
                
                // Check remaining limit
                (RateLimiter::remaining($action . ':' . $getIdentifier, $perMinute = $limit))
            ){
                // Increase increment for each action
                RateLimiter::increment($action . ':' . $getIdentifier);

                // Return the result
                return $next($request);
            }

            // Return error if request is limited
            return ErrorHelper::apiErrorLimiterResult();
        }
        catch(\Throwable $th){
            return ErrorHelper::apiErrorResult( $th );
        }
    }
}
