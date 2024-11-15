<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farms extends Model
{
    use HasFactory;

    public function Farm_Component() {
        return $this->belongsTo('App\Models\Farm_Component');
    }
}
