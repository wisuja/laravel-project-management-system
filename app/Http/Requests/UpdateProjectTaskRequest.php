<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectTaskRequest extends FormRequest
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
            'page' => ['nullable',
                        function ($attribute, $value, $fail) {
                            if (!in_array($value, ['backlog', 'boards']))
                                $fail('This ' . $attribute . ' is not valid');
                        }],
            'type' => ['nullable',
                        function ($attribute, $value, $fail) {
                            if (!in_array($value, ['sprint', 'backlog']))
                                $fail('This ' . $attribute . ' is not valid');
                        }],
            'status_group' => 'nullable',
            'order' => 'nullable|array',
            'task_type_id' => 'nullable|exists:task_types,id',
            'title' => 'nullable',
            'description' => 'nullable',
            'assigned_to' => 'nullable|array',
            'assigned_to.*' => 'nullable|exists:project_members,user_id',
            'label' => 'nullable',
            'deadline' => 'nullable|date'
        ];
    }
}
