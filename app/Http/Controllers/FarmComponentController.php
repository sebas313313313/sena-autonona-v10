<?php

namespace App\Http\Controllers;

use App\Models\Farm_Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FarmComponentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $farm_components = Farm_Component::filter($request->all())->with(['farm', 'component'])->get();
        return response()->json(['data' => $farm_components]);
    }

    /**
     * Create a new resource.
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|string',
            'farm_id' => 'required|exists:farms,id',
            'component_id' => 'required|exists:components,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $farm_component = Farm_Component::create($request->all());
        return response()->json([
            'message' => 'Componente de granja creado exitosamente',
            'data' => $farm_component->load(['farm', 'component'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $farm_component = Farm_Component::with(['farm', 'component'])->findOrFail($id);
        return response()->json(['data' => $farm_component]);
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, Farm_Component $farm_component)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'sometimes|required|string',
            'farm_id' => 'sometimes|required|exists:farms,id',
            'component_id' => 'sometimes|required|exists:components,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $farm_component->update($request->all());
        return response()->json([
            'message' => 'Componente de granja actualizado exitosamente',
            'data' => $farm_component->load(['farm', 'component'])
        ]);
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Farm_Component $farm_component)
    {
        $farm_component->delete();
        return response()->json([
            'message' => 'Componente de granja eliminado exitosamente'
        ]);
    }
}
