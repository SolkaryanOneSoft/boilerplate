<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomErrorException;
use App\Http\Requests\StoreRegularBannerRequest;
use App\Http\Requests\UpdateRegularBannerRequest;
use App\Http\Resources\RegularBannerResource;
use App\Models\RegularBanner;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class RegularBannerController extends Controller
{
    use ApiResponse;

    public function store(StoreRegularBannerRequest $request): JsonResponse
    {
        $dataRegularBanner = $request->validated();
        $images = $request->input('images');

        try {
            DB::beginTransaction();
            $regularBanner = RegularBanner::create($dataRegularBanner);
            if (!empty($images)) {
                $this->attachImages($regularBanner, $images);
            }
            DB::commit();
            return $this->response201([
                'message' => __('successMessage.create'),
                'data' => new RegularBannerResource($regularBanner)
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function show($page): JsonResponse
    {
        $regularBanner = RegularBanner::where('page', $page)->first();

        if (!$regularBanner) {
            return $this->response200(null);
        }

        return $this->response200(new RegularBannerResource($regularBanner));
    }

    public function update(UpdateRegularBannerRequest $request, $page): JsonResponse
    {
        $regularBanner = RegularBanner::where('page', $page)->first();

        if (!$regularBanner) {
            throw new CustomErrorException('not_found_message', 'errorMessage', Response::HTTP_NOT_FOUND);
        }

        $dataRegularBanner = $request->validated();
        $images = $request->input('images');

        try {
            DB::beginTransaction();
            $regularBanner->update($dataRegularBanner);
            if ($request->has('images')) {
                if (empty($images)) {
                    $regularBanner->images()->delete();
                } else {
                    $regularBanner->images()->delete();
                    $this->attachImages($regularBanner, $images);
                }
            }
            DB::commit();
            return $this->response201(['message' => __('successMessage.update')]);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function destroy($page): JsonResponse
    {
        $regularBanner = RegularBanner::where('page', $page)->first();

        if (!$regularBanner) {
            throw new CustomErrorException('not_found_message', 'errorMessage', Response::HTTP_NOT_FOUND);
        }

        $regularBanner->delete();
        return $this->response204();
    }

    public function attachImages(RegularBanner $regularBanner, $images): void
    {
        $regularBanner->images()->delete();
        $regularBanner->images()->createMany($images);
    }
}
