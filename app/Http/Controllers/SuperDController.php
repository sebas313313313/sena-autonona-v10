<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Users_Role;
use App\Models\Farm;
use App\Models\Component; // Agregado el modelo Component
use App\Models\Farm_Component;
use App\Models\Sensor_Component;
use App\Models\Sensor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
}
