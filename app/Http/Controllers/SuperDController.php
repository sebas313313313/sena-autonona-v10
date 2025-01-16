<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Users_Role;
use App\Models\Farm;
use App\Models\Component;
use App\Models\Farm_Component;
use App\Models\Sensor_Component;
use App\Models\Sensor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SuperDController extends Controller
{
    public function showLoginForm()
    {
        return view('superD.blank');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Verificar si es el usuario especial de SuperD
        $user = User::where('email', $credentials['email'])->first();
        
        if (!$user || $user->email !== 'super.d@example.com') {
            return back()->with('error', 'Acceso no autorizado.');
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('superD.dashboard')
                ->with('success', '¡Bienvenido a SuperD!');
        }

        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Las credenciales proporcionadas no son correctas.');
    }

    public function dashboard()
    {
        try {
            $users = User::all();
            $farms = Farm::with(['municipality', 'usersRole.user'])->get();
            $components = Component::all();
            
            Log::info('Número total de usuarios encontrados: ' . $users->count());
            Log::info('Número total de granjas encontradas: ' . $farms->count());
            Log::info('Número total de componentes encontrados: ' . $components->count());
            
            return view('superD.dashboard', compact('users', 'farms', 'components'));
        } catch (\Exception $e) {
            Log::error('Error en dashboard SuperD: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return view('superD.dashboard')->with('error', 'Error al cargar los datos: ' . $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('superD.login');
    }

    public function deleteComponent(Component $component)
    {
        try {
            $component->delete();
            return response()->json([
                'success' => true,
                'message' => 'Componente eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            Log::error('Error al eliminar componente: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el componente'
            ], 500);
        }
    }

    public function getComponentSensors(Component $component)
    {
        try {
            // Mapear los nombres de componentes a farm_type
            $farmTypeMap = [
                'Acuaponia' => 'acuaponica',
                'Hidroponia' => 'hidroponica',
                'Sistema de Riego' => 'riego',
                'Sistema de Vigilancia' => 'vigilancia'
            ];
            
            // Obtener el farm_type correspondiente
            $farmType = $farmTypeMap[$component->description] ?? null;
            
            if (!$farmType) {
                return response()->json([
                    'success' => true,
                    'sensors' => []
                ]);
            }
            
            // Obtener los sensores que coincidan con el tipo de granja
            $sensors = Sensor::where('farm_type', $farmType)->get();
            
            return response()->json([
                'success' => true,
                'sensors' => $sensors
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener sensores del componente: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los sensores: ' . $e->getMessage()
            ], 500);
        }
    }

    public function changePassword(Request $request, $id)
    {
        try {
            $request->validate([
                'password' => 'required|min:6'
            ]);

            $user = User::findOrFail($id);
            $user->password = bcrypt($request->password);
            $user->save();

            return response()->json(['message' => 'Contraseña actualizada exitosamente']);
        } catch (\Exception $e) {
            Log::error('Error al cambiar la contraseña: ' . $e->getMessage());
            return response()->json(['error' => 'Error al cambiar la contraseña'], 500);
        }
    }

    public function createSuperD(Request $request)
    {
        try {
            $request->validate([
                'email' => [
                    'required',
                    'email',
                    'unique:users,email'
                ],
                'password' => [
                    'required',
                    'min:6'
                ]
            ], [
                'email.required' => 'El correo electrónico es obligatorio',
                'email.email' => 'El correo electrónico debe ser válido',
                'email.unique' => 'Este correo electrónico ya está registrado',
                'password.required' => 'La contraseña es obligatoria',
                'password.min' => 'La contraseña debe tener al menos 6 caracteres'
            ]);

            DB::beginTransaction();

            $user = new User();
            $user->name = 'SuperD';
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();

            // Crear el registro en users_roles con valores por defecto
            $userRole = new Users_Role();
            $userRole->user_id = $user->id;
            $userRole->role = 'SuperD';
            $userRole->identification = 'SD' . str_pad($user->id, 5, '0', STR_PAD_LEFT);
            $userRole->name = 'SuperD';
            $userRole->Last_name = 'Administrator';
            $userRole->date_birth = now();
            $userRole->direction = 'Sistema';
            $userRole->contact = '0000000000';
            // Obtener el primer tipo de identificación
            $identificationType = \App\Models\Identification_Type::first();
            $userRole->identification_type_id = $identificationType->id;
            // Obtener el primer municipio
            $municipality = \App\Models\Municipality::first();
            $userRole->municipality_id = $municipality->id;
            $userRole->save();

            DB::commit();
            return response()->json(['message' => 'SuperD creado exitosamente']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Error de validación al crear SuperD: ' . $e->getMessage());
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear SuperD: ' . $e->getMessage());
            return response()->json(['error' => 'Error al crear SuperD: ' . $e->getMessage()], 500);
        }
    }

    public function deleteUser($id)
    {
        try {
            DB::beginTransaction();

            // Verificar que el usuario existe
            $user = User::findOrFail($id);

            // No permitir eliminar al SuperD principal
            if ($user->email === 'super.d@example.com') {
                return response()->json(['error' => 'No se puede eliminar al SuperD principal'], 403);
            }

            // Eliminar el rol del usuario
            Users_Role::where('user_id', $id)->delete();

            // Eliminar el usuario
            $user->delete();

            DB::commit();
            return response()->json(['message' => 'Usuario eliminado exitosamente']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar usuario: ' . $e->getMessage());
            return response()->json(['error' => 'Error al eliminar usuario'], 500);
        }
    }

    public function getUsers()
    {
        try {
            $users = User::all();
            return response()->json($users);
        } catch (\Exception $e) {
            Log::error('Error al obtener usuarios: ' . $e->getMessage());
            return response()->json(['error' => 'Error al obtener usuarios'], 500);
        }
    }

    public function getUserDetails($id)
    {
        try {
            // Obtener el usuario con sus relaciones
            $user = User::with([
                'roles',
                'identification_type',
                'security_questions' => function($query) {
                    $query->select('security_questions.id', 'security_questions.question');
                }
            ])->findOrFail($id);

            // Formatear la información para mostrar
            $userDetails = [
                'id' => $user->id,
                'name' => $user->name ?? 'No especificado',
                'email' => $user->email ?? 'No especificado',
                'identification_type' => optional($user->identification_type)->name ?? 'No especificado',
                'identification_number' => $user->identification_number ?? 'No especificado',
                'phone' => $user->phone ?? 'No especificado',
                'address' => $user->address ?? 'No especificada',
                'created_at' => optional($user->created_at)->format('d/m/Y H:i:s') ?? 'No especificado',
                'roles' => $user->roles->pluck('name')->implode(', ') ?: 'Sin roles asignados',
                'security_questions' => []
            ];

            // Agregar preguntas de seguridad si existen
            if ($user->security_questions) {
                foreach ($user->security_questions as $question) {
                    $userDetails['security_questions'][] = [
                        'question' => $question->question,
                        'answer' => optional($question->pivot)->answer ?? 'No especificada'
                    ];
                }
            }

            return response()->json($userDetails);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Usuario no encontrado: ' . $e->getMessage());
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        } catch (\Exception $e) {
            Log::error('Error al obtener detalles del usuario: ' . $e->getMessage());
            return response()->json(['error' => 'Error al obtener detalles del usuario: ' . $e->getMessage()], 500);
        }
    }
}
