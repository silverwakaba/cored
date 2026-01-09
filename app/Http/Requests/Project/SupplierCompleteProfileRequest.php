<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupplierCompleteProfileRequest extends FormRequest{
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
            'qualification'             => ['required', 'integer', 'exists:base_requests,id'],
            'business_entity'           => ['required', 'integer', 'exists:base_requests,id'],
            'bank'                      => ['required', 'integer', 'exists:base_requests,id'],
            
            // Basic information
            'name'                      => ['required', 'string', 'max:255'],
            
            // Address information
            'address_1'                 => ['required', 'string'],
            'address_2'                 => ['required', 'string'],
            
            // Contact information
            'telp'                      => ['required', 'string', 'max:255'],
            'fax'                       => ['required', 'string', 'max:255'],
            
            // Tax information
            'npwp'                      => ['required', 'string', 'max:255'],
            'npwp_address'              => ['required', 'string'],
            
            // Bank account information
            'bank_account_name'         => ['required', 'string', 'max:255'],
            'bank_account_number'       => ['required', 'string', 'max:255'],
            
            // Additional information
            'pkp'                       => ['required', 'string', 'max:255'],
            'nib'                       => ['required', 'string', 'max:255'],
            'notes'                     => ['required', 'string'],
            'statement'                 => ['required', 'file', 'min:100', 'max:1024'], // 'mimes:pdf',

            // PIC-related
            'pic_name'                  => ['required', 'string', 'max:255'],
            'pic_password'              => ['required', 'string', 'min:8', 'confirmed'],
            'pic_password_confirmation' => ['required', 'string', 'same:pic_password'],
            'agreement'                 => ['accepted', 'boolean'],
        ];
    }
}
