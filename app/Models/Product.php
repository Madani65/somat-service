<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $casts = [
        "documents" => "array"
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, "id_category");
    }

    public function unit_of_measure()
    {
        return $this->belongsTo(UnitOfMeasure::class, "uom", "code");
    }
}
