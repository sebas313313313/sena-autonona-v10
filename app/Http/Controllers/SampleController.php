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
    public function index(Request $request)
    {
        try {
            $samples = Sample::filter($request->all())
                           ->with('sensorComponent.sensor')
                           ->latest('fecha_hora')
                           ->get();

            return response()->json([
                'message' => 'Muestras obtenidas exitosamente',
                'data' => $samples,
                'filters_applied' => array_intersect_key(
                    $request->all(),
                    array_flip(['fecha_inicio', 'fecha_fin', 'valor_minimo', 'valor_maximo', 'sensor_id'])
                )
            ]);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json([
                    'error' => 'Error al obtener muestras',
                    'debug' => [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]
                ], 500);
            }
            return response()->json(['error' => 'No se pudieron obtener las muestras. Por favor, intente más tarde'], 500);
        }
    }

    /**
     * Almacenar una nueva muestra de sensor.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'sensor_component_id' => 'required|exists:sensor_components,id',
                'fecha_hora' => 'required|date',
                'value' => 'required|numeric'
            ]);

            $sample = Sample::create($request->all());
            
            return response()->json([
                'message' => 'Muestra registrada exitosamente',
                'data' => $sample->load('sensorComponent.sensor')
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'details' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json([
                    'error' => 'Error al crear la muestra',
                    'debug' => [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]
                ], 500);
            }
            return response()->json(['error' => 'No se pudo crear la muestra. Por favor, intente más tarde'], 500);
        }
    }

    /**
     * Mostrar una muestra específica.
     */
    public function show(Sample $sample)
    {
        try {
            return response()->json([
                'message' => 'Muestra obtenida exitosamente',
                'data' => $sample->load('sensorComponent.sensor')
            ]);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json([
                    'error' => 'Error al obtener la muestra',
                    'debug' => [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]
                ], 500);
            }
            return response()->json(['error' => 'No se pudo obtener la muestra. Por favor, intente más tarde'], 500);
        }
    }

    /**
     * Actualizar una muestra específica.
     */
    public function update(Request $request, Sample $sample)
    {
        try {
            $request->validate([
                'sensor_component_id' => 'exists:sensor_components,id',
                'fecha_hora' => 'date',
                'value' => 'numeric'
            ]);

            $sample->update($request->all());
            
            return response()->json([
                'message' => 'Muestra actualizada exitosamente',
                'data' => $sample->load('sensorComponent.sensor')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'details' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json([
                    'error' => 'Error al actualizar la muestra',
                    'debug' => [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]
                ], 500);
            }
            return response()->json(['error' => 'No se pudo actualizar la muestra. Por favor, intente más tarde'], 500);
        }
    }

    /**
     * Eliminar una muestra específica.
     */
    public function destroy(Sample $sample)
    {
        try {
            $sample->delete();
            return response()->json([
                'message' => 'Muestra eliminada exitosamente'
            ]);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json([
                    'error' => 'Error al eliminar la muestra',
                    'debug' => [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]
                ], 500);
            }
            return response()->json(['error' => 'No se pudo eliminar la muestra. Por favor, intente más tarde'], 500);
        }
    }
}
