<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Farm;

class DashboardController extends Controller
{
    public function index($farm_id)
    {
        $user = auth()->user();
        $farm = $user->farms()->findOrFail($farm_id);
        
        // Guardar el ID de la granja actual en la sesión
        session(['current_farm_id' => $farm_id]);
        session(['farm_role' => $farm->pivot->role]);
        
        // Si es operario, solo mostrar datos y tareas
        if ($farm->pivot->role === 'operario') {
            return view('dashboard.operario.index', [
                'farm' => $farm,
                'tasks' => $farm->tasks()->where('status', 'pendiente')->get()
            ]);
        }
        
        // Para administradores y otros roles, mostrar todo
        return view('dashboard.index', [
            'farm' => $farm,
            'tasks' => $farm->tasks,
            'users' => $farm->users,
            'statistics' => $this->getStatistics($farm)
        ]);
    }
    
    private function getStatistics($farm)
    {
        return [
            'completed_tasks' => $farm->tasks()->where('status', 'completada')->count(),
            'pending_tasks' => $farm->tasks()->where('status', 'pendiente')->count(),
            'total_users' => $farm->users()->count(),
            'recent_activities' => $farm->activities()->latest()->take(5)->get()
        ];
    }
}
