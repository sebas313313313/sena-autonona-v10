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

        $user = auth()->user();
        $userRole = $user->userRole;
        
        if (!$userRole) {
            return redirect()->route('login');
        }

        // Si estamos fuera del contexto de una granja específica
        if (!session('current_farm_id')) {
            // El usuario es admin por defecto fuera de las granjas
            session(['farm_role' => 'admin']);
            return $next($request);
        }

        // Si estamos dentro de una granja
        $farm = Farm::find(session('current_farm_id'));
        if (!$farm) {
            abort(404, 'Granja no encontrada.');
        }

        // Si el usuario es el dueño de la granja
        if ($farm->users_role_id === $userRole->id) {
            session(['farm_role' => 'admin']);
            return $next($request);
        }

        // Si el usuario está invitado a la granja
        $farmUser = $farm->users()->where('users.id', $user->id)->first();
        if ($farmUser) {
            session(['farm_role' => $farmUser->pivot->role]);
            return $next($request);
        }

        abort(403, 'No tienes acceso a esta granja.');
    }
}
