<?php

namespace App\Http\Requests\Core;

use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest{
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
            'name_master'          => ['required', 'string'],
            'description_master'   => ['required', 'string'],
            'details'              => ['required', 'array', 'min:1'],
            'details.*.name'       => ['required', 'string'],
            'details.*.description' => ['required', 'string'],
        ];
    }
}

