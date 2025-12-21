<?php

namespace App\Helpers\Core;

use Illuminate\Support\Str;

class ErrorHelper{
    // Throw default error for API
    public static function apiErrorResult($data = null){
        if((isset($data)) && ($data != null)){
            return response()->json([
                'success'   => false,
                'message'   => Str::of($data),
            ], 409);
        }

        return response()->json([
            'success'   => false,
            'message'   => "Something unexpected happened.",
        ], 409);
    }

    // Throw default 403 forbidden for API
    public static function apiError403Result(){
        return response()->json([
            'success'   => false,
            'message'   => "Forbidden action.",
        ], 403);
    }

    // Throw default 404 not found for API
    public static function apiError404Result(){
        return response()->json([
            'success'   => false,
            'message'   => "Data not found.",
        ], 404);
    }

    // Throw default rate-limiter for API
    public static function apiErrorLimiterResult(array $headers = []){
        $defaultHeaders = [
            'X-RateLimit-Limit' => $headers['X-RateLimit-Limit'] ?? 60,
            'X-RateLimit-Remaining' => $headers['X-RateLimit-Remaining'] ?? 0,
            'Retry-After' => $headers['Retry-After'] ?? 60,
        ];

        return response()->json([
            'success'   => false,
            'message'   => "You reached request limit. Please try again later.",
        ], 429)->withHeaders(array_merge($defaultHeaders, $headers));
    }
}
