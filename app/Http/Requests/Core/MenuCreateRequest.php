<?php

namespace App\Http\Requests\Core;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;

class MenuCreateRequest extends FormRequest{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize() : bool{
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules() : array{
        return [
            'name'              => ['required', 'string'],
            'icon'              => ['nullable', 'string'],
            'route'             => ['nullable', 'string',
                function($attribute, $value, $fail){
                    if(!empty($value) && !Route::has($value)){
                        $fail('The route name is invalid.');
                    }
                }
            ],
            'type'              => ['required', 'string', Rule::in(['h', 'p', 'c'])],
            'parent'            => ['nullable', 'integer', 'exists:menus,id'],
            'authenticate'      => ['nullable', 'boolean'],
            'guest_only'        => ['nullable', 'boolean'],
            'position'          => ['nullable', 'string', Rule::in(['before', 'after'])],
            'reference_id'      => ['nullable', 'integer', 'exists:menus,id'],
        ];
    }
}
