<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupplierRequest extends FormRequest{
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
            'users_id'                  => ['nullable', 'string', 'exists:users,id'],
            'base_qualification_id'     => ['required', 'integer', 'exists:base_requests,id'],
            'base_business_entity_id'   => ['required', 'integer', 'exists:base_requests,id'],
            'base_bank_id'              => ['nullable', 'integer', 'exists:base_requests,id'],
            
            // User data (required if users_id is not provided)
            'pic_name'                  => ['required:users_id', 'string', 'max:255'],
            'pic_email'                 => ['required:users_id', 'email', 'unique:users,email'],
            
            // Basic information
            'code'                      => ['required', 'string', 'max:255'],
            'name'                      => ['required', 'string', 'max:255'],
            'credit_day'                => ['required', 'integer', 'min:0'],
            
            // Address
            'address_1'                 => ['nullable', 'string'],
            'address_2'                 => ['nullable', 'string'],
            
            // Contact
            'telp'                      => ['nullable', 'string', 'max:255'],
            'fax'                       => ['nullable', 'string', 'max:255'],
            
            // Tax information
            'npwp'                      => ['nullable', 'string', 'max:255'],
            'npwp_address'              => ['nullable', 'string'],
            
            // Bank information
            'bank_account_name'         => ['nullable', 'string', 'max:255'],
            'bank_account_number'       => ['nullable', 'string', 'max:255'],
            
            // Additional information
            'pkp'                       => ['nullable', 'string', 'max:255'],
            'nib'                       => ['nullable', 'string', 'max:255'],
            'notes'                     => ['nullable', 'string', 'max:255'],
            'statement_file_path'       => ['nullable', 'string', 'max:255'],
        ];
    }
}
