<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexRequest;
use App\Http\Requests\StoreFaqRequest;
use App\Http\Requests\UpdateFaqRequest;
use App\Http\Resources\FaqResource;
use App\Models\Faq;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class FaqController extends Controller
{
    use ApiResponse;

    public function index(IndexRequest $request): JsonResponse
    {
        $faqs = Faq::query();
        $offset = $request->input('offset');
        $limit = $request->input('limit');

        $total = $faqs->count();

        if (isset($offset) && isset($limit)) {
            $faqs->offset($offset)->limit($limit);
        }

        $faqs = $faqs->get();

        return $this->response200([
            'total' => $total,
            'faqs' => FaqResource::collection($faqs)
        ]);
    }

    public function store(StoreFaqRequest $request): JsonResponse
    {
        $faq = Faq::create($request->validated());

        return $this->response201([
            'message' => __('successMessage.create'),
            'data' => $faq
        ]);
    }

    public function show(Faq $faq): JsonResponse
    {
        return $this->response200(new FaqResource($faq));
    }

    public function update(UpdateFaqRequest $request, Faq $faq): JsonResponse
    {
        $faq->update($request->validated());
        return $this->response201(['message' => __('successMessage.update')]);
    }

    public function destroy(Faq $faq): JsonResponse
    {
        $faq->delete();
        return $this->response204();
    }
}
