<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\SensorData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

/**
 * Controlador para gestionar los sensores del sistema
 * Este controlador maneja todas las operaciones CRUD relacionadas con los sensores
 */
class SensorController extends Controller
{
    /**
     * Muestra una lista de todos los sensores
     * @param Request $request Parámetros de búsqueda
     * @return \Illuminate\Http\JsonResponse Lista de sensores en formato JSON
     */
    public function index(Request $request)
    {
        \Log::info('Request type:', ['type' => $request->type]);
        
        $query = Sensor::query();

        // Filtrar por tipo si se especifica
        if ($request->has('type')) {
            $type = $request->type;
            \Log::info('Filtering by type:', ['type' => $type]);
            $query->where('farm_type', $type);
        }

        $sensors = $query->get();
        \Log::info('Sensors found:', ['count' => $sensors->count(), 'sensors' => $sensors->toArray()]);
        
        return response()->json(['data' => $sensors]);
    }

    /**
     * Almacena un nuevo sensor en la base de datos
     * @param Request $request Datos del nuevo sensor
     * @return \Illuminate\Http\JsonResponse Respuesta con el sensor creado
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $sensor = Sensor::create($request->all());
        return response()->json([
            'message' => 'Sensor creado exitosamente',
            'data' => $sensor
        ], 201);
    }

    /**
     * Muestra los detalles de un sensor específico
     * @param Sensor $sensor El sensor a mostrar
     * @return \Illuminate\Http\JsonResponse Datos del sensor en formato JSON
     */
    public function show(Sensor $sensor)
    {
        return response()->json(['data' => $sensor]);
    }

    /**
     * Actualiza la información de un sensor existente
     * @param Request $request Nuevos datos del sensor
     * @param Sensor $sensor El sensor a actualizar
     * @return \Illuminate\Http\JsonResponse Respuesta con el sensor actualizado
     */
    public function update(Request $request, Sensor $sensor)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $sensor->update($request->all());
        return response()->json([
            'message' => 'Sensor actualizado exitosamente',
            'data' => $sensor
        ]);
    }

    /**
     * Elimina un sensor del sistema
     * @param Sensor $sensor El sensor a eliminar
     * @return \Illuminate\Http\JsonResponse Mensaje de confirmación
     */
    public function destroy(Sensor $sensor)
    {
        $sensor->delete();
        return response()->json([
            'message' => 'Sensor eliminado exitosamente'
        ]);
    }

    /**
     * Actualiza el estado de un sensor
     * @param Request $request Datos del nuevo estado
     * @param int $id ID del sensor a actualizar
     * @return \Illuminate\Http\JsonResponse Respuesta con el resultado de la actualización
     */
    public function updateEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:0,1,2'
        ]);

        $sensor = Sensor::findOrFail($id);
        $sensor->estado = $request->estado;
        $sensor->save();

        $estados = [
            '0' => 'Inactivo',
            '1' => 'Activo',
            '2' => 'En Mantenimiento'
        ];

        return response()->json([
            'success' => true,
            'message' => 'Estado del sensor actualizado a ' . $estados[$request->estado]
        ]);
    }
}
