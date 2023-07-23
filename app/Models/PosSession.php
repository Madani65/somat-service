<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosSession extends Model
{
    protected $casts = [
        "documents" => "array"
    ];

    public function entity_map()
    {
        return $this->belongsTo(MemberEntity::class, "id_entity_map");
    }

    public function payment_methods_pivot()
    {
        return $this->hasMany(PosSessionPayMethod::class, "id_session");
    }

    public function pos_session_opens()
    {
        return $this->hasMany(PosSessionOpen::class, "id_session");
    }
}
