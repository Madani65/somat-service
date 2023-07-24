<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PosSessionOpenResponse extends JsonResource
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
            "idSessionOpen" => $this->id,
            "idEntity" => $this->id_entity,
            "idAccount" => $this->id_account,
            "startTime" => $this->start_time,
            "cashOpen" => $this->cash_open,
            "notesOpen" => $this->notes_open,
            "endTime" => $this->end_time,
            "cashClose" => $this->cash_close,
            "notesClose" => $this->notes_close,
        ];
    }
}
