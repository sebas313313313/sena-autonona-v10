<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Component_Task;
use App\Models\Farm;
use App\Models\Component;
use App\Models\Sensor_Component;
use App\Models\Sensor;

class Farm_Component extends Model
{
    use HasFactory;

    protected $table = 'farm_components';

    protected $fillable = [
        'farm_id',
        'component_id',
        'description'
    ];

    protected $allowFilter = [
        'description'
    ];

    public function componentTasks()
    {
        return $this->hasMany(Component_Task::class);
    }

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function component()
    {
        return $this->belongsTo(Component::class);
    }

    public function sensorComponents()
    {
        return $this->hasMany(Sensor_Component::class, 'farm_component_id');
    }

    public function sensors()
    {
        return $this->belongsToMany(Sensor::class, 'sensor_components', 'farm_component_id', 'sensor_id');
    }

    public function scopeFilter($query)
    {
        if (empty($this->allowFilter) || empty(request('filter'))) {
            return;
        }

        $filters = request('filter');
        $allowFilter = collect($this->allowFilter);

        foreach ($filters as $filter => $value) {
            if ($allowFilter->contains($filter)) {
                $query->where($filter, 'LIKE', "%$value%");
            }
        }
    }
}
