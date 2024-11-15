<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farm_Component extends Model
{
    use HasFactory;

    public function Component_Task() {
        return $this->belongsTo('App\Models\Component_Task');
    }

    public function Farm() {
        return $this->hasMany('App\Models\Farm');
    }

    public function Components() {
        return $this->hasMany('App\Models\Component');
    }
}
