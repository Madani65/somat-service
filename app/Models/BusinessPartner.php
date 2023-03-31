<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessPartner extends Model
{
    protected $casts = [
        "documents" => "array"
    ];

    public function partner_type()
    {
        return $this->belongsTo(PartnerType::class, "id_partner_type");
    }
}
