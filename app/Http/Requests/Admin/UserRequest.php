<?php

namespace App\Http\Requests\Admin;

use App\Models\Role;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'     => 'required|string|max:60',
            'email'    => ['required', 'string', 'email:filter', 'max:100', Rule::unique('users')->ignore($this->user)],
            'password' => 'required|string|min:8|confirmed',
            'roles'    => "sometimes|array",
            'roles.*'  => "int|in:{$this->getAllowedRoles()}",
        ];

        if ($this->isMethod('PUT')) {
            $rules['password'] = 'exclude_unless:update_password,true|' . $rules['password'];
        }

        return $rules;
    }

    /**
     * Get the allowed roles name for the authenticaed user
     *
     * @return string
     */
    private function getAllowedRoles()
    {
        return Role::where('level', '>=', auth()->user()->level)
            ->pluck('id')
            ->implode(',');
    }

    /**
     * Get the validated data from the request.
     *
     * @param  string|null  $key
     * @param  mixed  $default
     *
     * @return mixed
     */
    public function validated($key = null, $default = null)
    {
        $validatedData = parent::validated($key, $default);

        if (isset($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

        return $validatedData;
    }
}
