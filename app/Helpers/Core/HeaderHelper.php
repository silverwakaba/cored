<?php

namespace App\Helpers\Core;

// Helper
use App\Helpers\Core\CookiesHelper;
use App\Helpers\Core\RateLimitHelper;

class HeaderHelper{
    // Exclude column from selection
    public static function apiHeader(){
        // Merge RateLimitHelper and CookiesHelper
        return array_merge(
            RateLimitHelper::headerProperties(), CookiesHelper::headerProperties(),
        );
    }
}
