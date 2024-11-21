<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farm extends Model
{
    use HasFactory;

    protected $fillable = [
        'latitude',
        'longitude',
        'address',
        'vereda',
        'extension',
        'users_role_id',
        'municipality_id'
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float'
    ];

    protected $allowFilter = [
        'latitude',
        'longitude',
        'address',
        'vereda',
        'extension',
    ];

    public function usersRole()
    {
        return $this->belongsTo(Users_Role::class, 'users_role_id');
    }

    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }

    public function farmComponents()
    {
        return $this->hasMany(Farm_Component::class);
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
