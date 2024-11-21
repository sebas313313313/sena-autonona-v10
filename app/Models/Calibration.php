<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calibration extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'parameters', 'alert', 'sensor_component_id'];

    protected $allowFilter = ['id', 'date', 'parameters'];

    public function Sensor_Component()
    {
        return $this->belongsTo('App\Models\Sensor_Component');
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
