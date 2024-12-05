<?php

namespace App\Http\Controllers;

use App\Models\Farm;
use App\Models\Municipality;
use App\Models\Users_Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Controlador para gestionar las granjas del sistema
 * Maneja las operaciones CRUD para las granjas y sus relaciones con usuarios y municipios
 */
class FarmController extends Controller
{
    /**
     * Obtiene y muestra la lista de todas las granjas
     * Incluye información relacionada de usuarios y municipios
     * @param Request $request Parámetros de filtrado
     * @return \Illuminate\Http\JsonResponse Lista de granjas con sus relaciones
     */
    public function index(Request $request)
    {
        try {
            $farms = Farm::filter($request->all())->with(['usersRole', 'municipality'])->get();
            $municipalities = Municipality::all();
            
            if ($request->wantsJson()) {
                return response()->json(['data' => $farms]);
            }

            return view('farms.index', compact('farms', 'municipalities'));
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json([
                    'error' => 'Error al obtener granjas',
                    'debug' => [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]
                ], 500);
            }
            return response()->json(['error' => 'No se pudieron cargar las granjas. Por favor, intente más tarde'], 500);
        }
    }

    /**
     * Almacena una nueva granja en el sistema
     * @param Request $request Datos de la nueva granja (ubicación, extensión, etc.)
     * @return \Illuminate\Http\JsonResponse Respuesta con la granja creada y sus relaciones
     * 
     * Campos requeridos:
     * - latitude, longitude: Coordenadas de ubicación
     * - address: Dirección física
     * - vereda: Ubicación específica rural
     * - extension: Tamaño de la granja
     * - users_role_id: ID del usuario propietario
     * - municipality_id: ID del municipio
     */
    public function store(Request $request)
    {
        try {
            $userRole = Users_Role::where('user_id', auth()->id())->first();
            if (!$userRole) {
                return response()->json(['error' => 'No se encontró el rol del usuario'], 422);
            }
            $request->merge(['users_role_id' => $userRole->id]);

            $validator = Validator::make($request->all(), [
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'address' => 'required|string|max:100',
                'vereda' => 'required|string|max:50',
                'extension' => 'required|string|max:50',
                'users_role_id' => 'required|exists:users_roles,id',
                'municipality_id' => 'required|exists:municipalities,id'
            ]);

            if ($validator->fails()) {
                if ($request->wantsJson()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                }
                return back()->withErrors($validator)->withInput();
            }

            $farm = Farm::create($request->all());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Granja creada exitosamente',
                    'data' => $farm->load(['usersRole', 'municipality'])
                ], 201);
            }

            return redirect()->route('home')->with('success', 'Granja creada exitosamente');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json([
                    'error' => 'Error al crear granja',
                    'debug' => [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]
                ], 500);
            }
            return response()->json(['error' => 'No se pudo crear la granja. Por favor, intente más tarde'], 500);
        }
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
     * Elimina una granja existente del sistema
     * @param Farm $farm La granja a eliminar
     * @return \Illuminate\Http\JsonResponse Respuesta de eliminación
     */
    public function destroy(Farm $farm)
    {
        try {
            $farm->delete();
            return redirect()->route('farms.index')->with('success', 'Granja eliminada exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('farms.index')->with('error', 'No se pudo eliminar la granja');
        }
    }
}
