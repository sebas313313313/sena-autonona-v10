<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use App\Models\Farm;

/**
 * Modelo Invitation - Gestiona las invitaciones a granjas
 * 
 * Este modelo maneja las invitaciones que los dueños de granjas pueden enviar a otros usuarios
 * para darles acceso a sus granjas con roles específicos.
 * 
 * Atributos:
 * - email: Correo electrónico del usuario invitado
 * - farm_id: ID de la granja a la que se invita
 * - token: Token único para validar la invitación
 * - role: Rol que tendrá el usuario en la granja (operario, administrador, etc.)
 * - expires_at: Fecha de expiración de la invitación
 * - accepted: Estado de aceptación de la invitación
 * 
 * Relaciones:
 * - farm: La granja a la que pertenece la invitación
 */
class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'role',
        'token',
        'accepted',
        'expires_at',
        'farm_id'
    ];

    protected $casts = [
        'accepted' => 'boolean',
        'expires_at' => 'datetime'
    ];

    /**
     * Obtiene la granja asociada a esta invitación
     */
    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public static function generateToken()
    {
        return Str::random(32);
    }

    public function isExpired()
    {
        return $this->expires_at->isPast();
    }
}
