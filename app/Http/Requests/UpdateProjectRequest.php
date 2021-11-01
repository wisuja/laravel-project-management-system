<?php

namespace App\Http\Requests;

use App\Rules\ValidDuration;
use Illuminate\Foundation\Http\FormRequest;

use function PHPSTORM_META\map;

class UpdateProjectRequest extends FormRequest
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
            'id' => 'required',
            'is_starred' => 'nullable|boolean',
            'name' => 'nullable|string',
            'duration' => ['nullable', new ValidDuration]
        ];
    }
}
