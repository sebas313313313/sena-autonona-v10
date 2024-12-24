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

        // Obtener la granja
        $farm = Farm::with(['users' => function($query) {
            $query->wherePivot('role', 'operario');
        }])->findOrFail($farm_id);
        
        // Si es operario, solo mostrar sus tareas
        if (session('farm_role') === 'operario') {
            $tasks = Component_Task::where('user_id', auth()->id())
                                 ->whereHas('farmComponent', function($query) use ($farm_id) {
                                     $query->where('farm_id', $farm_id);
                                 })
                                 ->with('user')
                                 ->get();
            
            return view('dashboard.tasks.index', [
                'tasks' => $tasks,
                'farm' => $farm,
                'isOperario' => true
            ]);
        }
        
        // Si es admin, mostrar todas las tareas
        $farm->load('tasks.user');
        
        return view('dashboard.tasks.index', [
            'farm' => $farm,
            'isOperario' => false
        ]);
    }

    public function store(Request $request)
    {
        // Solo los administradores pueden crear tareas
        if (session('farm_role') !== 'admin') {
            abort(403, 'No tienes permiso para crear tareas.');
        }

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
            'farm_component_id' => $request->farm_component_id
        ]);

        return redirect()->back()->with('success', 'Tarea creada exitosamente');
    }

    public function update(Request $request, Component_Task $task)
    {
        // Verificar que el usuario tenga permiso para actualizar esta tarea
        if (session('farm_role') !== 'admin' && $task->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para actualizar esta tarea.');
        }

        $request->validate([
            'status' => 'required|boolean'
        ]);

        $task->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Estado de la tarea actualizado');
    }

    public function destroy(Component_Task $task)
    {
        // Solo los administradores pueden eliminar tareas
        if (session('farm_role') !== 'admin') {
            abort(403, 'No tienes permiso para eliminar tareas.');
        }

        $task->delete();
        return redirect()->back()->with('success', 'Tarea eliminada exitosamente');
    }
}
