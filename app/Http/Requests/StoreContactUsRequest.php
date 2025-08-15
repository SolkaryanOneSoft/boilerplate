<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactUsRequest extends FormRequest
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
            'address_am' => ['required', 'string', 'max:255'],
            'address_en' => ['nullable', 'string', 'max:255'],
            'address_ru' => ['nullable', 'string', 'max:255'],

            'email' => ['required', 'email'],
            'phones' => ['required', 'array'],
            'phones.*' => ['required', 'string', 'max:15'],
        ];
    }
}
