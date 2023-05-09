<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosSessionPayMethod extends Model
{
    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class, "id_payment_method");
    }
}
