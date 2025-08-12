<?php

namespace App\Http\Requests;

use App\Enums\FileExtensionsEnum;
use Illuminate\Foundation\Http\FormRequest;

class UploadImagesRequest extends FormRequest
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
        $allowVideoHeader = $this->header('allow-video');

        $isAllowVideo = filter_var($allowVideoHeader, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        $allowedMimeTypes = FileExtensionsEnum::ALLOWED_UPLOAD_IMAGE;

        if ($isAllowVideo === true) {
            $allowedMimeTypes = FileExtensionsEnum::ALLOWED_UPLOAD_IMAGE_VIDEO;
        }

        return [
            'images.*' => ['required', 'file', 'mimes:'.implode(',', $allowedMimeTypes), 'max:500000'],
        ];
    }
}
