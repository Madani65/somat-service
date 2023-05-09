<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $casts = [
        "documents" => "array"
    ];

    public function parent()
    {
        return $this->belongsTo(ProductCategory::class, "id_parent");
    }

    public function child()
    {
        return $this->hasMany(ProductCategory::class, "id_parent");
    }
}
