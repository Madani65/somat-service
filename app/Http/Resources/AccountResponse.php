<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountResponse extends JsonResource
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
            "email" => $this->email,
            "password" => $this->password,
            "general" => [
                "fullName" => $this->full_name,
                "nickName" => $this->nick_name,
                "gender" => $this->gender,
                "phone" => $this->phone,
                "placeOfBirth" => $this->place_of_birth,
                "dateOfBirth" => $this->date_of_birth,
                "idCard" => $this->id_card,
                "familyIdCard" => $this->family_id_card,
                "citizenship" => $this->citizenship,
                "bloodType" => $this->blood_type,
                "npwp" => $this->npwp,
                "maritalStatus" => $this->marital_status,
                "religion" => $this->religion,
                "education" => $this->education,
                "parentName" => $this->parent_name,
                "document" => $this->documents,
            ],
            "address" => [
                "idCard" => [
                    "detail" => $this->detail,
                    "asDomicile" => $this->as_idcard,
                ],
                "domicile" => [
                    "detail" => $this->as_domicile
                ]
            ]
        ];
    }
}
