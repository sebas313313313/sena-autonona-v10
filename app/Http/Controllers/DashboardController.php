<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Farm;
use App\Models\Sample;
use App\Models\Sensor_Component;
use App\Models\User;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    public function index($farm_id)
    {
        try {
            DB::enableQueryLog();
            $debugLogs = [];
            $user = auth()->user();
            
            // Cargar la granja con todas sus relaciones
            $farm = Farm::with([
                'farmComponents',
                'farmComponents.sensorComponents',
                'farmComponents.sensorComponents.sensor',
                'farmComponents.sensorComponents.samples'
            ])->findOrFail($farm_id);

            // Obtener todos los sensores de la granja
            $allSensors = \App\Models\Sensor::where('farm_type', $farm->farm_type)->get();
            
            // Expandir los sensor_components para ver sus detalles
            $data = [
                'farm_id' => $farm_id,
                'farm_type' => $farm->farm_type,
                'components_count' => $farm->farmComponents->count(),
                'all_available_sensors' => $allSensors->map(function($s) {
                    return [
                        'id' => $s->id,
                        'description' => $s->description,
                        'farm_type' => $s->farm_type
                    ];
                })->toArray(),
                'components' => $farm->farmComponents->map(function($fc) {
                    return [
                        'id' => $fc->id,
                        'description' => $fc->description,
                        'sensor_components' => $fc->sensorComponents->map(function($sc) {
                            return [
                                'id' => $sc->id,
                                'farm_component_id' => $sc->farm_component_id,
                                'sensor_id' => $sc->sensor_id,
                                'min' => $sc->min,
                                'max' => $sc->max,
                                'sensor' => $sc->sensor ? [
                                    'id' => $sc->sensor->id,
                                    'description' => $sc->sensor->description,
                                    'estado' => $sc->sensor->estado,
                                    'farm_type' => $sc->sensor->farm_type
                                ] : null
                            ];
                        })->toArray()
                    ];
                })->toArray()
            ];

            \Log::info('Datos de la granja:', $data);

            // Log de los componentes de la granja
            $debugLogs[] = [
                'title' => 'Farm Components encontrados',
                'data' => [
                    'farm_id' => $farm_id,
                    'components_count' => $farm->farmComponents->count(),
                    'components' => $farm->farmComponents->map(function($fc) {
                        return [
                            'id' => $fc->id,
                            'description' => $fc->description,
                            'sensor_components' => $fc->sensorComponents->map(function($sc) {
                                return [
                                    'id' => $sc->id,
                                    'sensor_id' => $sc->sensor_id,
                                    'sensor_description' => $sc->sensor->description,
                                    'sensor_estado' => $sc->sensor->estado
                                ];
                            })->toArray()
                        ];
                    })->toArray()
                ]
            ];

            // Obtener el rol del usuario en esta granja
            $farmUser = $farm->users()->where('user_id', $user->id)->first();
            
            if (!$farmUser) {
                return redirect()->route('farms.index')->with('error', 'No tienes acceso a esta granja');
            }

            $role = $farmUser->pivot->role;
            
            // Guardar el ID de la granja y el rol en la sesión
            session(['current_farm_id' => $farm_id]);
            session(['farm_role' => $role]);

            // Obtener los sensores y sus datos
            $sensorData = [];
            
            \Log::info('Procesando farm components:', [
                'total_components' => $farm->farmComponents->count(),
                'components' => $farm->farmComponents->map(function($fc) {
                    return [
                        'id' => $fc->id,
                        'description' => $fc->description,
                        'sensor_components_count' => $fc->sensorComponents->count()
                    ];
                })->toArray()
            ]);

            foreach ($farm->farmComponents as $farmComponent) {
                \Log::info('Procesando farm component:', [
                    'id' => $farmComponent->id,
                    'description' => $farmComponent->description,
                    'sensor_components' => $farmComponent->sensorComponents->map(function($sc) {
                        return [
                            'id' => $sc->id,
                            'sensor_id' => $sc->sensor_id,
                            'sensor_description' => $sc->sensor ? $sc->sensor->description : 'NO SENSOR'
                        ];
                    })->toArray()
                ]);

                foreach ($farmComponent->sensorComponents as $sensorComponent) {
                    // Verificar que el sensor existe
                    if (!$sensorComponent->sensor) {
                        \Log::warning('Sensor no encontrado para component:', [
                            'sensor_component_id' => $sensorComponent->id,
                            'sensor_id' => $sensorComponent->sensor_id,
                            'farm_component_id' => $sensorComponent->farm_component_id
                        ]);
                        continue;
                    }

                    // Obtener las últimas 24 horas de datos
                    $samples = $sensorComponent->samples()
                        ->where('fecha_hora', '>=', Carbon::now()->subHours(24))
                        ->orderBy('fecha_hora', 'asc')
                        ->get();

                    // Generar datos de prueba si el sensor está activo
                    if ($sensorComponent->sensor->estado === 'activo') {
                        $now = Carbon::now();
                        $testData = [];
                        for ($i = 24; $i >= 0; $i--) {
                            $testData[] = [
                                'fecha' => $now->copy()->subHours($i)->format('Y-m-d H:i'),
                                'valor' => rand($sensorComponent->min ?: 0, $sensorComponent->max ?: 100)
                            ];
                        }
                        $samples = collect($testData);
                    }

                    $sensorData[] = [
                        'id' => $sensorComponent->sensor->id,
                        'nombre' => $sensorComponent->sensor->description,
                        'estado' => $sensorComponent->sensor->estado,
                        'muestras' => $samples
                    ];

                    \Log::info('Agregando sensor a sensorData:', [
                        'sensor_component_id' => $sensorComponent->id,
                        'sensor_id' => $sensorComponent->sensor->id,
                        'nombre' => $sensorComponent->sensor->description,
                        'estado' => $sensorComponent->sensor->estado,
                        'muestras_count' => count($samples)
                    ]);
                }
            }

            // Log final de sensores
            \Log::info('Total sensores procesados:', [
                'count' => count($sensorData),
                'sensores' => collect($sensorData)->map(function($s) {
                    return [
                        'id' => $s['id'],
                        'nombre' => $s['nombre'],
                        'estado' => $s['estado'],
                        'muestras' => count($s['muestras'])
                    ];
                })->toArray()
            ]);

            return view('dashboard.index', [
                'farm' => $farm,
                'sensorData' => $sensorData,
                'debugLogs' => $debugLogs,
                'user' => $user
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en dashboard: ' . $e->getMessage(), [
                'farm_id' => $farm_id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Error al cargar el dashboard: ' . $e->getMessage());
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
