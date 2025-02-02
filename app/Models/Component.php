<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sensor;

class Component extends Model
{
    use HasFactory;

    protected $fillable = [
        'description'
    ];

    protected $allowFilter = ['description'];

    public function Farm_Component() {
        return $this->belongsTo('App\Models\Farm_Component');
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


                $query->where($filter, 'LIKE', '%' . $value . '%');
            }
        }
    }
}
