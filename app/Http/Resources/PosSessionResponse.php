<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PosSessionResponse extends JsonResource
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
            "idSession" => $this->id,
            "idEntity" => $this->id_entity,
            "name" => $this->name,
            "idEntityMap" => $this->id_entity_map,
            "entityMapName" => $this->entity_map->name,
            "notesFlag" => $this->notes_flag,
            "tablesFlag" => $this->tables_flag,
            "paymentMethods" => $this->payment_methods_pivot->pluck("id_payment_method"),
            "casierPrintFlag" => $this->casier_print_flag,
            "kitchenPrintFlag" => $this->kitchen_print_flag,
            "customReceiptFlag" => $this->custom_print_flag,
            "customReceiptHeader" => $this->custom_receipt_header,
            "customReceiptFooter" => $this->custom_receipt_footer,
            "autoprintBeforeFlag" => $this->autoprint_before_flag,
            "autoprintAfterFlag" => $this->autoprint_after_flag,
            "documents" => $this->documents,
        ];
    }
}
