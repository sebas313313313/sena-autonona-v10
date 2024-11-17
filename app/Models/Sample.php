<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sensor_Component;

class Sample extends Model
{
    use HasFactory;

    protected $fillable = [
        'sensor_component_id',
        'fecha_hora',
        'value'
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
        'value' => 'integer'
    ];

    /**
     * Obtiene el componente del sensor asociado a esta muestra.
     */
    public function sensorComponent()
    {
        return $this->belongsTo(Sensor_Component::class, 'sensor_component_id');
    }
}
