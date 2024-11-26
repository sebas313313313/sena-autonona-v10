<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Controlador para gestionar los sensores del sistema
 * Este controlador maneja todas las operaciones CRUD relacionadas con los sensores
 */
class SensorController extends Controller
{
    /**
     * Muestra una lista de todos los sensores
     * @return \Illuminate\Http\JsonResponse Lista de sensores en formato JSON
     */
    public function index()
    {
        $sensors = Sensor::all();
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
}
