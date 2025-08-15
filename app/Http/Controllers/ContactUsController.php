<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactUsRequest;
use App\Models\ContactUs;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class ContactUsController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        return $this->response200(ContactUs::first());
    }

    public function store(StoreContactUsRequest $request): JsonResponse
    {
        $contactUs = ContactUs::singleton();
        $contactUs->fill($request->validated());
        $contactUs->save();

        return $this->response201([
            'message' => __('successMessage.create'),
            'data' => $contactUs,
        ]);
    }

}
