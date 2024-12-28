<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Farm;
use App\Models\Sample;
use App\Models\Sensor_Component;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index($farm_id)
    {
        $user = auth()->user();
        $farm = Farm::with(['farmComponents.sensorComponents.sensor', 'farmComponents.sensorComponents.samples'])
                    ->findOrFail($farm_id);
        
        // Obtener el rol del usuario en esta granja
        $farmUser = $farm->users()->where('user_id', $user->id)->first();
        $role = $farmUser ? $farmUser->pivot->role : null;
        
        // Guardar el ID de la granja y el rol en la sesión
        session(['current_farm_id' => $farm_id]);
        session(['farm_role' => $role]);
        
        // Obtener los sensores y sus datos
        $sensorData = [];
        foreach ($farm->farmComponents as $farmComponent) {
            foreach ($farmComponent->sensorComponents as $sensorComponent) {
                // Obtener las últimas 24 horas de datos
                $samples = $sensorComponent->samples()
                    ->where('fecha_hora', '>=', Carbon::now()->subHours(24))
                    ->orderBy('fecha_hora')
                    ->get()
                    ->map(function ($sample) {
                        return [
                            'fecha' => $sample->fecha_hora->format('Y-m-d H:i'),
                            'valor' => $sample->value
                        ];
                    });

                $sensorData[] = [
                    'id' => $sensorComponent->id,
                    'nombre' => $sensorComponent->sensor->description,
                    'muestras' => $samples
                ];
            }
        }

        // Datos comunes para ambas vistas
        $viewData = [
            'farm' => $farm,
            'sensorData' => $sensorData
        ];

        // Si es operario, solo mostrar datos y tareas pendientes
        if ($farm->pivot && $farm->pivot->role === 'operario') {
            $viewData['tasks'] = $farm->tasks()->where('status', false)->get();
            return view('dashboard.operario.index', $viewData);
        }
        
        // Para administradores y otros roles, mostrar todo
        $viewData['tasks'] = $farm->tasks;
        $viewData['users'] = $farm->users;
        $viewData['statistics'] = $this->getStatistics($farm);
        
        return view('dashboard.index', $viewData);
    }
    
    public function users($farm_id)
    {
        try {
            // Obtener la granja
            $farm = Farm::findOrFail($farm_id);
            
            // Guardar el ID de la granja en la sesión
            session(['current_farm_id' => $farm_id]);

            // Obtener todos los usuarios de la granja
            $users = $farm->users;

            return view('dashboard.tables.index', [
                'farm' => $farm,
                'users' => $users
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en users(): ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar la página de usuarios: ' . $e->getMessage());
        }
    }
    
    private function getStatistics($farm)
    {
        return [
            'completed_tasks' => $farm->tasks()->where('status', true)->count(),
            'pending_tasks' => $farm->tasks()->where('status', false)->count(),
            'total_users' => $farm->users()->count()
        ];
    }
}
