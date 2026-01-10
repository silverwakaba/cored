<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupplierCreateRequest extends FormRequest{
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
            // Foreign keys
            'qualification'     => ['required', 'integer', 'exists:base_requests,id'],
            'business_entity'   => ['required', 'integer', 'exists:base_requests,id'],
            
            // User data
            'pic_name'          => ['required:users_id', 'string', 'max:255'],
            'pic_email'         => ['required:users_id', 'email', 'unique:users,email'],
            
            // Basic information
            'code'              => ['required', 'string', 'max:255', 'unique:supplier,code'],
            'name'              => ['required', 'string', 'max:255'],
            'credit_day'        => ['required', 'integer', 'min:0'],
        ];
    }
}
