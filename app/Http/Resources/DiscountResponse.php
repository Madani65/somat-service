<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResponse extends JsonResource
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
            "idDiscount" => $this->id,
            "idEntity" => $this->id_entity,
            "name" => $this->name,
            "startDate" => $this->start_date,
            "endDate" => $this->end_date,
            "type" => $this->type,
            "value" => $this->value,
            "products" => $this->products_pivot->pluck("id_product")
        ];
    }
}
