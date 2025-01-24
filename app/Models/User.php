<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address'
    ];

    protected $allowFilter = [
        'id',
        'name',
        'email'
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
                if ($filter === 'id') {
                    $query->where($filter, $value);
                } else {
                    $query->where($filter, 'LIKE', '%' . $value . '%');
                }
            }
        }
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'users_roles', 'user_id', 'role_id');
    }

    public function identification_type()
    {
        return $this->belongsTo(IdentificationType::class, 'identification_type_id');
    }

    public function security_questions()
    {
        return $this->belongsToMany(SecurityQuestion::class, 'user_security_questions', 'user_id', 'security_question_id')
            ->withPivot('answer');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the user's role information
     */
    public function users_role()
    {
        return $this->hasOne(Users_Role::class);
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function userRole()
    {
        return $this->hasOne(Users_Role::class, 'user_id');
    }

    public function User_Roles()
    {
        return $this->hasMany('App\Models\User_Roles');
    }

    public function Component_Task() {
        return $this->belongsTo('App\Models\Component_Task');
    }

    public function farms()
    {
        return $this->belongsToMany('App\Models\Farm', 'farm_user')
                    ->withPivot('role')
                    ->withTimestamps();
    }
}
