<?php

namespace App\Http\Controllers;

use App\Models\Users_Role;
use App\Models\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class Users_RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usersRoles = Users_Role::with(['User', 'Identification_Type', 'Municipality'])->get();
        return response()->json(['data' => $usersRoles]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identification' => 'required|string|max:15|unique:users_roles',
            'name' => 'required|string|max:50',
            'Last_name' => 'required|string|max:50',
            'date_birth' => 'required|date',
            'direction' => 'required|string|max:50',
            'contact' => 'required|string|max:10',
            'user_id' => 'required|exists:users,id',
            'identification_type_id' => 'required|exists:identification_types,id',
            'municipality_id' => 'required|exists:municipalities,id',
            // Campos para la contraseña
            'password' => 'required|min:6',
            'password_question' => 'required|string',
            'password_answer' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Crear el rol de usuario
        $userRole = Users_Role::create($request->only([
            'identification',
            'name',
            'Last_name',
            'date_birth',
            'direction',
            'contact',
            'user_id',
            'identification_type_id',
            'municipality_id'
        ]));

        // Crear la contraseña asociada
        Password::create([
            'users_role_id' => $userRole->id,
            'contrasena' => Hash::make($request->password),
            'pregunta' => $request->password_question,
            'respuesta' => Hash::make($request->password_answer),
            'fecha' => now()
        ]);

        return response()->json([
            'message' => 'Usuario y contraseña creados exitosamente',
            'data' => $userRole->load(['User', 'Identification_Type', 'Municipality'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Users_Role $users_role)
    {
        return response()->json([
            'data' => $users_role->load(['User', 'Identification_Type', 'Municipality'])
        ]);
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, Users_Role $users_role)
    {
        $validator = Validator::make($request->all(), [
            'identification' => 'string|max:15|unique:users_roles,identification,' . $users_role->id,
            'name' => 'string|max:50',
            'Last_name' => 'string|max:50',
            'date_birth' => 'date',
            'direction' => 'string|max:50',
            'contact' => 'string|max:10',
            'user_id' => 'exists:users,id',
            'identification_type_id' => 'exists:identification_types,id',
            'municipality_id' => 'exists:municipalities,id',
            // Campos opcionales para la contraseña
            'password' => 'min:6',
            'password_question' => 'string',
            'password_answer' => 'string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $users_role->update($request->only([
            'identification',
            'name',
            'Last_name',
            'date_birth',
            'direction',
            'contact',
            'user_id',
            'identification_type_id',
            'municipality_id'
        ]));

        // Actualizar contraseña si se proporcionó
        if ($request->filled('password')) {
            $password = Password::where('users_role_id', $users_role->id)->first();
            if ($password) {
                $updateData = [
                    'contrasena' => Hash::make($request->password),
                    'fecha' => now()
                ];
                
                if ($request->filled('password_question')) {
                    $updateData['pregunta'] = $request->password_question;
                }
                
                if ($request->filled('password_answer')) {
                    $updateData['respuesta'] = Hash::make($request->password_answer);
                }
                
                $password->update($updateData);
            }
        }

        return response()->json([
            'message' => 'Usuario actualizado exitosamente',
            'data' => $users_role->load(['User', 'Identification_Type', 'Municipality'])
        ]);
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Users_Role $users_role)
    {
        // La contraseña se eliminará automáticamente por la relación onDelete('cascade')
        $users_role->delete();
        return response()->json(['message' => 'Usuario eliminado exitosamente']);
    }

    /**
     * Validate user password
     */
    public function validatePassword(Request $request, Users_Role $users_role)
    {
        $request->validate([
            'password' => 'required|string'
        ]);

        $password = Password::where('users_role_id', $users_role->id)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$password) {
            return response()->json(['message' => 'No se encontró contraseña para este usuario'], 404);
        }

        if (Hash::check($request->password, $password->contrasena)) {
            return response()->json(['message' => 'Contraseña válida']);
        }

        return response()->json(['message' => 'Contraseña inválida'], 401);
    }
}
