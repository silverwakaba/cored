<?php

namespace App\Http\Middleware\Core;

use App\Helpers\Core\RateLimitHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to bypass rate limiting for internal API requests
 * 
 * Best Practice: Use this middleware before throttle middleware
 * to bypass rate limiting for internal requests
 */
class BypassRateLimitMiddleware{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next) : Response{
        // Bypass rate limiting for internal API request
        if($this->isInternalRequest($request)){
            // Set flag to skip throttle middleware
            $request->attributes->set('_skip_rate_limit', true);
        }

        return $next($request);
    }

    /**
     * Check if request is an internal request
     */
    protected function isInternalRequest(Request $request): bool{
        return (
            $request->header(RateLimitHelper::internalHeaderIP()) == '127.0.0.1'
            && password_verify('apiInternalToken', $request->header(RateLimitHelper::internalHeaderToken()) ?? '')
            && $request->header(RateLimitHelper::internalHeaderType()) == 'apiInternalRequest'
        );
    }
}
