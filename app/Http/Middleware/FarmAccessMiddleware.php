<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Farm;

class FarmAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $farmId = $request->route('farm_id');
        $user = auth()->user();
        
        // Obtener la granja
        $farm = Farm::find($farmId);
        
        if (!$farm) {
            return redirect()->route('farms.index')
                ->with('error', 'La granja no existe.');
        }
        
        // Verificar si el usuario es el dueño de la granja
        $userRole = $user->userRole()->first();
        if ($userRole && $farm->users_role_id === $userRole->id) {
            return $next($request);
        }
        
        // Verificar si el usuario está invitado a la granja
        $invitedFarm = $user->farms()->where('farms.id', $farmId)->first();
        if ($invitedFarm) {
            return $next($request);
        }
        
        return redirect()->route('farms.index')
            ->with('error', 'No tienes acceso a esta granja.');
    }
}
