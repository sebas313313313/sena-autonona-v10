<?php

namespace App\Http\Controllers;

use App\Models\Identification_Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IdentificationTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $types = Identification_Type::all();
        return response()->json(['data' => $types]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $type = Identification_Type::create($request->all());
        return response()->json([
            'message' => 'Tipo de identificación creado exitosamente',
            'data' => $type
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Identification_Type $identification_type)
    {
        return response()->json(['data' => $identification_type]);
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, Identification_Type $identification_type)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $identification_type->update($request->all());
        return response()->json([
            'message' => 'Tipo de identificación actualizado exitosamente',
            'data' => $identification_type
        ]);
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Identification_Type $identification_type)
    {
        $identification_type->delete();
        return response()->json(['message' => 'Tipo de identificación eliminado exitosamente']);
    }
}
