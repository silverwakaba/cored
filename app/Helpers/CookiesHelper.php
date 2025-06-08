<?php

namespace App\Helpers;

// Emulating external user storing their token inside database
use App\Models\User;

// Internal
use Illuminate\Support\Str;

class CookiesHelper{
    // Get header value
    public static function header(){
        return request()->header(self::headerName());
    }

    // Get header name
    public static function headerName(){
        return 'X-Internal-Request-Localization';
    }

    // Set header properties
    public static function headerProperties(){
        return [
            self::headerName() => self::appLocale(),
        ];
    }

    // App locale
    public static function appLocale($data = null){
        // Get locale cookie
        $cookie = request()->cookie('locale');

        // Parse cookie if present, set to other if not present
        $locale = isset($cookie) ? $cookie : 'other';

        // Check if locale cookie is within the scope
        $localeChecker = Str::contains($locale, ['en', 'id']);

        // If not within scope we're using the "en" locale
        if($localeChecker == false){
            return 'en';
        }

        // Either way we're using the locale cookie
        return $locale;
    }

    // JWT token
    public static function jwtToken(){
        if((self::jwtRemember() == false)){
            return request()->cookie('jwt_token');
        }

        return User::find(self::jwtUserID())->only('token')['token'];
    }

    // JWT TTL (Time to Live)
    public static function jwtTTL(){
        if((self::jwtRemember() == false)){
            return request()->cookie('jwt_ttl');
        }

        return User::find(self::jwtUserID())->only('token_expire_at')['token_expire_at'];
    }

    // JWT remember session
    public static function jwtRemember(){
        $data = request()->cookie('jwt_remember') ? request()->cookie('jwt_remember') : false;

        return $data;
    }

    // JWT user id
    public static function jwtUserID(){
        $data = request()->cookie('jwt_user_id') ? request()->cookie('jwt_user_id') : false;

        return $data;
    }

    // Darkmode
    // By default we're using light mode
    public static function darkmode(){
        $data = request()->cookie('darkmode') ? true : false;

        return $data;
    }

    // Darkmode for body
    public static function darkmodeBody(){
        $data = (self::darkmode() == true) ? 'dark-mode' : null;

        return $data;
    }

    // Darkmode for header navigation
    public static function darkmodeNavigation(){
        $data = (self::darkmode() == true) ? 'navbar-dark' : 'navbar-light navbar-white';

        return $data;
    }

    // Darkmode for sidebar
    public static function darkmodeSidebar(){
        $data = (self::darkmode() == true) ? 'sidebar-dark-primary' : 'sidebar-light-primary';

        return $data;
    }

    // Darkmode icon
    public static function darkmodeIcon(){
        $data = (self::darkmode() == true) ? 'fas fa-sun' : 'fas fa-moon';

        return $data;
    }

    // Auth box class
    public static function authBox(){
        switch(request()){
            case request()->routeIs('fe.auth.register'):
                return "register-box";
            default:
                return "login-box";
        }
    }

    // Auth page class
    public static function authPage(){
        switch(request()){
            case request()->routeIs('fe.auth.register'):
                return "register-page";
            default:
                return "login-page";
        }
    }
}
