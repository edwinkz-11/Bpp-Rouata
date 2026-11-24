<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isCreate = $this->isMethod('post');

        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($this->route('user'))],
            'role' => ['required', Rule::in(['admin', 'editor'])],
            'password' => [$isCreate ? 'required' : 'nullable', 'confirmed', Password::min(10)->mixedCase()->numbers()],
        ];
    }

    public function attributes(): array
    {
        return ['name' => 'nama', 'email' => 'email', 'role' => 'peran', 'password' => 'kata sandi'];
    }
}
