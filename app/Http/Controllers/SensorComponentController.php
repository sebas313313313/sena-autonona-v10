<?php

namespace App\Http\Controllers;

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
}
