<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calibration extends Model
{
    use HasFactory;

    protected $fillable = ['date','parameters','alert','sensor_component_id'];

    public function Sensor_Component () {
        return $this->belongsTo('App\Models\Sensor_Component'); 
    }
}
