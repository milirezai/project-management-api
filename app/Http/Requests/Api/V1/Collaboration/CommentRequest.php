<?php

namespace App\Http\Requests\Api\V1\Collaboration;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CommentRequest extends FormRequest
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
                'body' => 'nullable|max:300|min:5',
                'status' => 'nullable|boolean',
            ];
        }
        else{
            return [
                'commentable_type' => 'required|in:task,project',
                'commentable_id' => 'required|numeric',
                'body' => 'required|max:300|min:5'
            ];
        }
    }
}
