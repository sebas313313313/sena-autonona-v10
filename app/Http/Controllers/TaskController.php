<?php

namespace App\Http\Controllers;

use App\Models\Component_Task;
use App\Models\Farm;
use App\Models\Farm_Component;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index($farm_id)
    {
        // Obtener la granja
        $farm = Farm::with(['users' => function($query) {
            $query->wherePivot('role', 'operario');
        }])->findOrFail($farm_id);

        // Guardar el farm_id en la sesión
        session(['current_farm_id' => $farm_id]);

        // Obtener los componentes de la granja para el formulario
        $components = Farm_Component::where('farm_id', $farm_id)->get();
        
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
                'components' => $components,
                'isOperario' => true
            ]);
        }
        
        // Si es admin, mostrar todas las tareas con su estado
        $tasks = Component_Task::whereHas('farmComponent', function($query) use ($farm_id) {
            $query->where('farm_id', $farm_id);
        })
        ->with('user')
        ->get();

        return view('dashboard.tasks.index', [
            'tasks' => $tasks,
            'farm' => $farm,
            'components' => $components,
            'isOperario' => false
        ]);
    }

    public function store(Request $request, $farm_id)
    {
        try {
            // Solo los administradores pueden crear tareas
            if (session('farm_role') !== 'admin') {
                return redirect()->back()->with('error', 'No tienes permiso para crear tareas.');
            }

            $farm = Farm::findOrFail($farm_id);
            
            // Obtener el farm_component_id de la granja actual
            $farmComponent = Farm_Component::where('farm_id', $farm_id)->first();
            
            if (!$farmComponent) {
                return redirect()->back()->with('error', 'Esta granja no tiene un tipo asignado.');
            }
            
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'date' => 'required|date',
                'time' => 'required',
                'comments' => 'required|string',
                'status' => 'required|boolean'
            ], [
                'user_id.required' => 'Por favor, selecciona un operario.',
                'date.required' => 'La fecha es requerida.',
                'time.required' => 'La hora es requerida.',
                'comments.required' => 'La descripción de la tarea es requerida.'
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
                'status' => false, // Estado inicial como 'sin completar'
                'farm_component_id' => $farmComponent->id // Usar el componente de la granja actual
            ]);

            return redirect()->back()->with('success', '¡Tarea asignada exitosamente al operario!');
            
        } catch (\Exception $e) {
            \Log::error('Error al crear tarea: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Hubo un error al asignar la tarea. Por favor, inténtalo de nuevo.');
        }
    }

    public function update(Request $request, $farm_id, Component_Task $task)
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

    public function destroy($farm_id, $task_id)
    {
        // Solo los administradores pueden eliminar tareas
        if (session('farm_role') !== 'admin') {
            abort(403, 'No tienes permiso para eliminar tareas.');
        }

        $task = Component_Task::findOrFail($task_id);
        $task->delete();
        return redirect()->back()->with('success', 'Tarea eliminada exitosamente');
    }
}
