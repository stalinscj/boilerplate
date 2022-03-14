<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'  => ['required', 'string', 'alpha_dash', 'max:30', Rule::unique('roles')->ignore($this->role)],
            'level' => "required|integer|min:{$this->user()->level}",
        ];

        return $rules;
    }
}
