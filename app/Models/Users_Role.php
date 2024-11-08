<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users_Role extends Model
{
    use HasFactory;

    /* 
        Con el metodo "belongsTo" relacionamos Rol_Usuario y Usuario a nivel de modelo,
        llamando al usuario al cual esta relacionado con rol_usuario 
        (ESTO RESPECTA A LAS DEMAS FUNCIONES CON "belongsTo") 
    */
     
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
