<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Calibration;
use App\Models\Farm_Component;
use App\Models\Sensor;

class Sensor_Component extends Model
{
    use HasFactory;

    protected $table = 'sensor_components';

    protected $fillable = [
        'farm_component_id',
        'sensor_id',
        'min',
        'max'
    ];

    protected $casts = [
        'min' => 'float',
        'max' => 'float'
    ];

    public function calibrations()
    {
        return $this->hasMany(Calibration::class);
    }

    public function farmComponent()
    {
        return $this->belongsTo(Farm_Component::class, 'farm_component_id');
    }

    public function sensor()
    {
        return $this->belongsTo(Sensor::class);
    }
}
