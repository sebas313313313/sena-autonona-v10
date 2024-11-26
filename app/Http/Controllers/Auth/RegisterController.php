<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Municipality;
use App\Models\Identification_Type;
use App\Models\Users_Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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
        
        return view('auth.register', compact('identificationTypes', 'municipalities'));
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
            'contact_number' => 'required|max:15'
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
            Users_Role::create([
                'user_id' => $user->id,
                'identification_type_id' => $request->identification_type_id,
                'identification' => $request->identification,
                'name' => $request->name,
                'Last_name' => $request->last_name,
                'date_birth' => $request->birth_date,
                'municipality_id' => $request->municipality_id,
                'direction' => $request->direction,
                'contact' => $request->contact_number
            ]);

            DB::commit();

            // Redireccionar al login con mensaje de éxito
            return redirect()->route('login')
                ->with('success', '¡Registro exitoso! Por favor inicia sesión con tus credenciales.');

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error en registro: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Error al crear el usuario. Por favor intenta nuevamente.');
        }
    }
}
