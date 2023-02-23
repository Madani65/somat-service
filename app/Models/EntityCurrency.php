<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EntityCurrency extends Model
{
    public function currency()
    {
        return $this->belongsTo(Currency::class, "currency_code", "code");
    }
}
