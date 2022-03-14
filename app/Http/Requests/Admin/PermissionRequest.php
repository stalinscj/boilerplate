<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'       => ['required', 'string', 'max:45', Rule::unique('permissions')->ignore($this->permission)],
            'route_name' => ['required', 'string', 'max:45', Rule::unique('permissions')->ignore($this->permission)],
            'level'      => "required|integer|min:{$this->user()->level}",
            'group'      => 'required|string|min:3|max:20',
        ];

        return $rules;
    }
}
