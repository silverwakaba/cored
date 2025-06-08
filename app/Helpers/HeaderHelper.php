<?php

namespace App\Helpers;

// Helper
use App\Helpers\CookiesHelper;
use App\Helpers\RateLimitHelper;

class HeaderHelper{
    // Exclude column from selection
    public static function apiHeader(){
        // Merge RateLimitHelper and CookiesHelper
        return array_merge(
            RateLimitHelper::headerProperties(), CookiesHelper::headerProperties(),
        );
    }
}
