<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAboutRequest extends FormRequest
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
            'title_am' => ['nullable', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'title_ru' => ['nullable', 'string', 'max:255'],

            'description_am' => ['nullable', 'string', 'max:10000'],
            'description_en' => ['nullable', 'string', 'max:10000'],
            'description_ru' => ['nullable', 'string', 'max:10000'],

            'image' => ['nullable', 'array'],
            'image.path' => ['required_with:image.file_type', 'string', 'max:10000'],
            'image.file_type' => ['required_with:image.path', 'in:image,video'],
        ];
    }
}
