<?php

namespace App\Http\Controllers;

use App\Models\Component_Task;
use App\Models\Farm;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        // Obtener el ID de la granja de la sesión
        $farm_id = session('current_farm_id');
        if (!$farm_id) {
            return redirect()->back()->with('error', 'No se pudo identificar la granja actual');
        }

        // Obtener la granja con sus usuarios y tareas
        $farm = Farm::with(['users' => function($query) {
            $query->wherePivot('role', 'operario');
        }, 'tasks.user'])->findOrFail($farm_id);

        return view('dashboard.tasks.index', compact('farm'));
    }

    public function store(Request $request)
    {
        $farm_id = session('current_farm_id');
        if (!$farm_id) {
            return redirect()->back()->with('error', 'Por favor, selecciona una granja primero.');
        }
        
        $farm = Farm::findOrFail($farm_id);
        
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'time' => 'required',
            'comments' => 'required|string',
            'status' => 'required|boolean'
        ]);

        // Verificar que el usuario es operario de esta granja
        $user = $farm->users()->wherePivot('role', 'operario')
                     ->where('users.id', $request->user_id)
                     ->firstOrFail();

        $task = Component_Task::create([
            'user_id' => $request->user_id,
            'date' => $request->date,
            'time' => $request->time,
            'comments' => $request->comments,
            'status' => $request->status,
            'farm_component_id' => $farm_id
        ]);

        return redirect()->back()->with('success', 'Tarea asignada exitosamente');
    }

    public function update(Request $request, Component_Task $task)
    {
        // Verificar que la tarea pertenece a la granja actual
        if ($task->farm_component_id != session('current_farm_id')) {
            abort(403);
        }

        $task->update(['status' => !$task->status]);
        return redirect()->back()->with('success', 'Estado de la tarea actualizado');
    }

    public function destroy(Component_Task $task)
    {
        // Verificar que la tarea pertenece a la granja actual
        if ($task->farm_component_id != session('current_farm_id')) {
            abort(403);
        }

        $task->delete();
        return redirect()->back()->with('success', 'Tarea eliminada exitosamente');
    }
}
