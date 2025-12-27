<?php

namespace App\Http\Middleware\Core;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to add rate limit headers to response
 * 
 * Best Practice: Use this middleware after throttle middleware
 * to provide rate limit information to the client
 */
class RateLimitHeadersMiddleware{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $limiter  Name of the rate limiter being used
     */
    public function handle(Request $request, Closure $next, string $limiter = 'api') : Response{
        $response = $next($request);

        // Get limit from rate limiter configuration
        $limitCallback = RateLimiter::limiter($limiter);
        
        if(!$limitCallback){
            return $response;
        }

        $limitResult = $limitCallback($request);
        
        if(!($limitResult instanceof \Illuminate\Cache\RateLimiting\Limit)){
            return $response;
        }

        // Determine identifier
        $identifier = $request->user() ? $request->user()->id : $request->ip();
        $key = "{$limiter}:{$identifier}";
        
        $maxAttempts = $limitResult->maxAttempts;
        $decaySeconds = $limitResult->decaySeconds ?? 60;
        $remaining = RateLimiter::remaining($key, $maxAttempts);
        $retryAfter = RateLimiter::availableIn($key);

        // Add headers
        return $response->withHeaders([
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => max(0, $remaining),
            'X-RateLimit-Reset' => $retryAfter > 0 
                ? now()->addSeconds($retryAfter)->getTimestamp() 
                : now()->addSeconds($decaySeconds)->getTimestamp(),
        ]);
    }
}
