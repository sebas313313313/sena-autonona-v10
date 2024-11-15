<?php

namespace App\Http\Controllers;

use App\Models\Calibration;
use Illuminate\Http\Request;

class CalibrationController extends Controller
{
    /* En el metodo INDEX es por donde vamos a recibir todos los questions/preguntas que estan en nuestra bd. */
    public function index()
    {
        $calibration = Calibration::all();
        return response()->json($calibration);
    }
    /* En el metodo CREATE es por donde vamos a ingresar nuestro nuevo questions/preguntas y guardarlo en la bd. */
    public function create (Request $request)
    {
        $request->validate([
            'date' => 'required|date|nullable',
            'parameters' => 'required|integer|nullable',
            'alert' => 'required|integer',
            'sensor_component_id' => 'required|exists:sensor_components,id'
        ]);

        $calibration = Calibration::create($request->all());
        return response()->json(['message' => "Registro Creado Exitosamente", $calibration]);
    }
    /* En el metodo SHOW es por donde vamos a mostrar un questions/preguntas especifico alojado en nuestra bd. */
    public function show($id)
    {
        $calibration = Calibration::findOrFail($id);
        return response()->json(['message' => "Registgro Enseñado Exitosamente", $calibration]);
    }
    /* En el metodo UPDATE es donde actualizamos el questions/preguntas especifico alojado en nuestra bd. */
    public function update(Request $request, Calibration $calibration)
    {
        $request->validate([
            'date' => 'required|date|nullable',
            'parameters' => 'required|integer|nullable',
            'alert' => 'required|integer',
            'sensor_component_id' => 'required|exists:sensor_components,id'
        ]);

        $calibration->update($request->all());
        return response()->json(['message' => "Registro Actualizado Exitosamente", $calibration]);
    }
    /* Con el metodo DESTROY eliminamos cualquier questions/preguntas especifico alojado en nuestra bd. */
    public function destroy(Calibration $calibration)
    {
        $calibration->delete();
        return response()->json(['message' => "Registro Elimiinado Exitosamente", $calibration]);
    }
}
