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

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['fecha_inicio'] ?? false, function($query, $fecha) {
            $query->where('fecha_hora', '>=', $fecha);
        })
        ->when($filters['fecha_fin'] ?? false, function($query, $fecha) {
            $query->where('fecha_hora', '<=', $fecha);
        })
        ->when($filters['valor_minimo'] ?? false, function($query, $valor) {
            $query->where('value', '>=', $valor);
        })
        ->when($filters['valor_maximo'] ?? false, function($query, $valor) {
            $query->where('value', '<=', $valor);
        })
        ->when($filters['sensor_id'] ?? false, function($query, $sensorId) {
            $query->whereHas('sensorComponent', function($q) use ($sensorId) {
                $q->where('sensor_id', $sensorId);
            });
        });
    }
}
