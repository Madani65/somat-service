<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    public function classMajor () 
    {
        return $this->belongsTo(ClassMajor::class, "class_major_id");
    }

    public function schoolYear ()
    {
        return $this->belongsTo(SchoolYear::class, "school_year_id");
    }

    public function classCategory ()
    {
        return $this->belongsTo(LessonCategory::class, "class_category_id");
    }
}
