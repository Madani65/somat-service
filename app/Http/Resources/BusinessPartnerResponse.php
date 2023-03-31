<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BusinessPartnerResponse extends JsonResource
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
            "idBusinessPartner" => $this->id,
            "idEntity" => $this->id_entity,
            "partnerNum" => $this->partner_num,
            "partnerName" => $this->partner_name,
            "idPartnerType" => $this->id_partner_type,
            "partnerTypeName" => $this->partner_type->name,
            "asCustomer" => $this->as_customer,
            "asSupplier" => $this->as_supplier,
            "email" => $this->email,
            "address" => $this->address,
            "gender" => $this->gender,
            "phone" => $this->phone,
            "salesPerson" => $this->sales_person,
            "salesPhone" => $this->sales_phone,
            "dateOfBirth" => $this->date_of_birth,
            "asDefault" => $this->as_default,
            "documents" => $this->documents
        ];
    }
}
