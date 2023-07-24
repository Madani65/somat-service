<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SchoolYearResponse extends JsonResource
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
            "idSchoolYear" => $this->id,
            "name" => $this->name,
            "startYear" => $this->start_year,
            "endYear" => $this->end_year,
            "curriculumName" => $this->curriculum_year
        ];
    }
}
