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
        return view('municipalities.index', compact('municipalities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('municipalities.create');
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

        Municipality::create($request->all());
        return redirect()->route('municipalities.index')->with('success', 'Municipality created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Municipality $municipality)
    {
        return view('municipalities.show', compact('municipality'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Municipality $municipality)
    {
        return view('municipalities.edit', compact('municipality'));
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
        return redirect()->route('municipalities.index')->with('success', 'Municipality updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Municipality $municipality)
    {
        $municipality->delete();
        return redirect()->route('municipalities.index')->with('success', 'Municipality deleted successfully.');
    }
}