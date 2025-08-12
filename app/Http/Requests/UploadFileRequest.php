<?php

namespace App\Http\Requests;

use App\Enums\FileExtensionsEnum;
use Illuminate\Foundation\Http\FormRequest;

class UploadFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:'.implode(',', FileExtensionsEnum::ALLOWED_UPLOAD_FILE)],
        ];
    }
}
