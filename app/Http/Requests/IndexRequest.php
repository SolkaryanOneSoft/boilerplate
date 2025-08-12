<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
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
            'offset' => ['nullable', 'required_with:limit', 'integer', 'min:0'],
            'limit' => ['nullable', 'required_with:offset', 'integer', 'max:30'],
        ];
    }
}
