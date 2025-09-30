<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadFileRequest;
use App\Http\Requests\UploadImagesRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;

class FileUploadController extends Controller
{
    use ApiResponse;

    public function uploadImages(UploadImagesRequest $request): JsonResponse
    {
        $uploadedPaths = [];
        $manager = ImageManager::gd();

        foreach ($request->file('images') as $file) {
            $mainType = explode('/', $file->getClientMimeType())[0];
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();

            if ($mainType === 'image') {
                $originalFileName = uniqid() . '.' . $extension;
                $webpName = pathinfo($originalFileName, PATHINFO_FILENAME) . '.webp';
                $webpPath = 'images/' . $webpName;

                /** @var ImageInterface $img */
                $img = $manager->read($file->getRealPath())
                    ->toWebp(80);

                $img->save(storage_path('app/public/' . $webpPath));
                $relativePath = '/storage/' . $webpPath;

                $uploadedPaths[] = [
                    'path' => $relativePath,
                    'file_type' => $mainType,
                ];
            } elseif ($mainType === 'video') {
                $videoName = uniqid() . '.' . $extension;
                $videoPath = 'videos/' . $videoName;
                $file->storeAs('videos', $videoName, 'public');

                $uploadedPaths[] = [
                    'path' => '/storage/' . $videoPath,
                    'file_type' => $mainType,
                ];
            } else {
                $fileName = $originalName . '.' . $extension;
                $counter = 1;
                while (Storage::disk('public')->exists('uploaded/' . $fileName)) {
                    $fileName = $originalName . '_' . $counter . '.' . $extension;
                    $counter++;
                }
                $path = $file->storeAs('uploaded', $fileName, 'public');

                $uploadedPaths[] = [
                    'path' => '/storage/' . str_replace('public/', '', $path),
                    'file_type' => $mainType,
                ];
            }
        }

        return $this->response200($uploadedPaths);
    }

    public function uploadFile(UploadFileRequest $request): JsonResponse
    {
        $file = $request->file('file');
        $mainType = explode('/', $file->getClientMimeType())[0];
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $manager = ImageManager::gd();

        if ($mainType === 'image') {
            $originalFileName = uniqid() . '.' . $extension;
            $webpName = pathinfo($originalFileName, PATHINFO_FILENAME) . '.webp';
            $webpPath = 'images/' . $webpName;

            /** @var ImageInterface $img */
            $img = $manager->read($file->getRealPath())
                ->toWebp(80);

            $img->save(storage_path('app/public/' . $webpPath));
            $relativePath = '/storage/' . $webpPath;

            $fileData = [
                'path' => $relativePath,
                'file_type' => $mainType,
                'name' => $originalName,
            ];
        } elseif ($mainType === 'video') {
            $videoName = uniqid() . '.' . $extension;
            $videoPath = 'videos/' . $videoName;
            $file->storeAs('videos', $videoName, 'public');

            $fileData = [
                'path' => '/storage/' . $videoPath,
                'file_type' => $mainType,
                'name' => $originalName,
            ];
        } else {
            $fileName = $originalName . '.' . $extension;
            $counter = 1;
            while (Storage::disk('public')->exists('uploaded/' . $fileName)) {
                $fileName = $originalName . '_' . $counter . '.' . $extension;
                $counter++;
            }
            $path = $file->storeAs('uploaded', $fileName, 'public');

            $fileData = [
                'path' => '/storage/' . str_replace('public/', '', $path),
                'file_type' => $mainType,
                'name' => $originalName,
            ];
        }

        return $this->response200($fileData);
    }
}
