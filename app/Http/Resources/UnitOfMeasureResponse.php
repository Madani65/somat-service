<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UnitOfMeasureResponse extends JsonResource
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
            "idUoM" => $this->id,
            "code" => $this->code,
            "shortName" => $this->short_name,
            "longName" => $this->long_name
        ];
    }
}
