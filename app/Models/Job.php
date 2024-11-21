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

    protected $allowFilter = [
        'id',
        'name',
        'description'
    ];

    public function componentTasks()
    {
        return $this->hasMany(Component_Task::class);
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
