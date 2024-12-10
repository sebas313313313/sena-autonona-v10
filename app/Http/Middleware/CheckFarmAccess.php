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
            
            if (!$farm || $farm->users_role_id !== $userRole->id) {
                abort(403, 'No tienes acceso a esta granja.');
            }
        }

        // Para la vista de listado de granjas, la verificación se hace en el controlador
        return $next($request);
    }
}
