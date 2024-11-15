<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    public function Component_Task() {
        return $this->belongsTo('App\Models\Component_Task');
    }
}
