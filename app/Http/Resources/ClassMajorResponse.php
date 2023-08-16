<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClassMajorResponse extends JsonResource
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
            "idClassMajor" => $this->id,
            "code" => $this->code,
            "name" => $this->name,
            "description" => $this->description,
            "activeFlag" => $this->active_flag,
            "schoolLevel" => new SchoolLevelResponse($this->schoolLevel)
        ];
    }
}
