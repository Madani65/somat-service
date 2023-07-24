<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LessonCategoryResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "idClassCategory" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
            "activeFlag" => $this->active_flag
        ];
    }
}
