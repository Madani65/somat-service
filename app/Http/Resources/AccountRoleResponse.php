<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountRoleResponse extends JsonResource
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
            "idAccount" => $this->id_account,
            "idEntity" => $this->id_entity,
            "idRole" => $this->role?->id,
            "roleName" => $this->role?->name
        ];
    }
}
