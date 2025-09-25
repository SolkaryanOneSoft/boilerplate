<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadFileRequest;
use App\Http\Requests\UploadImagesRequest;
use App\Jobs\OptimizeImageJob;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

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

        $originalName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $uploadedFile->getClientOriginalExtension();
        $fileName = $originalName . '.' . $extension;
        $counter = 1;

        while (Storage::disk('public')->exists('uploaded/' . $fileName)) {
            $fileName = $originalName . '_' . $counter . '.' . $extension;
            $counter++;
        }

        $path = $uploadedFile->storeAs('uploaded', $fileName, 'public');

        $relativePath = '/storage/' . str_replace('public/', '', $path);

        $mainType = explode('/', $uploadedFile->getMimeType())[0];

        if ($mainType === 'image') {
            OptimizeImageJob::dispatch($path);
        }

        $fileData = [
            'path'       => $relativePath,
            'file_type'  => $mainType,
            'name'       => $originalName,
        ];

        return $this->response200($fileData);
    }
}
