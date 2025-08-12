<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadFileRequest;
use App\Http\Requests\UploadImagesRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class FileUploadController extends Controller
{
    use ApiResponse;
    public function uploadImages(UploadImagesRequest $request): JsonResponse
    {
        $uploadedPaths = [];

        foreach ($request->file('images') as $image) {
            $url = $this->storeAndRetrieveUrl($image);
            $uploadedPaths[] = $url;
        }

        return $this->response200($uploadedPaths);
    }

    public function storeAndRetrieveUrl($file): array
    {
        $path = $file->store('images', 'public');

        $relativePath = '/storage/' . str_replace('public/', '', $path);

        $mainType = explode('/', $file->getMimeType())[0];

        return ['path' => $relativePath, 'file_type' => $mainType];
    }

    public function uploadFile(UploadFileRequest $request): JsonResponse
    {
        $uploadedFile = $request->file('file');

        $path = $uploadedFile->store('uploaded', 'public');
        $relativePath = '/storage/' . str_replace('public/', '', $path);
        $mainType = explode('/', $uploadedFile->getMimeType())[0];
        $originalName = $uploadedFile->getClientOriginalName();
        $nameWithoutExtension = pathinfo($originalName, PATHINFO_FILENAME);

        $file = [
            'path' => $relativePath,
            'file_type' => $mainType,
            'name' => $nameWithoutExtension
        ];

        return $this->response200($file);
    }
}
