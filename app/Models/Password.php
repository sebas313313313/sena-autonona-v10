<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Users_Role;

class Password extends Model
{
    use HasFactory;

    protected $table = 'passwords';
    protected $primaryKey = 'id';

    protected $fillable = [
        'users_role_id',
        'contrasena',
        'pregunta',
        'respuesta',
        'fecha'
    ];

    protected $allowFilter = [
        'users_role_id',
        'pregunta',
        'fecha'
    ];

    protected $casts = [
        'fecha' => 'datetime'
    ];

    public function scopeFilter($query)
    {
        if (empty($this->allowFilter) || empty(request('filter'))) {
            return;
        }

        $filters = request('filter');
        $allowFilter = collect($this->allowFilter);

        foreach ($filters as $filter => $value) {
            if ($allowFilter->contains($filter)) {
                if ($filter === 'fecha') {
                    $query->whereDate($filter, $value);
                } else {
                    $query->where($filter, 'LIKE', '%' . $value . '%');
                }
            }
        }
    }

    public function userRole()
    {
        return $this->belongsTo(Users_Role::class, 'users_role_id');
    }
}
