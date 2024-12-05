<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users_Role extends Model
{
    use HasFactory;

    protected $table = 'users_roles';
    
    protected $fillable = [
        'identification',
        'name',
        'Last_name',
        'date_birth',
        'direction',
        'contact',
        'user_id',
        'role_id',
        'identification_type_id',
        'municipality_id'
    ];

    protected $allowFilter = [
        'identification',
        'name',
        'Last_name',
        'date_birth',
        'contact',
        'identification_type_id',
        'municipality_id'
    ];

    protected $casts = [
        'date_birth' => 'date'
    ];

    /* 
        Con el metodo "belongsTo" relacionamos Rol_Usuario y Usuario a nivel de modelo,
        llamando al usuario al cual esta relacionado con rol_usuario 
        (ESTO RESPECTA A LAS DEMAS FUNCIONES CON "belongsTo") 
    */
    
    public function scopeFilter($query)
    {
        if (empty($this->allowFilter) || empty(request('filter'))) {
            return;
        }

        $filters = request('filter');
        $allowFilter = collect($this->allowFilter);

        foreach ($filters as $filter => $value) {
            if ($allowFilter->contains($filter)) {
                if ($filter === 'date_birth') {
                    $query->whereDate($filter, $value);
                } else {
                    $query->where($filter, 'LIKE', '%' . $value . '%');
                }
            }
        }
    }

    public function User(){
        return $this->belongsTo('App\Models\User'); 
    }

    public function Municipality(){
        return $this->belongsTo('App\Models\Municipality');
    }

    public function Identification_Type(){
        return $this->belongsTo('App\Models\Identification_Type');
    }
}
