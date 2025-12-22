<?php

namespace App\Http\Requests\Api\V1\Collaboration;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FileRequest extends FormRequest
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
                'type' => 'nullable|string',
                'status' => 'nullable|boolean',
            ];
        }
        else{
            return [
                'fileable_type' => 'required|in:task,project',
                'fileable_id' => 'required|numeric',
                'file' => 'required|file'
            ];
        }
    }
}
