<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Farm;

class FarmAccess
{
    public function handle(Request $request, Closure $next)
    {
        // Obtener el farm_id de la ruta o de la sesión
        $farm_id = $request->route('farm_id') ?? session('current_farm_id');
        
        if (!$farm_id) {
            return redirect()->route('farms.index')->with('error', 'Por favor, selecciona una granja.');
        }

        try {
            $user = auth()->user();
            $farm = Farm::findOrFail($farm_id);

            // Guardar el farm_id en la sesión
            session(['current_farm_id' => $farm_id]);

            // Verificar si el usuario tiene acceso a la granja
            $farmUser = $farm->users()->where('user_id', $user->id)->first();
            
            if (!$farmUser) {
                return redirect()->route('farms.index')
                    ->with('error', 'No tienes acceso a esta granja.');
            }

            // Guardar el rol en la sesión
            session(['farm_role' => $farmUser->pivot->role]);

            return $next($request);
            
        } catch (\Exception $e) {
            \Log::error('Error en FarmAccess middleware: ' . $e->getMessage());
            return redirect()->route('farms.index')
                ->with('error', 'Error al acceder a la granja.');
        }
    }
}
