<?php

namespace App\Http\Controllers;

use App\Models\Component_Task;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Obtener el ID de la granja de la sesión
        $farm_id = session('current_farm_id');
        if (!$farm_id) {
            return redirect()->back()->with('error', 'No se pudo identificar la granja actual');
        }

        // Obtener las tareas de la granja actual
        $tasks = Component_Task::whereHas('farmComponent', function($query) use ($farm_id) {
            $query->where('farm_id', $farm_id);
        })->with(['user', 'farmComponent'])->get();

        return view('dashboard.tasks.index', compact('tasks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $job = Job::create($request->all());
        return response()->json([
            'message' => 'Trabajo creado exitosamente',
            'data' => $job
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Job $job)
    {
        return response()->json([
            'data' => $job->load('componentTasks')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Job $job)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $job->update($request->all());
        return response()->json([
            'message' => 'Trabajo actualizado exitosamente',
            'data' => $job
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Job $job)
    {
        $job->delete();
        return response()->json([
            'message' => 'Trabajo eliminado exitosamente'
        ]);
    }
}
