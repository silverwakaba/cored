<?php

namespace App\Helpers;

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

    // Throw default 404 not found for API
    public static function apiError404Result(){
        return response()->json([
            'success'   => false,
            'message'   => "Data not found.",
        ], 404);
    }

    // Throw default rate-limiter for API
    public static function apiErrorLimiterResult(){
        return response()->json([
            'success'   => false,
            'message'   => "You reached request limit.",
        ], 429);
    }
}
