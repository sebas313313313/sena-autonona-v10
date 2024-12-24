<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Farm;

class FarmAccess
{
    public function handle(Request $request, Closure $next)
    {
        $farm_id = $request->route('farm_id') ?? session('current_farm_id');
        
        if (!$farm_id) {
            return redirect()->route('home')->with('error', 'Por favor, selecciona una granja.');
        }

        $user = auth()->user();
        $farm = Farm::findOrFail($farm_id);

        // Verificar si el usuario es el dueño de la granja
        if ($farm->users_role_id === $user->userRole->id) {
            return $next($request);
        }

        // Verificar si el usuario está invitado a la granja
        $isInvited = $farm->users()
            ->where('users.id', $user->id)
            ->exists();

        if (!$isInvited) {
            return redirect()->route('home')->with('error', 'No tienes acceso a esta granja.');
        }

        return $next($request);
    }
}
