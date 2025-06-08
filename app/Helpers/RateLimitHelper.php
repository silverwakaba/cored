<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\RateLimiter;

class RateLimitHelper{
    // Get internal header ip
    public static function internalHeaderIP(){
        return 'X-Internal-Request-IP';
    }

    // Get internal header token
    public static function internalHeaderToken(){
        return 'X-Internal-Request-Token';
    }

    // Get internal header type
    public static function internalHeaderType(){
        return 'X-Internal-Request-Type';
    }

    // Set default header properties
    public static function headerProperties(){
        return [
            self::internalHeaderIP()    => request()->ip(),
            self::internalHeaderToken() => password_hash('apiInternalToken', PASSWORD_DEFAULT),
            self::internalHeaderType()  => 'apiInternalRequest',
        ];
    }
}
