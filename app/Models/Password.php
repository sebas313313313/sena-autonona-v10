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

    protected $casts = [
        'fecha' => 'datetime'
    ];

    public function userRole()
    {
        return $this->belongsTo(Users_Role::class, 'users_role_id');
    }
}
