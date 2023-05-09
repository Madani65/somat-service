<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaxResponse extends JsonResource
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
            "idTax" => $this->id,
            "name" => $this->name,
            "idEntity" => $this->id_entity,
            "type" => $this->type,
            "value" => $this->value,
            "isAutoadd" => $this->is_autoadd
        ];
    }
}
