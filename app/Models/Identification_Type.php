<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Identification_Type extends Model
{
    use HasFactory;

    public function User_Roles()
    {
        return $this->hasMany('App\Models\User_Roles');
    }
}
