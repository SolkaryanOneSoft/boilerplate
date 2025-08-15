<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            'role' => ['required', 'integer', 'in:1,2,3'],
            'email' => [
                'required',
                'string',
                'email',
                'unique:users,email'
            ],
            'name' => ['required', 'string', 'max:255'],
            'password' => [
                'required',
                Password::min(6)
                    ->mixedCase()
                    ->numbers(),
            ],
            'confirm_password' => ['required', 'same:password'],
        ];
    }
}
