<?php

namespace App\Http\Controllers;

use App\Models\Farm;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index($farm_id)
    {
        try {
            // Obtener la granja con sus relaciones
            $farm = Farm::with(['municipality'])->findOrFail($farm_id);
            
            // Obtener estadísticas
            $stats = [
                'total_users' => \App\Models\User::count(),
                'total_sensors' => \App\Models\Sensor::where('farm_id', $farm_id)->count(),
                'total_components' => \App\Models\Component::where('farm_id', $farm_id)->count(),
                'growth_rate' => 15 // Valor por defecto, ajustar según tus necesidades
            ];

            // Pasar los datos a la vista
            return view('dashboard.index', compact('farm', 'stats'));
        } catch (\Exception $e) {
            \Log::error('Error en dashboard: ' . $e->getMessage());
            return redirect()->route('farms.index')
                ->with('error', 'No se pudo cargar el dashboard de la granja');
        }
    }
}
