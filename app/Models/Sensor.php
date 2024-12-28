<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'farm_type',
        'estado'
    ];

    protected $allowFilter = [
        'id',
        'description',
        'farm_type',
        'estado'
    ];

    public function scopeFilter($query)
    {
        if (empty($this->allowFilter) || empty(request('filter'))) {
            return;
        }

        $filters = request('filter');
        $allowFilter = collect($this->allowFilter);

        foreach ($filters as $filter => $value) {
            if ($allowFilter->contains($filter)) {
                if ($filter === 'id') {
                    $query->where($filter, $value);
                } else {
                    $query->where($filter, 'LIKE', '%' . $value . '%');
                }
            }
        }
    }

    public function sensorComponents()
    {
        return $this->hasMany(\App\Models\Sensor_Component::class);
    }

    public function farmComponents()
    {
        return $this->belongsToMany(\App\Models\Farm_Component::class, 'sensor_components');
    }

    public function farms()
    {
        return $this->belongsToMany(\App\Models\Farm::class, 'farm_components')
            ->through('farmComponents');
    }
}
