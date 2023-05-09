<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResponse extends JsonResource
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
            "idProduct" => $this->id,
            "idEntity" => $this->id_entity,
            "name" => $this->name,
            "idCategory" => $this->id_category,
            "categoryName" => $this->category?->name,
            "isManufacture" => $this->is_manufacture,
            "price" => $this->price,
            "initPrice" => $this->init_price,
            "sku" => $this->sku,
            "uom" => $this->uom,
            "uomShortName" => $this->unit_of_measure?->short_name,
            "measureConv" => $this->measure_conv,
            "documents" => $this->documents,
        ];
    }
}
