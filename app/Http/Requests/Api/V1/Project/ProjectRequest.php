<?php

namespace App\Http\Requests\Api\V1\Project;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
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
                'name' => ['nullable',Rule::unique('projects','name')->ignore($this->route('project')->id)],
                'description' => 'nullable|max:400',
                'start_date' => 'nullable|date',
                'end_date' =>   'nullable|date',
                'status' => 'nullable|boolean'
            ];
        }
        else{
            return [
                'name' => 'required|unique:projects,name|max:100',
                'description' => 'nullable|max:400',
                'start_date' => 'required|date',
                'end_date' =>   'required|date',
                'status' => 'nullable|boolean'
            ];
        }
    }
}
