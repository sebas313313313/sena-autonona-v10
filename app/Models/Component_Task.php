<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Component_Task extends Model
{
    use HasFactory;

    protected $fillable =['date','date','time','status','status','comments','job_id','farm_component_id','user_id'];

    public function Users() {
        return $this->hasMany('App\Models\User');
    }

    public function Jobs() {
        return $this->hasMany('App\Models\Job');
    }

    public function Farm_Components() {
        return $this->hasMany('App\Models\Farm_Component');
    }
}
