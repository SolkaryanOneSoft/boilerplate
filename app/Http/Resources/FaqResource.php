<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FaqResource extends JsonResource
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
            'sort_number' => $this->sort_number,

            'question' => $this->when($this->question, $this->question),
            'question_am' => $this->when($this->question_am, $this->question_am),
            'question_en' => $this->when($this->question_en, $this->question_en),
            'question_ru' => $this->when($this->question_ru, $this->question_ru),

            'answer' => $this->when($this->answer, $this->answer),
            'answer_am' => $this->when($this->answer_am, $this->answer_am),
            'answer_en' => $this->when($this->answer_en, $this->answer_en),
            'answer_ru' => $this->when($this->answer_ru, $this->answer_ru),

            'created_at' => $this->when($isAdminApi, $this->created_at),
            'updated_at' => $this->when($isAdminApi, $this->updated_at),
        ];
    }
}
