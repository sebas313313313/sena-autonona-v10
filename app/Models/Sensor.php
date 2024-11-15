<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    use HasFactory;

    protected $fillable = [
        'description'
    ];

    public function sensorComponents()
    {
        return $this->hasMany(Sensor_Component::class);
    }
}
