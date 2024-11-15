<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SensorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sensors = Sensor::all();
        return response()->json(['data' => $sensors]);
    }

    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
     */
    public function show(Sensor $sensor)
    {
        return response()->json(['data' => $sensor]);
    }

    /**
     * Update the specified resource in storage.
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
     * Remove the specified resource.
     */
    public function destroy(Sensor $sensor)
    {
        $sensor->delete();
        return response()->json([
            'message' => 'Sensor eliminado exitosamente'
        ]);
    }
}
