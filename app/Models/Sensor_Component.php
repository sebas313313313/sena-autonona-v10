<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Calibration;
use App\Models\Farm_Component;
use App\Models\Sensor;
use App\Models\Sample;

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

    protected $allowFilter = [
        'farm_component_id',
        'sensor_id',
        'min',
        'max'
    ];

    protected $casts = [
        'min' => 'float',
        'max' => 'float'
    ];

    public function farmComponent()
    {
        return $this->belongsTo(Farm_Component::class);
    }

    public function sensor()
    {
        return $this->belongsTo(Sensor::class);
    }

    public function samples()
    {
        return $this->hasMany(Sample::class, 'sensor_component_id');
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
                if (in_array($filter, ['min', 'max'])) {
                    $query->where($filter, $value);
                } else {
                    $query->where($filter, 'LIKE', '%' . $value . '%');
                }
            }
        }
    }

    public function calibrations()
    {
        return $this->hasMany(Calibration::class);
    }
}
