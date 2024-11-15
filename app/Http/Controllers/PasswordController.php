<?php

namespace App\Http\Controllers;

use App\Models\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $passwords = Password::with('userRole')->get();
        return response()->json(['data' => $passwords]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_rol_usuario' => 'required|exists:users_roles,id',
            'contrasena' => 'required|min:6',
            'pregunta' => 'required|string',
            'respuesta' => 'required|string',
            'fecha' => 'required|date'
        ]);

        $password = Password::create([
            'id_rol_usuario' => $request->id_rol_usuario,
            'contrasena' => Hash::make($request->contrasena),
            'pregunta' => $request->pregunta,
            'respuesta' => Hash::make($request->respuesta),
            'fecha' => $request->fecha
        ]);

        return response()->json([
            'data' => $password,
            'message' => 'Contraseña creada exitosamente'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Password $password)
    {
        return response()->json(['data' => $password->load('userRole')]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Password $password)
    {
        $request->validate([
            'id_rol_usuario' => 'exists:users_roles,id',
            'contrasena' => 'min:6',
            'pregunta' => 'string',
            'respuesta' => 'string',
            'fecha' => 'date'
        ]);

        $updateData = $request->only(['id_rol_usuario', 'pregunta', 'fecha']);
        
        if ($request->has('contrasena')) {
            $updateData['contrasena'] = Hash::make($request->contrasena);
        }
        
        if ($request->has('respuesta')) {
            $updateData['respuesta'] = Hash::make($request->respuesta);
        }

        $password->update($updateData);

        return response()->json([
            'data' => $password->fresh(),
            'message' => 'Contraseña actualizada exitosamente'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Password $password)
    {
        $password->delete();
        return response()->json(['message' => 'Contraseña eliminada exitosamente']);
    }

    /**
     * Verify password answer for password recovery
     */
    public function verifyAnswer(Request $request, Password $password)
    {
        $request->validate([
            'respuesta' => 'required|string'
        ]);

        if (Hash::check($request->respuesta, $password->respuesta)) {
            return response()->json(['message' => 'Respuesta correcta']);
        }

        return response()->json(['message' => 'Respuesta incorrecta'], 400);
    }
}
