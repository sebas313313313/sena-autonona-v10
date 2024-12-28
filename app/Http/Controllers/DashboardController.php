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
        try {
            $user = auth()->user();
            $farm = Farm::with(['farmComponents.sensorComponents.sensor', 'farmComponents.sensorComponents.samples'])
                        ->findOrFail($farm_id);
            
            // Obtener el rol del usuario en esta granja
            $farmUser = $farm->users()->where('user_id', $user->id)->first();
            
            if (!$farmUser) {
                return redirect()->route('farms.index')->with('error', 'No tienes acceso a esta granja');
            }

            $role = $farmUser->pivot->role;
            
            // Guardar el ID de la granja y el rol en la sesión
            session(['current_farm_id' => $farm_id]);
            session(['farm_role' => $role]);

            \Log::info('Rol del usuario en la granja:', [
                'user_id' => $user->id,
                'farm_id' => $farm_id,
                'role' => $role,
                'session_role' => session('farm_role')
            ]);
            
            // Obtener los sensores y sus datos
            $sensorData = [];
            foreach ($farm->farmComponents as $farmComponent) {
                foreach ($farmComponent->sensorComponents as $sensorComponent) {
                    // Obtener las últimas 24 horas de datos
                    $samples = $sensorComponent->samples()
                        ->where('fecha_hora', '>=', Carbon::now()->subHours(24))
                        ->orderBy('fecha_hora', 'asc')
                        ->get();

                    // Agregar datos de prueba si no hay muestras
                    if ($samples->isEmpty()) {
                        $now = Carbon::now();
                        $testData = [];
                        for ($i = 24; $i >= 0; $i--) {
                            $testData[] = [
                                'fecha' => $now->copy()->subHours($i)->format('Y-m-d H:i'),
                                'valor' => rand($sensorComponent->min ?: 0, $sensorComponent->max ?: 100)
                            ];
                        }
                        $samples = collect($testData);
                    } else {
                        $samples = $samples->map(function ($sample) {
                            return [
                                'fecha' => $sample->fecha_hora->format('Y-m-d H:i'),
                                'valor' => $sample->value
                            ];
                        });
                    }

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
                'sensorData' => $sensorData,
                'role' => $role
            ];

            // Si es operario, solo mostrar datos y tareas pendientes
            if ($role === 'operario') {
                $viewData['tasks'] = $farm->tasks()->where('status', false)->get();
                return view('dashboard.operario.index', $viewData);
            }
            
            // Para administradores y propietarios, mostrar todo
            $viewData['tasks'] = $farm->tasks;
            $viewData['users'] = $farm->users;
            $viewData['statistics'] = $this->getStatistics($farm);
            
            return view('dashboard.index', $viewData);
        } catch (\Exception $e) {
            \Log::error('Error en dashboard index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar el dashboard: ' . $e->getMessage());
        }
    }
    
    public function users($farm_id)
    {
        try {
            // Obtener la granja
            $farm = Farm::findOrFail($farm_id);
            
            // Guardar el ID de la granja en la sesión
            session(['current_farm_id' => $farm_id]);

            // Obtener el primer usuario administrador (considerado como propietario)
            $firstAdmin = $farm->users()
                ->wherePivot('role', 'admin')
                ->orderBy('farm_user.created_at')
                ->first();

            // Obtener todos los usuarios y marcar el propietario
            $users = $farm->users->map(function($user) use ($firstAdmin) {
                $user->is_owner = ($firstAdmin && $user->id === $firstAdmin->id);
                return $user;
            });

            return view('dashboard.tables.index', [
                'farm' => $farm,
                'users' => $users
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en users(): ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar la página de usuarios: ' . $e->getMessage());
        }
    }
    
    public function unlinkUser($farm_id, $user_id)
    {
        try {
            $farm = Farm::findOrFail($farm_id);
            
            // Verificar que el usuario a desvincular no sea el propietario
            $firstAdmin = $farm->users()
                ->wherePivot('role', 'admin')
                ->orderBy('farm_user.created_at')
                ->first();

            if ($firstAdmin && $firstAdmin->id == $user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede desvincular al propietario de la granja'
                ], 403);
            }

            // Desvincular el usuario de la granja
            $farm->users()->detach($user_id);

            return response()->json([
                'success' => true,
                'message' => 'Usuario desvinculado exitosamente'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al desvincular usuario: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al desvincular el usuario'
            ], 500);
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
