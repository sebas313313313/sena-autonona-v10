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

        collect($filters)
            ->filter(function ($value, $field) use ($allowFilter) {
                return $allowFilter->contains($field);
            })
            ->each(function ($value, $field) use ($query) {
                $query->where($field, $value);
            });
    }

    public function sensor_components()
    {
        return $this->hasMany(Sensor_Component::class, 'sensor_id');
    }

    public function farmComponents()
    {
        return $this->belongsToMany(Farm_Component::class, 'sensor_components', 'sensor_id', 'farm_component_id')
                    ->withPivot(['min', 'max']);
    }

    public function farms()
    {
        return $this->belongsToMany(\App\Models\Farm::class, 'farm_components')
            ->through('farmComponents');
    }
}
