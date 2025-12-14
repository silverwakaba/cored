<?php

namespace App\Helpers\Core;

// Helper
use App\Helpers\Core\ErrorHelper;

// Internal
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

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

            // Convert object into array if type is object
            case 'object': $data = collect($datas)->all(); break;
            
            // Explode string into array if type is string
            case 'string': $data = (array) explode(',', $datas); break;
            
            // Default is convert as array but it need to be idenfied later
			default: $data = (array) $datas;
        }

        // Return the result
        return $data;
    }

    // Execute callback with unified try-catch response
    public static function safe(callable $callback, array $onError = ['status' => 409, 'message' => false]){
        try{
            // Callback content
            return $callback();
        }
        catch(\Throwable $th){
            // Error payload
            $payload = $onError;
            $payload['message'] = ($payload['message'] === true) ? $th->getMessage() : null;

            // Return response
            return self::jsonResponse($payload);
        }
    }

    // Validate data and return unified response when it fails
    public static function validate(array $data, array $rules, array $messages = [], array $customAttributes = [], int $status = 422){
        $validator = Validator::make($data, $rules, $messages, $customAttributes);

        if($validator->fails()){
            return self::jsonResponse([
                'status' => $status,
                'errors' => $validator->errors(),
            ]);
        }

        return $validator->validated();
    }

    // Json response
    public static function jsonResponse($datas){
        // Determine success status based on status
        $is_success = in_array($datas['status'], [200, 201, 202, 204, 206]);

        // Set custom message based on status
        switch($datas['status']){
            // 403 - Forbidden
            case 403 : $message = 'Forbidden action.'; break;

            // 404 - Not found
            case 404 : $message = 'Data not found.'; break;

            // 409 - Conflict
            case 409 : $message = 'Conflicted request. Please try again.'; break;

            // 422 - Unprocessable Content
            case 422 : $message = 'Unprocessable request.'; break;

            // 429 - Rate limit
            case 429 : $message = 'You have reached request limit.'; break;

            // Default
            default: $message = 'Something unexpected happened. You can try again.';
        }
        
        // Plain array response
        $response = [
            'success'   => $is_success,
            'errors'    => isset($datas['errors']) ? $datas['errors'] : null,
            'eligible'  => isset($datas['eligible']) ? $datas['eligible'] : null,
            'data'      => isset($datas['data']) ? $datas['data'] : null,
            'message'   => (!isset($datas['message']) && ($is_success == false)) ? $message : (isset($datas['message']) ? Str::of($datas['message']) : null),
        ];

        // Remove null values
        $response = array_filter($response, function ($value){
            return !is_null($value);
        });

        return response()->json($response, $datas['status']);
    }
}
