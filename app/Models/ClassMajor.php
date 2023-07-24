<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassMajor extends Model
{
    public function schoolLevel ()
    {
        return $this-> belongsTo(SchoolLevel::class, "school_level_id");
    }
}
