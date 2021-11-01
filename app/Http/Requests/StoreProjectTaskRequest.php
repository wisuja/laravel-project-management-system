<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'task_type_id' => 'required|exists:task_types,id',
            'title' => 'required',
            'description' => 'nullable',
            'assigned_to' => 'required|array',
            'assigned_to.*' => 'required|exists:project_members,user_id',
            'label' => 'required',
            'deadline' => 'required|date'
        ];
    }
}
