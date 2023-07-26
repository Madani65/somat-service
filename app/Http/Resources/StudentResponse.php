<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResponse extends JsonResource
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
            "idStudent" => $this->id,
            "idEntity" => $this->id_entity,
            "nisn" => $this->nisn,
            "nis" => $this->nis,
            "certificateNumber" => $this->certificate_number,
            "skhun" => $this->skhun,
            "effectiveStartDate" => $this->effective_start_date,
            "effectiveEndDate" => $this->effective_end_date,
            "account" => new AccountResponse($this->account)
        ];
    }
}
