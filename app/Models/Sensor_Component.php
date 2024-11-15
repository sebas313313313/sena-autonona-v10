<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensor_Component extends Model
{
    use HasFactory;

    public function Calibrations() {
        return $this->hasMany('App\Models\Calibration');   
    }

}
