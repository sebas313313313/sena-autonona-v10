<?php

namespace App\Http\Controllers;

use App\Models\Component_Task;
use Illuminate\Http\Request;

class ComponentTaskController extends Controller
{
    /* En el metodo INDEX es por donde vamos a recibir todos los questions/preguntas que estan en nuestra bd. */
    public function index()
    {
        $component_task = Component_Task::all();
        return response()->json($component_task);
    }
    /* En el metodo CREATE es por donde vamos a ingresar nuestro nuevo questions/preguntas y guardarlo en la bd. */
    public function create(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'time' => 'required|time',
            'status' => 'required|string|max:50',
            'comments' => 'required|text|nullable',
            'date' => 'required|index',
            'status' => 'required|index',
            'job_id' => 'required|exists:jobs,id',
            'farm_component_id' => 'required|exists:farm_components,id',
            'user_id' => 'required|exists:users,id'
        ]);

        $component_task = Component_Task::create($request->all());
        return response()->json(['message' => "Tarea Creada Exitosamente", $component_task]);
    }
    /* En el metodo SHOW es por donde vamos a mostrar un questions/preguntas especifico alojado en nuestra bd. */
    public function show($id)
    {
        $component_task = Component_Task::findOrFail($id);
        return response()->json(['message' => "Tarea Enseñada Exitosamente", $component_task]);
    }
    /* En el metodo UPDATE es donde actualizamos el questions/preguntas especifico alojado en nuestra bd. */
    public function update(Request $request, Component_Task $component_task)
    {
        $request->validate([
            'date' => 'required|date',
            'time' => 'required|time',
            'status' => 'required|string|max:50',
            'comments' => 'required|text|nullable',
            'date' => 'required|index',
            'status' => 'required|index',
            'job_id' => 'required|exists:jobs,id',
            'farm_component_id' => 'required|exists:farm_components,id',
            'user_id' => 'required|exists:users,id'
        ]);

        $component_task->update($request->all());
        return response()->json(['message' => "Tarea Actualizada Exitosamente", $component_task]);
    }
    /* Con el metodo DESTROY eliminamos cualquier questions/preguntas especifico alojado en nuestra bd. */
    public function destroy(Component_Task $component_task)
    {
        $component_task->delete();
        return response()->json(['message' => "Tarea Elimiinada Exitosamente", $component_task]);
    }
}
