<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'department'];

    public function User_Roles()
    {
        return $this->hasMany('App\Models\User_Roles');
    }
}
