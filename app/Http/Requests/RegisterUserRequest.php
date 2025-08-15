<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterUserRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'email', Rule::unique('users', 'email')->where(function ($query) {
                    $query->whereNotNull('email_verified_at');
                }),
            ],
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
