<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Farm;

class CheckFarmAccess
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->userRole;
        if (!$userRole) {
            return redirect()->route('login');
        }

        // Si la ruta contiene un farm_id, verificar acceso específico a esa granja
        if ($request->route('farm_id') || $request->route('farm')) {
            $farmId = $request->route('farm_id') ?? $request->route('farm')->id;
            $farm = Farm::find($farmId);
            
            if (!$farm) {
                abort(404, 'Granja no encontrada.');
            }

            $user = auth()->user();
            
            // Verificar si el usuario es el propietario
            if ($farm->users_role_id === $userRole->id) {
                session(['current_farm_id' => $farm->id]);
                return $next($request);
            }
            
            // Verificar si el usuario está invitado a la granja
            $farmUser = $farm->users()->where('users.id', $user->id)->first();
            if ($farmUser) {
                session(['current_farm_id' => $farm->id]);
                // El rol específico de la granja está en $farmUser->pivot->role
                return $next($request);
            }
            
            abort(403, 'No tienes acceso a esta granja.');
        }

        // Para la vista de listado de granjas, la verificación se hace en el controlador
        return $next($request);
    }
}
