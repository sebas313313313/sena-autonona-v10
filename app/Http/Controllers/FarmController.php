<?php

namespace App\Http\Controllers;

use App\Models\Farm;
use App\Models\Municipality;
use App\Models\Users_Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Component;
use App\Models\Farm_Component;
use App\Models\Sensor;
use App\Models\Sensor_Component;
use Database\Seeders\SensorSeeder;
use Database\Seeders\SampleSeeder;

/**
 * Controlador para gestionar las granjas del sistema
 * Maneja las operaciones CRUD para las granjas y sus relaciones con usuarios y municipios
 */
class FarmController extends Controller
{
    /**
     * Obtiene y muestra la lista de todas las granjas
     * Incluye información relacionada de usuarios y municipios
     * @return \Illuminate\Http\View
     */
    public function index()
    {
        $user = auth()->user();
        Log::info('Usuario accediendo a lista de granjas: ' . $user->email);
        
        $userRole = $user->userRole()->first();
        
        if (!$userRole) {
            Log::warning('Usuario sin rol accediendo a granjas');
            return redirect()->route('login');
        }

        // Obtener las granjas propias
        $farms = Farm::where('users_role_id', $userRole->id)
                    ->with('municipality')
                    ->get();
        Log::info('Granjas propias encontradas: ' . $farms->count());

        // Obtener las granjas invitadas (excluyendo las propias)
        $invitedFarms = $user->farms()
                            ->whereNotIn('farms.id', $farms->pluck('id'))
                            ->with('municipality')
                            ->get();
        Log::info('Granjas invitadas encontradas: ' . $invitedFarms->count());
        
        // Obtener todos los componentes disponibles
        $components = \App\Models\Component::all();

        return view('farms.index', [
            'farms' => $farms,
            'invitedFarms' => $invitedFarms,
            'municipalities' => Municipality::all(),
            'components' => $components
        ]);
    }

    /**
     * Almacena una nueva granja en el sistema
     * @param Request $request Datos de la nueva granja (ubicación, extensión, etc.)
     * @return \Illuminate\Http\RedirectResponse
     * 
     * Campos requeridos:
     * - address: Dirección física
     * - vereda: Ubicación específica rural
     * - extension: Tamaño de la granja
     * - municipality_id: ID del municipio
     * - latitude: Coordenada de latitud
     * - longitude: Coordenada de longitud
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'farm_type' => 'required|string|in:acuaponica,hidroponica,vigilancia,riego',
                'address' => 'required|string|max:255',
                'vereda' => 'required|string|max:255',
                'extension' => 'required|numeric',
                'municipality_id' => 'required|exists:municipalities,id',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'sensors' => 'required|array|min:1',
                'sensors.*' => 'required|string'
            ]);

            // Obtener el ID del rol del usuario
            $userRoleId = auth()->user()->userRole()->first()->id;

            $farm = Farm::create([
                'name' => $request->name,
                'farm_type' => $request->farm_type,
                'address' => $request->address,
                'vereda' => $request->vereda,
                'extension' => $request->extension,
                'municipality_id' => $request->municipality_id,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'users_role_id' => $userRoleId
            ]);

            // Asignar al usuario como administrador de la granja
            $farm->users()->attach(auth()->id(), [
                'role' => 'admin',
                'status' => 'active'
            ]);

            // Crear los componentes seleccionados para la granja
            if ($farm) {
                foreach ($request->sensors as $sensorType) {
                    // Buscar el sensor por tipo
                    $sensor = Sensor::where('description', $sensorType)
                                  ->where('farm_type', $request->farm_type)
                                  ->first();

                    if (!$sensor) {
                        \Log::warning("Sensor no encontrado: {$sensorType} para tipo de granja: {$request->farm_type}");
                        continue;
                    }

                    // Crear el componente
                    $component = Component::create([
                        'name' => $sensorType,
                        'description' => "Componente de {$sensorType}"
                    ]);

                    // Crear la relación farm_component
                    $farmComponent = Farm_Component::create([
                        'farm_id' => $farm->id,
                        'component_id' => $component->id,
                        'description' => "Componente de {$sensorType} para {$farm->name}"
                    ]);

                    // Crear la relación sensor_component
                    Sensor_Component::create([
                        'farm_component_id' => $farmComponent->id,
                        'sensor_id' => $sensor->id,
                        'min' => 0, // Valores por defecto
                        'max' => 100 // Valores por defecto
                    ]);
                }
            }

            return redirect()->route('farms.index')->with('success', 'Granja creada exitosamente');
        } catch (\Exception $e) {
            \Log::error('Error al crear granja: ' . $e->getMessage());
            return back()->with('error', 'Error al crear la granja: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Muestra los detalles de una granja específica
     * @param Farm $farm La granja a consultar
     * @return \Illuminate\Http\JsonResponse Datos de la granja y sus relaciones
     */
    public function show(Farm $farm)
    {
        try {
            return response()->json([
                'data' => $farm->load(['usersRole', 'municipality'])
            ]);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json([
                    'error' => 'Error al obtener granja',
                    'debug' => [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]
                ], 500);
            }
            return response()->json(['error' => 'No se pudo obtener la granja. Por favor, intente más tarde'], 500);
        }
    }

    /**
     * Actualiza la información de una granja existente
     * @param Request $request Nuevos datos de la granja
     * @param Farm $farm La granja a actualizar
     * @return \Illuminate\Http\JsonResponse Respuesta con la granja actualizada
     */
    public function update(Request $request, Farm $farm)
    {
        try {
            $validator = Validator::make($request->all(), [
                'latitude' => 'numeric',
                'longitude' => 'numeric',
                'address' => 'string|max:100',
                'vereda' => 'string|max:50',
                'extension' => 'string|max:50',
                'users_role_id' => 'exists:users_roles,id',
                'municipality_id' => 'exists:municipalities,id'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $farm->update($request->all());
            return response()->json([
                'message' => 'Granja actualizada exitosamente',
                'data' => $farm->load(['usersRole', 'municipality'])
            ]);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json([
                    'error' => 'Error al actualizar granja',
                    'debug' => [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]
                ], 500);
            }
            return response()->json(['error' => 'No se pudo actualizar la granja. Por favor, intente más tarde'], 500);
        }
    }

    /**
     * Elimina una granja específica del sistema
     * @param Farm $farm La granja a eliminar
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Farm $farm)
    {
        if ($farm->users_role_id !== auth()->user()->userRole()->first()->id) {
            return redirect()->back()->with('error', 'No tienes permiso para eliminar esta granja.');
        }

        $farm->delete();
        return redirect()->back()->with('success', 'Granja eliminada exitosamente.');
    }

    private function createFarmComponents(Farm $farm)
    {
        // Crear los componentes según el tipo de granja
        // Este método debe ser implementado según las necesidades específicas
    }
}
