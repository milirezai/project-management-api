<?php

namespace App\Http\Requests\Api\V1\Project;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
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
                'title' => 'nullable|max:100',
                'description' => 'nullable|max:400',
                'start_date' => 'nullable|date',
                'end_date' =>   'nullable|date',
                'project_id' => 'nullable|exists:projects,id',
                'user_id' =>  'nullable|exists:users,id',
                'status' => 'nullable|boolean'
            ];
        }
        else{
            return [
                'title' => 'required|max:100',
                'description' => 'nullable|max:400',
                'start_date' => 'required|date',
                'end_date' =>   'required|date',
                'project_id' => 'required|exists:projects,id',
                'user_id' =>  'required|exists:users,id',
                'status' => 'nullable|boolean'
            ];
        }
    }
}
