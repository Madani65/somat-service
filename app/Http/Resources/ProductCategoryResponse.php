<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductCategoryResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $result = [
            "idProductCategory" => $this->id,
            "idEntity" => $this->id_entity,
            "name" => $this->name,
            "idParent" => $this->id_parent,
            "parentName" => $this->parent?->name,
            "documents" => $this->documents
        ];

        if($request->input("data.isParent"))
            $result["child"] = ProductCategoryResponse::collection($this->child);
            /* $this->child->transform( function($row) {
                return ["idProductCategory" => $row->id, "name" => $row->name];
            }); */

        return $result;
    }
}
