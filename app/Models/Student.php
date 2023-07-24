<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    public function account (){
        return $this->hasOne(MemberAccount::class, "id_account", "id_account");
    }
}
