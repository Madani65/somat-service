<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosSessionOpen extends Model
{
    public function pos_session()
    {
        return $this->belongsTo(PosSession::class, "id_session");
    }
}
