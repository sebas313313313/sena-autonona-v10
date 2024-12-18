<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Municipality;
use App\Models\Identification_Type;
use App\Models\Users_Role;
use App\Models\Password;
use App\Models\SecurityQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador para gestionar el registro de nuevos usuarios
 * Maneja la creación de nuevas cuentas y la asignación de roles de usuario
 */
class RegisterController extends Controller
{
    /**
     * Muestra el formulario de registro
     * @return \Illuminate\View\View Vista del formulario con los tipos de identificación y municipios
     */
    public function showRegistrationForm()
    {
        $identificationTypes = Identification_Type::all();
        $municipalities = Municipality::all();
        $securityQuestions = SecurityQuestion::all();
        
        return view('auth.register', compact('identificationTypes', 'municipalities', 'securityQuestions'));
    }

    /**
     * Procesa el registro de un nuevo usuario
     * @param Request $request Datos del formulario de registro
     * @return \Illuminate\Http\RedirectResponse Redirección a login con éxito o back con error
     * 
     * El método realiza las siguientes operaciones:
     * - Valida los datos del formulario
     * - Crea el usuario base en la tabla users
     * - Crea el registro detallado en users_roles
     * - Almacena la contraseña en la tabla passwords
     * - Maneja la transacción para garantizar la integridad de los datos
     */
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'identification_type_id' => 'required|exists:identification_types,id',
            'identification' => 'required|max:15',
            'name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'birth_date' => 'required|date',
            'municipality_id' => 'required|exists:municipalities,id',
            'direction' => 'required|max:100',
            'contact_number' => 'required|max:15',
            'security_question_id' => 'required|exists:security_questions,id',
            'security_answer' => 'required|string|max:250'
        ]);

        try {
            DB::beginTransaction();
            
            // Crear el usuario
            $user = new User();
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->name = $request->name;
            $user->save();

            // Crear el rol de usuario
            $users_role = Users_Role::create([
                'identification' => $request->identification,
                'identification_type_id' => $request->identification_type_id,
                'name' => $request->name,
                'Last_name' => $request->last_name,
                'date_birth' => $request->birth_date,
                'municipality_id' => $request->municipality_id,
                'direction' => $request->direction,
                'contact' => $request->contact_number,
                'user_id' => $user->id
            ]);

            // Obtener la pregunta de seguridad
            $securityQuestion = SecurityQuestion::findOrFail($request->security_question_id);

            // Almacenar la contraseña en la tabla passwords
            Password::create([
                'users_role_id' => $users_role->id,
                'contrasena' => Hash::make($request->password),
                'pregunta' => $securityQuestion->question,
                'respuesta' => Hash::make($request->security_answer),
                'fecha' => now()
            ]);

            DB::commit();

            // Asegurarse de que el usuario no esté autenticado
            if (Auth::check()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            // Redireccionar al login con mensaje de éxito
            return redirect()->route('login')
                ->with('success', '¡Registro exitoso! Por favor inicia sesión con tus credenciales.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error en registro: {$e->getMessage()}");
            return back()
                ->withInput()
                ->with('error', 'Error al crear el usuario. Por favor intenta nuevamente.');
        }
    }
}
