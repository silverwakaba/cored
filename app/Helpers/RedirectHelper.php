<?php

namespace App\Helpers;

class RedirectHelper{
    // Merge response
    public static function merge($data, $route){
        return array_merge($data, ['redirect' => $route]);
    }
}
