<?php

namespace App\Http\Controllers;

use App\Models\Component;
use App\Models\Sensor;
use App\Models\Sensor_Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SensorComponentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sensor_components = Sensor_Component::with(['sensor', 'farmComponent'])->get();
        return response()->json(['data' => $sensor_components]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'farm_component_id' => 'required|exists:farm_components,id',
            'description' => 'required',
            'farm_type' => 'required',
            'estado' => 'required',
            'min' => 'required|numeric',
            'max' => 'required|numeric|gt:min'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Crear una nueva instancia de sensor para la granja
        $sensor = Sensor::create([
            'description' => $request->input('description'),
            'farm_type' => $request->input('farm_type'),
            'estado' => $request->input('estado')
        ]);

        // Asociar el nuevo sensor al componente de la granja
        $sensor_component = Sensor_Component::create([
            'farm_component_id' => $request->input('farm_component_id'),
            'sensor_id' => $sensor->id,
            'min' => $request->input('min'),
            'max' => $request->input('max')
        ]);

        return response()->json([
            'message' => 'Componente de sensor creado exitosamente',
            'data' => $sensor_component->load(['sensor', 'farmComponent'])
        ], 201);
    }

    /**
     * Create a new resource.
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'farm_component_id' => 'required|exists:farm_components,id',
            'sensor_id' => 'required|exists:sensors,id',
            'min' => 'required|numeric',
            'max' => 'required|numeric|gt:min'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $sensor_component = Sensor_Component::create($request->all());
        return response()->json([
            'message' => 'Componente de sensor creado exitosamente',
            'data' => $sensor_component->load(['sensor', 'farmComponent'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $sensor_component = Sensor_Component::with(['sensor', 'farmComponent'])->findOrFail($id);
        return response()->json(['data' => $sensor_component]);
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, Sensor_Component $sensor_component)
    {
        $validator = Validator::make($request->all(), [
            'farm_component_id' => 'sometimes|required|exists:farm_components,id',
            'sensor_id' => 'sometimes|required|exists:sensors,id',
            'min' => 'sometimes|required|numeric',
            'max' => 'sometimes|required|numeric|gt:min'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $sensor_component->update($request->all());
        return response()->json([
            'message' => 'Componente de sensor actualizado exitosamente',
            'data' => $sensor_component->load(['sensor', 'farmComponent'])
        ]);
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Sensor_Component $sensor_component)
    {
        $sensor_component->delete();
        return response()->json([
            'message' => 'Componente de sensor eliminado exitosamente'
        ]);
    }

    /**
     * Get sensors for a specific component
     */
    public function getSensorsByComponent(Component $component)
    {
        $sensors = Sensor_Component::where('farm_component_id', $component->id)
            ->with('sensor')
            ->get()
            ->map(function($sensor_component) {
                return [
                    'id' => $sensor_component->sensor->id,
                    'description' => $sensor_component->sensor->description,
                    'min' => $sensor_component->min,
                    'max' => $sensor_component->max
                ];
            });

        return response()->json(['data' => $sensors]);
    }
}
