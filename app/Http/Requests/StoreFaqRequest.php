<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFaqRequest extends FormRequest
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
            'sort_order' => ['nullable', 'integer'],

            'question_am' => ['required', 'string', 'max:10000'],
            'question_en' => ['nullable', 'string', 'max:10000'],
            'question_ru' => ['nullable', 'string', 'max:10000'],

            'answer_am' => ['required', 'string', 'max:10000'],
            'answer_en' => ['nullable', 'string', 'max:10000'],
            'answer_ru' => ['nullable', 'string', 'max:10000'],
        ];
    }
}
