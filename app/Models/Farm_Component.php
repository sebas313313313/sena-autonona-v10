<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Component_Task;
use App\Models\Farm;
use App\Models\Component;

class Farm_Component extends Model
{
    use HasFactory;

    protected $table = 'farm_components';

    protected $fillable = [
        'description',
        'farm_id',
        'component_id'
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
}
