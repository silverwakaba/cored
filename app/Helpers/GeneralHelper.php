<?php

namespace App\Helpers;

// Helper
use App\Helpers\ErrorHelper;

// Internal
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class GeneralHelper{
    // Create random token
    public static function randomToken($length = 8){
        // List of possible data as array
        $array = [
            'ulid'  => (string) Str::ulid(),
            'uuid4' => (string) Str::uuid(),
            'uuid7' => (string) Str::uuid7(),
        ];

        // Shuffle the array
        $arrayShuffle = Arr::shuffle($array);

        // Join the array as string
        $arrayJoin = Arr::join($arrayShuffle, '');

        // Remove the hyphen while uppercasing the string
        $string = Str::of($arrayJoin)->remove('-')->upper();

        // List of possible hash method as array
        $method = [
            'sha256', 'ripemd256', 'snefru',
        ];

        // Shuffle the method
        $methodShuffle = Arr::shuffle($method);

        // Hashed string
        $hash = Str::of(hash($methodShuffle[0], $string))->substr(0, $length);

        // Return the result
        return $hash;
    }

    // Create random password from plain text
    public static function randomPassword($length = 64){
        $password = Str::password($length);

        // Return the result
        return $password;
    }

    // Get data type and convert it to array
    public static function getType($datas){
        // Check data type
        $type = gettype($datas);

        // Handle type
        switch($type){
            // Return array if type is array
            case 'array': $data = (array) $datas; break;
            
            // Explode string into array if type is string
            case 'string': $data = (array) explode(',', $datas); break;
            
            // Default is null
            default: $data = (array) $datas;
        }

        // Return the result
        return $data;
    }
}
