<?php

namespace App\Http\Requests\Api\V1\Collaboration;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->method() == 'PUT' || $this->method() == 'PATCH'){
            return [
                'name' => ['nullable',Rule::unique('companies','name')->ignore($this->route('company')->id)],
                'description' => 'nullable|max:400',
                'address' => 'nullable|max:50|min:5',
                'phone_number' => ['required',Rule::unique('companies','phone_number')->ignore($this->route('company')->id)],
                'email' => ['nullable','email',Rule::unique('companies','email')->ignore($this->route('company')->id)],
                'website' => 'nullable|url',
                'type' => 'nullable|max:100'
            ];
        }
        else{
            return [
                'name' => 'required|max:40|min:5',
                'description' => 'nullable|max:400',
                'address' => 'required|max:50|min:5',
                'phone_number' => 'required|unique:companies,phone_number|numeric',
                'email' => 'nullable|email|unique:companies,email',
                'website' => 'nullable|url',
                'type' => 'nullable|max:100'
            ];
        }
    }
}
