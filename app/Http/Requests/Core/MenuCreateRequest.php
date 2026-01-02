<?php

namespace App\Http\Requests\Core;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'name'              => ['required', 'string', 'max:255'],
            'icon'              => ['nullable', 'string', 'max:255'],
            'route'             => ['nullable', 'string', 'max:255'],
            'type'              => ['required', 'string', Rule::in(['h', 'p', 'c'])],
            'parent_id'         => ['nullable', 'integer', 'exists:menus,id'],
            'is_authenticate'  => ['nullable', 'boolean'],
            'is_guest_only'     => ['nullable', 'boolean'],
            'position'           => ['nullable', 'string', Rule::in(['before', 'after'])],
            'reference_id'      => ['nullable', 'integer', 'exists:menus,id'],
        ];
    }
}


