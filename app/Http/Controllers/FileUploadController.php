<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadFileRequest;
use App\Http\Requests\UploadImagesRequest;
use App\Jobs\OptimizeImageJob;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class FileUploadController extends Controller
{
    use ApiResponse;

    public function uploadImages(UploadImagesRequest $request): JsonResponse
    {
        $uploadedPaths = [];

        foreach ($request->file('images') as $image) {
            $path = $image->store('images', 'public');

            OptimizeImageJob::dispatch($path);

            $relativePath = '/storage/' . str_replace('public/', '', $path);
            $mainType = explode('/', $image->getMimeType())[0];

            $uploadedPaths[] = ['path' => $relativePath, 'file_type' => $mainType];
        }

        return $this->response200($uploadedPaths);
    }

    public function uploadFile(UploadFileRequest $request): JsonResponse
    {
        $uploadedFile = $request->file('file');
        $path = $uploadedFile->store('uploaded', 'public');

        if (str_starts_with($uploadedFile->getMimeType(), 'image/')) {
            OptimizeImageJob::dispatch($path);
        }

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
