<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupplierUpdateRequest extends FormRequest{
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
            'base_qualification_id'     => ['required', 'integer', 'exists:base_requests,id'],
            'base_business_entity_id'   => ['required', 'integer', 'exists:base_requests,id'],
            
            // Basic information
            'code'                      => ['required', 'string', 'max:255', Rule::unique('supplier', 'code')->ignore(request()->id)],
            'name'                      => ['required', 'string', 'max:255'],
            'credit_day'                => ['required', 'integer', 'min:0'],
        ];
    }
}
