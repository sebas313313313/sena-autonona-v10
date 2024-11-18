<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Identification_Type extends Model
{
    use HasFactory;

    protected $table = 'identification_types';

    protected $fillable = [
        'description'
    ];

    protected $allowFilter = ['id', 'description'];

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

    public function User_Roles()
    {
        return $this->hasMany('App\Models\User_Roles');
    }
}
