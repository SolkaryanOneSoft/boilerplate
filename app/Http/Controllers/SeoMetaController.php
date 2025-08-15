<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomErrorException;
use App\Http\Requests\IndexRequest;
use App\Http\Requests\StoreSeoMetaRequest;
use App\Http\Requests\UpdateSeoMetaRequest;
use App\Models\SeoMeta;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class SeoMetaController extends Controller
{
    use ApiResponse;

    public function index(IndexRequest $request): JsonResponse
    {
        $seoMetas = SeoMeta::query();
        $offset = $request->input('offset');
        $limit = $request->input('limit');

        $total = $seoMetas->count();

        if (isset($offset) && isset($limit)) {
            $seoMetas->offset($offset)->limit($limit);
        }

        $seoMetas = $seoMetas->get();

        return $this->response200([
            'total' => $total,
            'seo_metas' => $seoMetas
        ]);
    }

    public function store(StoreSeoMetaRequest $request): JsonResponse
    {
        $seoMeta = SeoMeta::create($request->validated());

        return $this->response201([
            'message' => __('successMessage.create'),
            'data' => $seoMeta
        ]);
    }

    public function show($page): JsonResponse
    {
        $seoMeta = SeoMeta::where('page', $page)->first();

        if (!$seoMeta) {
            return $this->response200(null);
        }

        return $this->response200($seoMeta);
    }

    public function update(UpdateSeoMetaRequest $request, $page): JsonResponse
    {
        $seoMeta = SeoMeta::where('page', $page)->first();

        if (!$seoMeta) {
            throw new CustomErrorException('not_found_message', 'errorMessage', Response::HTTP_NOT_FOUND);
        }

        $seoMeta->update($request->validated());
        return $this->response201(['message' => __('successMessage.update')]);
    }

    public function destroy($page): JsonResponse
    {
        $seoMeta = SeoMeta::where('page', $page)->first();

        if (!$seoMeta) {
            throw new CustomErrorException('not_found_message', 'errorMessage', Response::HTTP_NOT_FOUND);
        }

        $seoMeta->delete();
        return $this->response204();
    }
}
