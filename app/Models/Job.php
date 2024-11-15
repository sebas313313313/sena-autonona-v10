<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Component_Task;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    public function componentTasks() {
        return $this->hasMany(Component_Task::class);
    }
}
