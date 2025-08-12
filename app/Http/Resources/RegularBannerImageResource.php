<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RegularBannerImageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'path' => $this->path,
            'file_type' => $this->file_type,
        ];
    }
}

