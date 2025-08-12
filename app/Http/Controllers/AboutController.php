<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAboutRequest;
use App\Models\About;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class AboutController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        return $this->response200(About::first());
    }

    public function store(StoreAboutRequest $request): JsonResponse
    {
        $about = About::singleton();
        $about->fill($request->validated());
        $about->save();

        return $this->response201([
            'message' => __('successMessage.create'),
            'data' => $about
        ]);
    }

}
