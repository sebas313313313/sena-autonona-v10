<?php

namespace App\Http\Controllers;

use App\Models\Component_Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ComponentTaskController extends Controller
{
    /**
     * Mostrar todas las tareas de componentes
     */
    public function index(Request $request)
    {
        $component_tasks = Component_Task::filter(($request->all()))->with(['user', 'job', 'farmComponent'])->get();
        return response()->json($component_tasks);
    }

    /**
     * Crear una nueva tarea de componente
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'time' => 'required|date_format:H:i:s',
            'status' => 'required|string|max:50',
            'comments' => 'nullable|string',
            'job_id' => 'required|exists:jobs,id',
            'farm_component_id' => 'required|exists:farm_components,id',
            'user_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $component_task = Component_Task::create($request->all());
        return response()->json([
            'message' => 'Tarea creada exitosamente',
            'data' => $component_task->load(['user', 'job', 'farmComponent'])
        ], 201);
    }

    /**
     * Mostrar una tarea de componente específica
     */
    public function show($id)
    {
        $component_task = Component_Task::with(['user', 'job', 'farmComponent'])->findOrFail($id);
        return response()->json($component_task);
    }

    /**
     * Actualizar una tarea de componente
     */
    public function update(Request $request, Component_Task $component_task)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'sometimes|required|date',
            'time' => 'sometimes|required|date_format:H:i:s',
            'status' => 'sometimes|required|string|max:50',
            'comments' => 'nullable|string',
            'job_id' => 'sometimes|required|exists:jobs,id',
            'farm_component_id' => 'sometimes|required|exists:farm_components,id',
            'user_id' => 'sometimes|required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $component_task->update($request->all());
        return response()->json([
            'message' => 'Tarea actualizada exitosamente',
            'data' => $component_task->load(['user', 'job', 'farmComponent'])
        ]);
    }

    /**
     * Eliminar una tarea de componente
     */
    public function destroy(Component_Task $component_task)
    {
        $component_task->delete();
        return response()->json([
            'message' => 'Tarea eliminada exitosamente'
        ]);
    }
}
