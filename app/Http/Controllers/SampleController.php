<?php

namespace App\Http\Controllers;

use App\Models\Sample;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SampleController extends Controller
{
    /**
     * Mostrar todas las muestras de sensores.
     */
    public function index()
    {
        try {
            $samples = Sample::with('sensorComponent')->get();
            return response()->json(['data' => $samples]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener las muestras', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Almacenar una nueva muestra de sensor.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'sensor_component_id' => 'required|exists:sensor_components,id',
                'fecha_hora' => 'required|date',
                'value' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $sample = Sample::create($request->all());
            return response()->json([
                'message' => 'Muestra registrada exitosamente',
                'data' => $sample->load('sensorComponent')
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear la muestra', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Mostrar una muestra específica.
     */
    public function show(Sample $sample)
    {
        try {
            return response()->json([
                'data' => $sample->load('sensorComponent')
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener la muestra', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Actualizar una muestra específica.
     */
    public function update(Request $request, Sample $sample)
    {
        try {
            $validator = Validator::make($request->all(), [
                'sensor_component_id' => 'exists:sensor_components,id',
                'fecha_hora' => 'date',
                'value' => 'integer'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $sample->update($request->all());
            return response()->json([
                'message' => 'Muestra actualizada exitosamente',
                'data' => $sample->load('sensorComponent')
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar la muestra', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Eliminar una muestra específica.
     */
    public function destroy(Sample $sample)
    {
        try {
            $sample->delete();
            return response()->json(['message' => 'Muestra eliminada exitosamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar la muestra', 'details' => $e->getMessage()], 500);
        }
    }
}
