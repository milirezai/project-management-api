<?php

namespace App\Http\Requests\Api\V1\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
                'roles' => 'required|array',
                'roles.*' => 'exists:roles,id'
            ];
        }else{
            return [
                'mobile' => ['nullable','min:11','max:11',Rule::unique('users','mobile')->ignore($this->route('user')->id)],
                'first_name' => 'nullable|min:3|max:30',
                'last_name' => 'nullable|min:3|max:30',
                'profile_photo_path' => 'nullable|image|extensions:png,jpg,jpeg'
            ];
        }
    }
}
