<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRegularBannerRequest extends FormRequest
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
            'active' => ['nullable', 'boolean'],
            'title_am' => ['sometimes', 'string', 'max:255'],
            'description_am' => ['nullable', 'string', 'max:10000'],

            'title_en' => ['nullable', 'string', 'max:255'],
            'description_en' => ['nullable', 'string', 'max:10000'],

            'title_ru' => ['nullable', 'string', 'max:255'],
            'description_ru' => ['nullable', 'string', 'max:10000'],

            'images' => ['nullable', 'array'],
            'images.*.path' => ['required_with:images.*.file_type', 'string', 'max:10000'],
            'images.*.file_type' => ['required_with:images.*.path', 'in:image,video'],
        ];
    }
}
