<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegularBannerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $isAdminApi = $request->is('admin/*');

        return [
            'id' => $this->id,
            'active' =>  $this->when($isAdminApi, $this->active),

            'title' => $this->when($this->title, $this->title),
            'title_am' => $this->when($this->title_am, $this->title_am),
            'title_en' => $this->when($this->title_en, $this->title_en),
            'title_ru' => $this->when($this->title_ru, $this->title_ru),

            'description' => $this->when($this->description, $this->description),
            'description_am' => $this->when($this->description_am, $this->description_am),
            'description_en' => $this->when($this->description_en, $this->description_en),
            'description_ru' => $this->when($this->description_ru, $this->description_ru),

            'page' => $this->page,

            'images' => RegularBannerImageResource::collection($this->images),

            'created_at' => $this->when($isAdminApi, $this->created_at),
            'updated_at' => $this->when($isAdminApi, $this->updated_at),
        ];
    }
}
