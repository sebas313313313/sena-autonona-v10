<?php

namespace App\Http\Controllers;

use App\Models\Municipality;
use Illuminate\Http\Request;

class MunicipalityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $municipalities = Municipality::all();
        return response()->json(['data' => $municipalities]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // No necesario para API
        return response()->json(['message' => 'Method not allowed'], 405);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:100',
            'department' => 'nullable|string|max:50'
        ]);

        $municipality = Municipality::create($request->all());
        return response()->json(['data' => $municipality, 'message' => 'Municipality created successfully'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Municipality $municipality)
    {
        return response()->json(['data' => $municipality]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Municipality $municipality)
    {
        // No necesario para API
        return response()->json(['message' => 'Method not allowed'], 405);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Municipality $municipality)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:100',
            'department' => 'nullable|string|max:50'
        ]);

        $municipality->update($request->all());
        return response()->json(['data' => $municipality, 'message' => 'Municipality updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Municipality $municipality)
    {
        $municipality->delete();
        return response()->json(['message' => 'Municipality deleted successfully']);
    }
}
