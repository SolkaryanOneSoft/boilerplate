<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSeoMetaRequest extends FormRequest
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
            'page' => ['sometime', 'string', 'max:255', 'unique:seo_metas,page'],

            'title_am' => ['sometime', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'title_ru' => ['nullable', 'string', 'max:255'],

            'description_am' => ['sometime', 'string', 'max:10000'],
            'description_en' => ['nullable', 'string', 'max:10000'],
            'description_ru' => ['nullable', 'string', 'max:10000'],

            'keywords' => ['nullable', 'array'],
        ];
    }
}
