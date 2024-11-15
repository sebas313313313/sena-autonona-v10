<?php

namespace App\Http\Controllers;

use App\Models\Component;
use Illuminate\Http\Request;

class ComponentController extends Controller
{
    /* En el metodo INDEX es por donde vamos a recibir todos los questions/preguntas que estan en nuestra bd. */
    public function index()
    {
        $component = Component::all();
        return response()->json($component);
    }
    /* En el metodo CREATE es por donde vamos a ingresar nuestro nuevo questions/preguntas y guardarlo en la bd. */
    public function create(Request $request)
    {
        $request->validate([
        'description' => 'required|string|max:100',
        ]);

        $component = Component::create($request->all());
        return response()->json(['message' => "Registro Creado Exitosamente", $component]);
    }
    /* En el metodo SHOW es por donde vamos a mostrar un questions/preguntas especifico alojado en nuestra bd. */
    public function show($id)
    {
        $component = Component::findOrFail($id);
        return response()->json(['message' => "Registgro Enseñado Exitosamente", $component]);
    }
    /* En el metodo UPDATE es donde actualizamos el questions/preguntas especifico alojado en nuestra bd. */
    public function update(Request $request, Component $component)
    {
        $request->validate([
        'description' => 'required|string|max:100',
        ]);

        $component->update($request->all());
        return response()->json(['message' => "Registro Actualizado Exitosamente", $component]);
    }
    /* Con el metodo DESTROY eliminamos cualquier questions/preguntas especifico alojado en nuestra bd. */
    public function destroy(Component $component)
    {
        $component->delete();
        return response()->json(['message' => "Registro Elimiinado Exitosamente", $component]);
    }
}
