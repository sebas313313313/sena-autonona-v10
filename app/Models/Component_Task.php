<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Farm_Component;

class Component_Task extends Model
{
    use HasFactory;

    protected $table = 'component_tasks';

    protected $fillable = [
        'date',
        'time',
        'status',
        'comments',
        'farm_component_id',
        'user_id'
    ];

    protected $allowFilter = [
        'date',
        'time',
        'status',
        'comments',
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime:H:i:s',
        'status' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function farmComponent()
    {
        return $this->belongsTo(Farm_Component::class, 'farm_component_id');
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
