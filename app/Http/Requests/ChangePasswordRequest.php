<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ChangePasswordRequest extends FormRequest
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
            'current_password' => ['required'],
            'new_password' => [
                'required',
                'string',
                'confirmed',
                Password::min(6)
                    ->mixedCase()
                    ->numbers(),
                'different:current_password',
            ],
            'new_password_confirmation' => ['required', 'same:new_password'],
        ];
    }
}
