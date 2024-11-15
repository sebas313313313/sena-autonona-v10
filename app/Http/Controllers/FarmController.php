<?php

namespace App\Http\Controllers;

use App\Models\Farm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FarmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $farms = Farm::with(['usersRole', 'municipality'])->get();
        return response()->json(['data' => $farms]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'address' => 'required|string|max:50',
            'vereda' => 'required|string|max:50',
            'extension' => 'required|string|max:50',
            'users_role_id' => 'required|exists:users_roles,id',
            'municipality_id' => 'required|exists:municipalities,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $farm = Farm::create($request->all());
        return response()->json([
            'message' => 'Granja creada exitosamente',
            'data' => $farm->load(['usersRole', 'municipality'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Farm $farm)
    {
        return response()->json([
            'data' => $farm->load(['usersRole', 'municipality'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Farm $farm)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'sometimes|required|numeric',
            'longitude' => 'sometimes|required|numeric',
            'address' => 'sometimes|required|string|max:50',
            'vereda' => 'sometimes|required|string|max:50',
            'extension' => 'sometimes|required|string|max:50',
            'users_role_id' => 'sometimes|required|exists:users_roles,id',
            'municipality_id' => 'sometimes|required|exists:municipalities,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $farm->update($request->all());
        return response()->json([
            'message' => 'Granja actualizada exitosamente',
            'data' => $farm->load(['usersRole', 'municipality'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Farm $farm)
    {
        $farm->delete();
        return response()->json([
            'message' => 'Granja eliminada exitosamente'
        ]);
    }
}
