<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    public function products_pivot()
    {
        return $this->hasMany(ProductDiscount::class, "id_discount");
    }
}
