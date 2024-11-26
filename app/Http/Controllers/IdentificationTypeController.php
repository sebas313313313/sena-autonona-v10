<?php

namespace App\Http\Controllers;

use App\Models\Identification_Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Controlador para gestionar los tipos de identificación
 * Maneja las operaciones CRUD para los diferentes tipos de documentos de identidad
 */
class IdentificationTypeController extends Controller
{
    /**
     * Obtiene y muestra una lista de todos los tipos de identificación
     * Incluye filtrado de resultados según los parámetros recibidos
     * @param Request $request Parámetros de filtrado
     * @return \Illuminate\Http\JsonResponse Lista de tipos de identificación
     */
    public function index(Request $request)
    {
        try {
            $types = Identification_Type::filter($request->all())->get();
            return response()->json(['data' => $types]);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json([
                    'error' => 'Error al obtener tipos de identificación',
                    'debug' => [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]
                ], 500);
            }
            return response()->json(['error' => 'No se pudieron cargar los tipos de identificación. Por favor, intente más tarde'], 500);
        }
    }

    /**
     * Crea un nuevo tipo de identificación en el sistema
     * @param Request $request Datos del nuevo tipo de identificación
     * @return \Illuminate\Http\JsonResponse Respuesta con el tipo creado o errores de validación
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'description' => 'required|string|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $type = Identification_Type::create($request->all());
            return response()->json([
                'message' => 'Tipo de identificación creado exitosamente',
                'data' => $type
            ], 201);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json([
                    'error' => 'Error al crear tipo de identificación',
                    'debug' => [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]
                ], 500);
            }
            return response()->json(['error' => 'No se pudo crear el tipo de identificación. Por favor, intente más tarde'], 500);
        }
    }

    /**
     * Muestra los detalles de un tipo de identificación específico
     * @param Identification_Type $identification_type El tipo de identificación a mostrar
     * @return \Illuminate\Http\JsonResponse Datos del tipo de identificación
     */
    public function show(Identification_Type $identification_type)
    {
        try {
            return response()->json(['data' => $identification_type]);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json([
                    'error' => 'Error al obtener tipo de identificación',
                    'debug' => [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]
                ], 500);
            }
            return response()->json(['error' => 'No se pudo obtener el tipo de identificación. Por favor, intente más tarde'], 500);
        }
    }

    /**
     * Actualiza la información de un tipo de identificación existente
     * @param Request $request Nuevos datos del tipo de identificación
     * @param Identification_Type $identification_type El tipo a actualizar
     * @return \Illuminate\Http\JsonResponse Respuesta con el tipo actualizado o errores
     */
    public function update(Request $request, Identification_Type $identification_type)
    {
        try {
            $validator = Validator::make($request->all(), [
                'description' => 'required|string|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $identification_type->update($request->all());
            return response()->json([
                'message' => 'Tipo de identificación actualizado exitosamente',
                'data' => $identification_type
            ]);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json([
                    'error' => 'Error al actualizar tipo de identificación',
                    'debug' => [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]
                ], 500);
            }
            return response()->json(['error' => 'No se pudo actualizar el tipo de identificación. Por favor, intente más tarde'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Identification_Type $identification_type)
    {
        try {
            $identification_type->delete();
            return response()->json(['message' => 'Tipo de identificación eliminado exitosamente']);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json([
                    'error' => 'Error al eliminar tipo de identificación',
                    'debug' => [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]
                ], 500);
            }
            return response()->json(['error' => 'No se pudo eliminar el tipo de identificación. Por favor, intente más tarde'], 500);
        }
    }
}
