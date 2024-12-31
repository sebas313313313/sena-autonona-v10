<?php

namespace App\Http\Controllers;

use App\Models\Component;
use App\Models\Sensor;
use Illuminate\Http\Request;

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
     * @param Component $component Componente al que pertenece el sensor
     * @return \Illuminate\Http\JsonResponse Respuesta con el sensor creado
     */
    public function store(Request $request, Component $component)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'unit' => 'required|string|max:50',
            ]);

            $sensor = $component->sensors()->create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Sensor creado exitosamente',
                'sensor' => $sensor
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al crear sensor: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el sensor: ' . $e->getMessage()
            ], 500);
        }
    }
}
