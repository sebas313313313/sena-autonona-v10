<?php

namespace App\Http\Controllers;

use App\Models\Farm;
use App\Models\Municipality;
use App\Models\Users_Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

/**
 * Controlador para gestionar las granjas del sistema
 * Maneja las operaciones CRUD para las granjas y sus relaciones con usuarios y municipios
 */
class FarmController extends Controller
{
    /**
     * Obtiene y muestra la lista de todas las granjas
     * Incluye información relacionada de usuarios y municipios
     * @return \Illuminate\Http\View
     */
    public function index()
    {
        $user = auth()->user();
        Log::info('Usuario accediendo a lista de granjas: ' . $user->email);
        
        $userRole = $user->userRole;
        
        if (!$userRole) {
            Log::warning('Usuario sin rol accediendo a granjas');
            return redirect()->route('login');
        }

        // Obtener las granjas propias
        $farms = Farm::where('users_role_id', $userRole->id)
                    ->with('municipality')
                    ->get();
        Log::info('Granjas propias encontradas: ' . $farms->count());

        // Obtener las granjas a las que ha sido invitado
        $invitedFarms = $user->farms()
                            ->with('municipality')
                            ->get();
        Log::info('Granjas invitadas encontradas: ' . $invitedFarms->count());
        
        // Log de los IDs de las granjas invitadas
        foreach ($invitedFarms as $farm) {
            Log::info('Granja invitada: ID=' . $farm->id . ', Role=' . $farm->pivot->role);
        }

        // Depuración: Mostrar información en la vista
        $debug = [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'invited_farms_count' => $invitedFarms->count(),
            'invited_farms' => $invitedFarms->map(function($farm) {
                return [
                    'id' => $farm->id,
                    'address' => $farm->address,
                    'role' => $farm->pivot->role ?? 'No role'
                ];
            })
        ];

        return view('farms.index', [
            'farms' => $farms,
            'invitedFarms' => $invitedFarms,
            'municipalities' => Municipality::all(),
            'debug' => $debug
        ]);
    }

    /**
     * Almacena una nueva granja en el sistema
     * @param Request $request Datos de la nueva granja (ubicación, extensión, etc.)
     * @return \Illuminate\Http\RedirectResponse
     * 
     * Campos requeridos:
     * - address: Dirección física
     * - vereda: Ubicación específica rural
     * - extension: Tamaño de la granja
     * - municipality_id: ID del municipio
     * - latitude: Coordenada de latitud
     * - longitude: Coordenada de longitud
     */
    public function store(Request $request)
    {
        $userRole = auth()->user()->userRole;
        if (!$userRole) {
            return redirect()->route('login');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'vereda' => 'required|string|max:255',
            'extension' => 'required|numeric',
            'municipality_id' => 'required|exists:municipalities,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $farm = new Farm($request->all());
        $farm->users_role_id = $userRole->id;
        $farm->save();

        return redirect()->back()->with('success', 'Granja creada exitosamente.');
    }

    /**
     * Muestra los detalles de una granja específica
     * @param Farm $farm La granja a consultar
     * @return \Illuminate\Http\JsonResponse Datos de la granja y sus relaciones
     */
    public function show(Farm $farm)
    {
        try {
            return response()->json([
                'data' => $farm->load(['usersRole', 'municipality'])
            ]);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json([
                    'error' => 'Error al obtener granja',
                    'debug' => [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]
                ], 500);
            }
            return response()->json(['error' => 'No se pudo obtener la granja. Por favor, intente más tarde'], 500);
        }
    }

    /**
     * Actualiza la información de una granja existente
     * @param Request $request Nuevos datos de la granja
     * @param Farm $farm La granja a actualizar
     * @return \Illuminate\Http\JsonResponse Respuesta con la granja actualizada
     */
    public function update(Request $request, Farm $farm)
    {
        try {
            $validator = Validator::make($request->all(), [
                'latitude' => 'numeric',
                'longitude' => 'numeric',
                'address' => 'string|max:100',
                'vereda' => 'string|max:50',
                'extension' => 'string|max:50',
                'users_role_id' => 'exists:users_roles,id',
                'municipality_id' => 'exists:municipalities,id'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $farm->update($request->all());
            return response()->json([
                'message' => 'Granja actualizada exitosamente',
                'data' => $farm->load(['usersRole', 'municipality'])
            ]);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json([
                    'error' => 'Error al actualizar granja',
                    'debug' => [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]
                ], 500);
            }
            return response()->json(['error' => 'No se pudo actualizar la granja. Por favor, intente más tarde'], 500);
        }
    }

    /**
     * Elimina una granja específica del sistema
     * @param Farm $farm La granja a eliminar
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Farm $farm)
    {
        if ($farm->users_role_id !== auth()->user()->userRole->id) {
            return redirect()->back()->with('error', 'No tienes permiso para eliminar esta granja.');
        }

        $farm->delete();
        return redirect()->back()->with('success', 'Granja eliminada exitosamente.');
    }
}
