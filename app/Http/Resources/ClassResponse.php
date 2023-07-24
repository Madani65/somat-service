<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClassResponse extends JsonResource
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
            "idClass" => $this->id,
            "className" => $this->class_name,
            "classLevel" => $this->class_level,
            "classMajor" => new ClassMajorResponse($this->classMajor),
            "SchoolYear" => new SchoolYearResponse($this->schoolYear),
            "classCategory" => new LessonCategoryResponse($this->classCategory) 
        ];
    }
}
