<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Job;
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
        'job_id',
        'farm_component_id',
        'user_id'
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime:H:i:s'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function job() {
        return $this->belongsTo(Job::class);
    }

    public function farmComponent() {
        return $this->belongsTo(Farm_Component::class, 'farm_component_id');
    }
}
