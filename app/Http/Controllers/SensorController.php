<?php

namespace App\Http\Controllers;

use App\Models\Component;
use App\Models\Sensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SensorController extends Controller
{
    /**
     * Muestra una lista de todos los sensores
     * @param Component $component Componente al que pertenecen los sensores
     * @return \Illuminate\Http\JsonResponse Lista de sensores en formato JSON
     */
    public function index(Component $component)
    {
        $sensors = $component->sensors;
        return response()->json(['sensors' => $sensors]);
    }

    /**
     * Almacena un nuevo sensor en la base de datos
     * @param Request $request Datos del nuevo sensor
     * @return \Illuminate\Http\JsonResponse Respuesta con el sensor creado
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'description' => 'required|string|max:255',
                'farm_type' => 'required|string|in:acuaponica,hidroponica,riego,vigilancia',
                'estado' => 'required|string|in:activo,inactivo',
                'component_id' => 'required|exists:components,id'
            ]);

            // Crear el sensor
            $sensor = Sensor::create([
                'description' => $request->description,
                'farm_type' => $request->farm_type,
                'estado' => $request->estado
            ]);

            // Obtener el componente
            $component = Component::findOrFail($request->component_id);

            // Asociar el sensor con el componente
            $component->sensors()->attach($sensor->id);

            return response()->json([
                'success' => true,
                'message' => 'Sensor creado exitosamente',
                'sensor' => $sensor
            ]);

        } catch (\Exception $e) {
            Log::error('Error al crear sensor: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el sensor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualiza el estado de un sensor específico
     * @param Request $request Datos de la actualización
     * @param int $id ID del sensor a actualizar
     * @return \Illuminate\Http\JsonResponse Respuesta con el resultado de la actualización
     */
    public function updateEstado(Request $request, $id)
    {
        try {
            $request->validate([
                'estado' => 'required|string|in:activo,inactivo,mantenimiento'
            ]);

            $sensor = Sensor::findOrFail($id);
            $sensor->estado = $request->estado;
            $sensor->save();

            Log::info('Estado del sensor actualizado', [
                'sensor_id' => $id,
                'nuevo_estado' => $request->estado
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Estado del sensor actualizado exitosamente',
                'sensor' => $sensor
            ]);

        } catch (\Exception $e) {
            Log::error('Error al actualizar estado del sensor: ' . $e->getMessage(), [
                'sensor_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el estado del sensor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $sensor = Sensor::findOrFail($id);
            
            // Eliminar las relaciones con componentes
            $sensor->components()->detach();
            
            // Eliminar el sensor
            $sensor->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sensor eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            Log::error('Error al eliminar sensor: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el sensor: ' . $e->getMessage()
            ], 500);
        }
    }
}
